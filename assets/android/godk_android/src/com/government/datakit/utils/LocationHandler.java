package com.government.datakit.utils;

import java.util.Date;

import android.annotation.TargetApi;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.location.Criteria;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Build;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.provider.Settings;
import android.util.Log;

import com.government.datakit.bo.LocationInfo;

public class LocationHandler {
	private static final String TAG="LocationHandler";
	private static final String PREF_LATITUDE_KEY="LATITUDE";
	private static final String PREF_LONGITUDE_KEY="LONGITUDE";
	private static final String PREF_PROVIDER_KEY="PROVIDER";
	private static final String PREF_TIME_KEY="TIME";
	private static final String PREF_ACCURACY_KEY="ACCURACY";
	private static final long TIME_THRESHOLD=60*1000;
	
	private static final long minTimeInterval = 5 * 1000; 
	private static final long  minDistance = 0; 
	
	
	private LocationManager locationManager;
	private Location gpsLocation = null;
	private Location networkLocation = null;
	private LocationInfo currentBestLocationInfo;
	private Context mContext=null;
	private String debugStatus="";
	
	public LocationHandler(Context context){
		debugStatus="Inside LocationHandler Constructor\n";
		this.mContext=context;
		currentBestLocationInfo=getSavedLocationFromPref();
		
		if(currentBestLocationInfo!=null)
		{
			if(currentBestLocationInfo.getLocationProvider()!=null)
			{
				if(currentBestLocationInfo.getLocationProvider().contains("_LK"))
				{
					String s=currentBestLocationInfo.getLocationProvider().split("_")[0];
					currentBestLocationInfo.setLocationProvider(s+"_LK");
				}
				else{
					currentBestLocationInfo.setLocationProvider(currentBestLocationInfo.getLocationProvider()+"_LK");
				}
			}
		}
		locationManager = (LocationManager) mContext.getSystemService(Context.LOCATION_SERVICE);
		gpsLocation=locationManager.getLastKnownLocation(LocationManager.GPS_PROVIDER);
		networkLocation=locationManager.getLastKnownLocation(LocationManager.NETWORK_PROVIDER);
		Log.d(TAG,"Inside constructor gpsLoca="+gpsLocation+" NetworkLoca="+networkLocation+" prevbest="+currentBestLocationInfo);
		if(gpsLocation !=null){
			gpsLocation.setProvider("gps_LK");
			determineCurrentBestLocation(gpsLocation);
		}else if(networkLocation!=null){
			networkLocation.setProvider("network_LK");
			determineCurrentBestLocation(networkLocation);
		}
	}
	public void onResume(){
		debugStatus=debugStatus+"Inside onResume\n";
		
		if(currentBestLocationInfo!=null)
		{
			if(currentBestLocationInfo.getLocationProvider()!=null)
			{
				if(currentBestLocationInfo.getLocationProvider().contains("_LK"))
				{
					String s=currentBestLocationInfo.getLocationProvider().split("_")[0];
					currentBestLocationInfo.setLocationProvider(s+"_LK");
				}
				else{
					currentBestLocationInfo.setLocationProvider(currentBestLocationInfo.getLocationProvider()+"_LK");
				}
			}
		}
		
		locationManager = (LocationManager) mContext.getSystemService(Context.LOCATION_SERVICE);
		gpsLocation=locationManager.getLastKnownLocation(LocationManager.GPS_PROVIDER);
		networkLocation=locationManager.getLastKnownLocation(LocationManager.NETWORK_PROVIDER);
		Log.d(TAG,"Inside constructor gpsLoca="+gpsLocation+" NetworkLoca="+networkLocation+" prevbest="+currentBestLocationInfo);
		if(gpsLocation !=null){
			gpsLocation.setProvider("gps_LK");
			determineCurrentBestLocation(gpsLocation);
		}else if(networkLocation!=null){
			networkLocation.setProvider("network_LK");
			determineCurrentBestLocation(networkLocation);
		}
		
		Utility.writeFileToSdCard(debugStatus);
		if(isMockSettingsON(mContext)){
			try {
			          Log.d("mock" ,"Removing Test providers");
			          locationManager.removeTestProvider(LocationManager.GPS_PROVIDER);
			          locationManager.removeTestProvider(LocationManager.NETWORK_PROVIDER);
			     } catch (IllegalArgumentException error) {
			          Log.d("mock","Got exception in removing test  provider");
			     }
			createMockDisabledAlert();
			return;

		}
		
		if (!locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER) || !locationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER)){  
			createGpsDisabledAlert();  
			return;
		}
		debugStatus=debugStatus+"Provider Enabled Check Passed. LocationListenerRequest Update done";
		Utility.writeFileToSdCard(debugStatus);
	
		locationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER, minTimeInterval,minDistance, networkLocationUpdateListener);
		locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, minTimeInterval,minDistance, gpsLocationUpdateListener);
	}
	public void onPause(){
		Log.d(TAG,"Inside onPause");
		locationManager.removeUpdates(networkLocationUpdateListener);
		locationManager.removeUpdates(gpsLocationUpdateListener);
		if(currentBestLocationInfo !=null){
		    saveLocationToPrefernces(currentBestLocationInfo);
		}
	}
	public boolean isLocationAvailable(){
		return currentBestLocationInfo!=null;
	}
	public LocationInfo getCurrentBestLocationInfo(){
		if(isLocationAvailable())
		{
			return currentBestLocationInfo;
		}
		else{
			LocationInfo temp = new LocationInfo();
			temp.setLatitude(0);
			temp.setLongitude(0);
			temp.setAccuracy(0);
			temp.setLocationTime(new Date().getTime());
			temp.setLocationProvider(LocationManager.GPS_PROVIDER);
			return temp;
		}
		
//		return currentBestLocationInfo;
	}
	
	
//	
//	private  boolean isMockSettingsON(Context context) {
//		  if (Settings.Secure.getString(context.getContentResolver(),Settings.Secure.ALLOW_MOCK_LOCATION).equals("0"))
//		     return false;
//		  else
//		     return true;
//	 }
//	
	 public static boolean isMockSettingsON(Context context) {
		 boolean isMock = false;
		 if (android.os.Build.VERSION.SDK_INT >= 18) {
		     // this will be handled in onLocationUpdate
		 } else {
		     isMock = !Settings.Secure.getString(context.getContentResolver(), Settings.Secure.ALLOW_MOCK_LOCATION).equals("0");
		 }
		 return isMock;

	 }
	
	
	
	private void createGpsDisabledAlert(){  
			AlertDialog.Builder builder = new AlertDialog.Builder(mContext);  
			builder.setMessage("Please enable location services before you proceed.")  
			.setCancelable(false)  
			.setPositiveButton("Enable",  new DialogInterface.OnClickListener(){  
				public void onClick(DialogInterface dialog, int id){  
					dialog.dismiss();
					showGpsOptions();  			
				}  
			});  
			AlertDialog alert = builder.create();  
			alert.show(); 	
	}
	private void createMockDisabledAlert(){ 
		AlertDialog.Builder builder = new AlertDialog.Builder(mContext);  
		builder.setMessage("Please Disable mock locations in the phone to continue using this app")  
		.setCancelable(false)
		.setPositiveButton("Disable",  new DialogInterface.OnClickListener(){  
			public void onClick(DialogInterface dialog, int id){  
				dialog.dismiss();
				showDeveloperOptions();  			
			}  
		});  
		AlertDialog alert = builder.create();  
		alert.show(); 
	
}
	private void showGpsOptions(){  
		Intent gpsOptionsIntent = new Intent(android.provider.Settings.ACTION_LOCATION_SOURCE_SETTINGS);
		mContext.startActivity(gpsOptionsIntent);  
	}
	private void showDeveloperOptions(){  
		Intent gpsOptionsIntent = new Intent(android.provider.Settings.ACTION_APPLICATION_DEVELOPMENT_SETTINGS);
		mContext.startActivity(gpsOptionsIntent);  
	}
	
	
	private LocationListener networkLocationUpdateListener=new LocationListener() {
		
		@Override
		public void onStatusChanged(String provider, int status, Bundle extras) {
			Log.d(TAG,"NetworkStatusChanged="+status);
			
		}
		
		@Override
		public void onProviderEnabled(String provider) {
			// TODO Auto-generated method stub
			
		}
		
		@Override
		public void onProviderDisabled(String provider) {
			// TODO Auto-generated method stub
			
		}
		
		@TargetApi(Build.VERSION_CODES.JELLY_BEAN_MR2) @Override
		public void onLocationChanged(Location location) {
			debugStatus=debugStatus+"Inside Network LocationChanged Method Time="+Utility.getFormattedTime(location.getTime())+" \n";
			Utility.writeFileToSdCard(debugStatus);
			boolean isMock = false;
			if (Build.VERSION.SDK_INT >= 18) {
			    isMock = location.isFromMockProvider();
			    if(isMock)
			    {
			    	createMockDisabledAlert();
			    }
			    
			}
			Log.d(TAG,"Inside network location changes");
			networkLocation=location;
			determineCurrentBestLocation(networkLocation);
			
		}
	};
	
	private LocationListener gpsLocationUpdateListener=new LocationListener() {
		
		@Override
		public void onStatusChanged(String provider, int status, Bundle extras) {
			Log.d(TAG,"GpsStatusChanged="+status);
			
		}
		
		@Override
		public void onProviderEnabled(String provider) {
			// TODO Auto-generated method stub
			
		}
		
		@Override
		public void onProviderDisabled(String provider) {
			// TODO Auto-generated method stub
			
		}
		
		@TargetApi(Build.VERSION_CODES.JELLY_BEAN_MR2) @Override
		public void onLocationChanged(Location location) {
			debugStatus=debugStatus+"Inside GPS LocationChanged Method Time="+Utility.getFormattedTime(location.getTime())+" \n";
			Utility.writeFileToSdCard(debugStatus);
			boolean isMock = false;
			if (Build.VERSION.SDK_INT >= 18) {
			    isMock = location.isFromMockProvider();
			    if(isMock)
			    {
			    	createMockDisabledAlert();
			    }
			    
			}
			Log.d(TAG,"Inside gps location changes");
			gpsLocation=location;
			determineCurrentBestLocation(gpsLocation);
			
		}
	};
	
	private synchronized void determineCurrentBestLocation(Location newLocation) {
		Location selectedBestLocation=null;
		// If there is no other previous location, then set this newlocation as best one
		if (currentBestLocationInfo == null) {
			selectedBestLocation=newLocation;
			
			currentBestLocationInfo = new LocationInfo();
			currentBestLocationInfo.setLatitude(selectedBestLocation.getLatitude());
			currentBestLocationInfo.setLongitude(selectedBestLocation.getLongitude());
			currentBestLocationInfo.setAccuracy(selectedBestLocation.getAccuracy());
			currentBestLocationInfo.setLocationTime(selectedBestLocation.getTime());
			currentBestLocationInfo.setLocationProvider(selectedBestLocation.getProvider());	
			return ;
		}
		String debugOldTime=Utility.getFormattedTime(currentBestLocationInfo.getLocationTime());
		String debugNewTime=Utility.getFormattedTime(newLocation.getTime());
		String debugBestDescription="Old Location is best";
		boolean isMoreAccurate=newLocation.getAccuracy()<currentBestLocationInfo.getAccuracy();
		
		// First check new location is more accurate, If yes then make sure its time is not in past
		 long timeDelta = newLocation.getTime() - currentBestLocationInfo.getLocationTime();
		if(timeDelta>0){
			  selectedBestLocation=newLocation;
		}
		if(selectedBestLocation !=null){
			debugBestDescription="New Location is best now";
			currentBestLocationInfo.setLatitude(selectedBestLocation.getLatitude());
			currentBestLocationInfo.setLongitude(selectedBestLocation.getLongitude());
			currentBestLocationInfo.setAccuracy(selectedBestLocation.getAccuracy());
			currentBestLocationInfo.setLocationTime(selectedBestLocation.getTime());
			currentBestLocationInfo.setLocationProvider(selectedBestLocation.getProvider());	
		}
		Log.d(TAG,"OldLocTime="+debugOldTime+"  NewLocaTime="+debugNewTime+" IsNewLocMoreAccurate="+isMoreAccurate+" Result="+debugBestDescription);
		
	}
	
	
	
	public void saveLocationToPrefernces(LocationInfo location){
		SharedPreferences sharedPreferences = PreferenceManager.getDefaultSharedPreferences(mContext);
		SharedPreferences.Editor editor=sharedPreferences.edit();
		
		editor.putString(PREF_LATITUDE_KEY, ""+location.getLatitude());
		editor.putString(PREF_LONGITUDE_KEY, ""+location.getLongitude());
		editor.putFloat(PREF_ACCURACY_KEY, location.getAccuracy());
		editor.putLong(PREF_TIME_KEY, location.getLocationTime());
		editor.putString(PREF_PROVIDER_KEY, location.getLocationProvider());
		
		editor.commit();
		
	}
	public LocationInfo getSavedLocationFromPref(){
		SharedPreferences sharedPreferences = PreferenceManager.getDefaultSharedPreferences(mContext);
		String latitude=sharedPreferences.getString(PREF_LATITUDE_KEY, "NULL");
		String longitude=sharedPreferences.getString(PREF_LONGITUDE_KEY, "NULL");
		float accuracy=sharedPreferences.getFloat(PREF_ACCURACY_KEY, 0.0f);
		String provider=sharedPreferences.getString(PREF_PROVIDER_KEY, "NULL");
		long time=sharedPreferences.getLong(PREF_TIME_KEY, 0);
		
		if(latitude.equals("NULL") || longitude.equals("NULL")){
			return null;
		}
		LocationInfo locationInfo=new LocationInfo();
		locationInfo.setLatitude(Double.valueOf(latitude));
		locationInfo.setLongitude(Double.valueOf(longitude));
		locationInfo.setAccuracy(accuracy);
		locationInfo.setLocationTime(time);
		locationInfo.setLocationProvider(provider);
		
		return locationInfo;
		
	}
}
