package com.government.datakit.utils;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.TimeZone;

import org.apache.http.HttpResponse;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import com.government.datakit.bo.trackingPoint;
import com.government.datakit.db.DataBaseAdapter;
import com.government.datakit.ui.MainScreen;
import com.government.datakit.ui.R;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Handler;
import android.preference.PreferenceManager;
import android.util.Log;
import android.widget.Toast;

public class ConnectivityReceiver extends BroadcastReceiver {
	private Context mcontext;
    @Override
    public void onReceive(Context context, Intent intent) {
    	this.mcontext=context;
        Log.d(ConnectivityReceiver.class.getSimpleName(), "action: "
                + intent.getAction());
        
        Boolean IS_NETWORK_AVAILABLE = haveNetworkConnection(context);
        Log.i("Internet status",""+IS_NETWORK_AVAILABLE);
        
        if(IS_NETWORK_AVAILABLE)
        {
        	
        	
        	
        	getUTCTime();

        	SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(context);
        	String RouteId=prefs.getString("routeId", "");
        	if(RouteId.equals(""))
        	{		//meaning tracking is not in progress and there are some points in db, that need to be sent.
        		sendPendingTrackingData();
        	}
        	
//        	M=null;
//        	Toast.makeText(context, "Connected To Internet",Toast.LENGTH_LONG).show();
        	
        }
    }
    
public void sendPendingTrackingData(){
        
        DataBaseAdapter dbAdapter = new DataBaseAdapter(this.mcontext);
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
			
			if(Utility.isInternetAvailable(this.mcontext))
			{
				
				submitTrackerPoint submitPoint = new submitTrackerPoint(this.mcontext);
				submitPoint.execute(this.mcontext.getString(R.string.TRACKING_URLBulk),jsArray.toString());
				
			}
			
			dbAdapter.close();
			
			
		}
	}

    
    public void getUTCTime(){

		getCurrentNetworkTime current = new getCurrentNetworkTime(this.mcontext);
		current.execute();
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

}
