package com.government.datakit.utils;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;
import java.util.UUID;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.government.datakit.bo.trackingPoint;
import com.government.datakit.db.DataBaseAdapter;
import com.government.datakit.ui.MainScreen;
import com.government.datakit.ui.R;

import android.app.AlarmManager;
import android.app.AlertDialog;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.Service;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.media.RingtoneManager;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.IBinder;
import android.preference.PreferenceManager;
import android.telephony.TelephonyManager;
import android.util.Log;
import android.widget.Toast;


public class trackerService extends Service {
   

    private LocationManager locationManager;
    private Polygon polygon;
	private Location gpsLocation = null;
	private String DebugTracking;
	private String DebugGeoFencing;
	private String GeoFence;
   @Override
   public IBinder onBind(Intent arg0) {
      return null;
   }

   @Override 
   public int onStartCommand(Intent intent, int flags, int startId) {
      Log.e("TRACKER","TRACKING STARTED!!");
      this.DebugTracking =ReadSetting("DebugTracking");
      this.DebugGeoFencing =ReadSetting("DebugGeoFencing");
      	if(this.DebugTracking.equalsIgnoreCase("YES"))
		{
		Toast.makeText(getBaseContext(), "Tracker Started",Toast.LENGTH_LONG).show();
		}
	   Boolean IS_NETWORK_AVAILABLE = haveNetworkConnection(this.getBaseContext());
       Log.i("internet status",""+IS_NETWORK_AVAILABLE); 
   locationManager = (LocationManager) this.getBaseContext().getSystemService(Context.LOCATION_SERVICE);
	gpsLocation=locationManager.getLastKnownLocation(LocationManager.GPS_PROVIDER);
//	gpsLocation=locationManager.getLastKnownLocation(LocationManager.NETWORK_PROVIDER);				//used only for debugging in office
	 
	
	long minTimeInterval = Long.parseLong(ReadSetting("TrackingInterval"));
	long minDistance = Long.parseLong(ReadSetting("TrackingDistance"));
	
	
        final AlarmManager alarmManager = (AlarmManager) this.getBaseContext().getSystemService(Context.ALARM_SERVICE);

        registerReceiver(stopServiceReceiver, new IntentFilter("myFilter"));
		PendingIntent pIntent = PendingIntent.getBroadcast(this, 0, new Intent("myFilter"), PendingIntent.FLAG_UPDATE_CURRENT);

        Calendar calendar = Calendar.getInstance();

        calendar.add(Calendar.DATE, 1);
        calendar.set(Calendar.HOUR_OF_DAY, 0);
        calendar.set(Calendar.MINUTE, 0);
        calendar.set(Calendar.SECOND, 0);

        Log.e("alarmManger", String.format("Setting up alarm for midnight %s", calendar.getTime().toString()));

        alarmManager.set(AlarmManager.RTC_WAKEUP, calendar.getTimeInMillis(), pIntent);
    
	
	
	SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(getBaseContext());
	
	String routeId = prefs.getString("routeId", UUID.randomUUID().toString());  
	 
	if(ReadSetting("hasGeoFencing").equalsIgnoreCase("YES"))
	{
		 
	
			DataBaseAdapter dbAdapter = new DataBaseAdapter(getBaseContext());
			dbAdapter.open();
			this.GeoFence=dbAdapter.getGeoFence();
			dbAdapter.close();
			Polygon.Builder b=Polygon.Builder();
			
			try {
				JSONArray ja=new JSONArray(this.GeoFence);
				for(int i=0;i<ja.length();i++)
				{
					
					JSONObject jo=ja.getJSONObject(i);
					b.addVertex(new Point((float)jo.getDouble("lat"),(float)jo.getDouble("lng")));
					
					Log.e("Points for polygon",""+(float)jo.getDouble("lat")+","+(float)jo.getDouble("lng"));
				}
				
			} catch (JSONException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		 
			this.polygon=b.build();
		 
	}
	Editor editor = prefs.edit();
	editor.putString("routeId", routeId);
	editor.putFloat("distanceCommutedInRoute", 0f);
	editor.putFloat("distanceCommutedInGeoFence", 0f);
	editor.putFloat("lat", 0f);
	editor.putFloat("lng", 0f);
	editor.putFloat("geolat", 0f);
	editor.putFloat("geolng", 0f);
	editor.putInt("iteration", 0);
	
	editor.commit();
	
	if(this.DebugTracking.equalsIgnoreCase("YES"))
	{
		showUpdateNotification("http://google.com");
	}
	 		

 
//	locationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER , 0,0, gpsLocationUpdateListener);		//used only for debugging in office
	locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER , 0,0, gpsLocationUpdateListener);
		
      return START_STICKY;
   }
   
   
   
   private LocationListener gpsLocationUpdateListener=new LocationListener() {
		
		@Override
		public void onStatusChanged(String provider, int status, Bundle extras) {
			Log.d("trackerService","Tracker GpsStatusChanged="+status);
			
		}
		
		@Override
		public void onProviderEnabled(String provider) {
			// TODO Auto-generated method stub
			
		}
		
		@Override
		public void onProviderDisabled(String provider) {
			// TODO Auto-generated method stub
			
		}
		
		@Override
		public void onLocationChanged(Location location) {
//			location.setSpeed(1f); 			//for testing
			Boolean inGeoFence=false;
			if(ReadSetting("hasGeoFencing").equalsIgnoreCase("YES"))
			{
			inGeoFence=trackerService.this.polygon.contains(new Point((float)location.getLatitude(), (float)location.getLongitude()));
			}
			SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(getBaseContext()); 
			int i=preferences.getInt("iteration", 0);
			Editor editor = preferences.edit();
			double previousLat=preferences.getFloat("lat", 0f);
			double previousLng=preferences.getFloat("lng", 0f);
			double previousGeoLat=preferences.getFloat("geolat", 0f);
			double previousGeoLng=preferences.getFloat("geolng", 0f);
			double distanceCommutedInRoute=preferences.getFloat("distanceCommutedInRoute", 0f);
			double distanceCommutedInGeoFence=preferences.getFloat("distanceCommutedInGeoFence", 0f);
			String isGeo="NO";
			

			Log.d("trackerService","Inside Tracker gps location changes");
			gpsLocation=location;
     
			
//			if(previousLat==0 || previousGeoLat==0)
			if(previousLat==0)
			{
				
			}
			else{
				
				if(location.getSpeed()>0.0f)
				{
					
				
				Location locationA = new Location("point A");

				locationA.setLatitude(previousLat);
				locationA.setLongitude(previousLng);

				Location locationB = new Location("point B");

				locationB.setLatitude(location.getLatitude());
				locationB.setLongitude(location.getLongitude());

				float distance = locationA.distanceTo(locationB);
				distance=(float) (distance+distanceCommutedInRoute);
				editor.putFloat("distanceCommutedInRoute", distance);
				editor.commit();
				
				if(inGeoFence==true)
	            {
					Location geolocationA = new Location("point c");
	
					geolocationA.setLatitude(previousGeoLat);
					geolocationA.setLongitude(previousGeoLng);
	
					Location geolocationB = new Location("point d");
	
					geolocationB.setLatitude(location.getLatitude());
					geolocationB.setLongitude(location.getLongitude());
	
					float geoDistance = geolocationA.distanceTo(geolocationB);
					geoDistance=(float) (geoDistance+distanceCommutedInGeoFence);
					editor.putFloat("distanceCommutedInGeoFence", geoDistance);
					editor.commit();
	            }
				
				
				}
				

			}
						
			
			
			String routeId = preferences.getString("routeId", ""); 
			
			long minTimeInterval = Long.parseLong(ReadSetting("TrackingInterval"));
			if(minTimeInterval<1)
			{
				minTimeInterval=1;			// min on tracker can be 1 sec
			}

			if( i % minTimeInterval==0 && location.getSpeed()>0.0f)
			{
				
				 
		 		if(inGeoFence==true)
	            {
		 			isGeo="YES";
		 			if(trackerService.this.DebugGeoFencing.equalsIgnoreCase("YES"))
		 			{
	                Toast.makeText(getBaseContext(), "You are inside of geoFence", Toast.LENGTH_SHORT).show();
		 			}
	            }
	            else
	            {
	            	if(trackerService.this.DebugGeoFencing.equalsIgnoreCase("YES"))
		 			{
	                Toast.makeText(getBaseContext(), "You are outside of geoFence", Toast.LENGTH_SHORT).show();
		 			}

	            } 
				Boolean IS_NETWORK_AVAILABLE = haveNetworkConnection(getBaseContext());
				if(IS_NETWORK_AVAILABLE && false)		//deliberatly making this false, so we always send bulk points
				{
					submitTrackerPoint submitPoint = new submitTrackerPoint(getBaseContext());
					submitPoint.execute(getBaseContext().getString(R.string.TRACKING_URL),
							""+location.getLatitude()+','+location.getLongitude(), ""+location.getLatitude() , ""+location.getLongitude(), ""+location.getAccuracy(),
							""+location.getAltitude(), ""+location.getSpeed(),""+Utility.getFormattedTime(location.getTime()),Utility.getCurrentDate(new Date()),routeId);
					
				}  
				else{
					DataBaseAdapter dbAdapter = new DataBaseAdapter(getBaseContext());
					dbAdapter.open();
//					TelephonyManager telephonyManager = (TelephonyManager)getBaseContext().getSystemService(Context.TELEPHONY_SERVICE);
//					String IMEI = telephonyManager.getDeviceId();
					String IMEI = Utility.getDeviceUniqueId(getBaseContext());
					String appId=getBaseContext().getString(R.string.app_id);
					double distance=preferences.getFloat("distanceCommutedInRoute", 0f);
					distance=distance/1000;
					String d = String.format(Locale.US, "%.2f", distance); 		//convert meters to km 
					
					double distanceGeo=preferences.getFloat("distanceCommutedInGeoFence", 0f);
					distanceGeo=distanceGeo/1000;
					String dgeo = String.format(Locale.US, "%.2f", distanceGeo); 		//convert meters to km 
					
					if(trackerService.this.DebugTracking.equalsIgnoreCase("YES"))
					{
					Toast.makeText(getBaseContext(), "Got new Location: "+d+"KM",Toast.LENGTH_SHORT).show();
					}
					
					
					dbAdapter.saveTrackerPoint(""+location.getLatitude()+','+location.getLongitude(), ""+location.getLatitude() , ""+location.getLongitude(), ""+location.getAccuracy(),
							""+location.getAltitude(), ""+location.getSpeed()*3.6,""+Utility.getFormattedTime(location.getTime()),Utility.getCurrentDate(new Date()),IMEI,appId,routeId,d,isGeo,dgeo);
					dbAdapter.close();
				}
			
			}
			
			i=i+1;
			
			editor.putFloat("lat", Float.valueOf(String.valueOf(location.getLatitude())));
			editor.putFloat("lng", Float.valueOf(String.valueOf(location.getLongitude())));
			if(inGeoFence==true)
            {
				editor.putFloat("geolat", Float.valueOf(String.valueOf(location.getLatitude())));
				editor.putFloat("geolng", Float.valueOf(String.valueOf(location.getLongitude())));
            }
			else
            {
//				editor.putFloat("geolat", 0f);
//				editor.putFloat("geolng", 0f);
            }
           
			editor.putInt("iteration", i);
			editor.commit();
			
			
			
			
		}
	};
	//We need to declare the receiver with onReceive function as below
	protected BroadcastReceiver stopServiceReceiver = new BroadcastReceiver() {   
	  @Override
	  public void onReceive(Context context, Intent intent) {
 
		  stopSelf();
		  
		  
	  }
	};

	public void showUpdateNotification(String urls)
	{
		
		Context context=this;
//		Context context=mcontext;
		 	    
	    
		 
		  PendingIntent contentIntent = PendingIntent.getBroadcast(this, 0, new Intent("myFilter"), PendingIntent.FLAG_UPDATE_CURRENT);
		     
		  
		Intent intent = new Intent(android.content.Intent.ACTION_VIEW, Uri.parse(urls));
		 
	     
//		PendingIntent pIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT);
		registerReceiver(stopServiceReceiver, new IntentFilter("myFilter"));
		PendingIntent pIntent = PendingIntent.getBroadcast(this, 0, new Intent("myFilter"), PendingIntent.FLAG_UPDATE_CURRENT);
		
		Notification noti = new Notification.Builder(context)
		.setTicker(context.getResources().getString(R.string.app_name))
		.setContentTitle(context.getResources().getString(R.string.app_name))
		.setContentText("Tracking In Progress")
		.setSmallIcon(R.drawable.icon)
		.setSound(RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION))
		.setContentIntent(pIntent).getNotification();
		
//		noti.flags=Notification.FLAG_AUTO_CANCEL;
		noti.flags=Notification.FLAG_ONGOING_EVENT | Notification.FLAG_NO_CLEAR;

		NotificationManager notificationManager = (NotificationManager) context.getSystemService(context.NOTIFICATION_SERVICE);
		notificationManager.notify(424, noti); 
		
		
	}

   private boolean haveNetworkConnection(Context context) {
       boolean haveConnectedWifi = false;
       boolean haveConnectedMobile = false;

       ConnectivityManager cm = (ConnectivityManager)   context.getSystemService(Context.CONNECTIVITY_SERVICE);
       NetworkInfo[] netInfo = cm.getAllNetworkInfo();
       for (NetworkInfo ni : netInfo) {
           if (ni.getTypeName().equalsIgnoreCase("WIFI"))
               if (ni.isConnected())
                   haveConnectedWifi = true;
           if (ni.getTypeName().equalsIgnoreCase("MOBILE"))
               if (ni.isConnected())
                   haveConnectedMobile = true;
       }
       return haveConnectedWifi || haveConnectedMobile;    
   }

   public String ReadSetting(String S)
	{
		SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(this);
		String value = preferences.getString(S, "");  
		
		if(value=="") 
		{ 
			if(S.equalsIgnoreCase("IS_SECURE_APP"))
			{
				value=this.getBaseContext().getResources().getString(R.string.IS_SECURE_APP);
			}
			else if(S.equalsIgnoreCase("showHighResOption"))
			{
				value=this.getBaseContext().getResources().getString(R.string.showHighResOption);
			}
			else if(S.equalsIgnoreCase("PersistImagesOnDevice"))
			{
				value=this.getBaseContext().getResources().getString(R.string.PersistImagesOnDevice);
			}
			else if(S.equalsIgnoreCase("BackgroundUpdate"))
			{
				value=this.getBaseContext().getResources().getString(R.string.BackgroundUpdate);
			}
			else if(S.equalsIgnoreCase("ForceUpdate"))
			{
				value=this.getBaseContext().getResources().getString(R.string.ForceUpdate);
			}
			else if(S.equalsIgnoreCase("EnableAutoTime"))
			{
				value=this.getBaseContext().getResources().getString(R.string.EnableAutoTime);
			}
			else if(S.equalsIgnoreCase("TrackingInterval"))
			{
				value=this.getBaseContext().getResources().getString(R.string.TrackingInterval);
			}
			else if(S.equalsIgnoreCase("TrackingDistance"))
			{
				value=this.getBaseContext().getResources().getString(R.string.TrackingDistance);
			}
			else if(S.equalsIgnoreCase("DebugTracking"))
			{
				value=this.getBaseContext().getResources().getString(R.string.DebugTracking);
			}
			else if(S.equalsIgnoreCase("hasGeoFencing"))
			{
				value=this.getBaseContext().getResources().getString(R.string.hasGeoFencing);
			}
			else if(S.equalsIgnoreCase("DebugGeoFencing"))
			{
				value=this.getBaseContext().getResources().getString(R.string.DebugGeoFencing);
			}
			else{
				value="";
			}

	     
		
		}
		
		return value;
	}


   
   @Override
   public void onDestroy() {
	   
      
      locationManager.removeUpdates(gpsLocationUpdateListener);
 	 
	  	SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(getBaseContext());
	  	Editor editor = prefs.edit();
	  	
	  	editor.putString("routeId", "");
	  	editor.putInt("iteration",0);
	  	editor.remove("routeId");
	  	editor.commit();
	  	if(trackerService.this.DebugTracking.equalsIgnoreCase("YES"))
		{
	  		Toast.makeText(this, "Tracker Stopped", Toast.LENGTH_LONG).show();
	  		unregisterReceiver(stopServiceReceiver);
		}
      	this.sendPendingTrackingData();
		NotificationManager notificationManager = (NotificationManager) getSystemService(NOTIFICATION_SERVICE);
		notificationManager.cancel(424);
		super.onDestroy();
   }
   
   public void sendPendingTrackingData(){
       
       DataBaseAdapter dbAdapter = new DataBaseAdapter(this.getBaseContext());
		dbAdapter.open();
		ArrayList<trackingPoint> trackingarrayList = dbAdapter.readTrackingData();
		
		
		
		ArrayList<Integer> sentids = new ArrayList<Integer>();
		if(trackingarrayList!=null && trackingarrayList.size()>0){
			
			Log.e("TP","You have "+trackingarrayList.size()+" unsent Tracking Points");
			JSONArray jsArray = new JSONArray();
			
			
			for (trackingPoint tp : trackingarrayList) 
			{  
				JSONObject o=new JSONObject();
				try {
					o.putOpt("id", ""+tp.id);
					sentids.add(tp.id);
					o.putOpt("location", tp.location);
					o.putOpt("lat", tp.lat);
					o.putOpt("lng", tp.lng);
					o.putOpt("accuracy", tp.accuracy);
					o.putOpt("altitude", tp.altitude);
					o.putOpt("speed", tp.speed);
					o.putOpt("gpsTime", tp.gpsTime);
					o.putOpt("deviceTS", tp.deviceTS);
					o.putOpt("imei_no", tp.imei_no);
					o.putOpt("appId", tp.appId);
					o.putOpt("routeId", tp.routeId);
					o.putOpt("distance", tp.distance);
					o.putOpt("InGeoFence", tp.inGeoFence);
					o.putOpt("distanceGeo", tp.distanceGeo);
					jsArray.put(o);
					
				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				
				
			}
			
			Log.e("ARRAY SIZE",""+jsArray.length());
			Log.e("TP","Made the array");
			
			if(Utility.isInternetAvailable(this))
			{
				
				submitTrackerPoint submitPoint = new submitTrackerPoint(getBaseContext());
				submitPoint.execute(getBaseContext().getString(R.string.TRACKING_URLBulk),jsArray.toString());
				
				
			}
			  
			dbAdapter.close();
			
			
		}
	}

}
