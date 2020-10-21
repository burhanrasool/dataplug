package com.government.datakit.utils;

import com.government.datakit.db.DataBaseAdapter;
import com.government.datakit.ui.MainScreen;
import com.government.datakit.ui.R;

import android.app.Service;
import android.content.Context;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Handler;
import android.os.IBinder;
import android.util.Log;
import android.widget.Toast;

public class bgService extends Service {
   
   @Override
   public IBinder onBind(Intent arg0) {
      return null;
   }

   @Override
   public int onStartCommand(Intent intent, int flags, int startId) {
      
	   
	   Boolean IS_NETWORK_AVAILABLE = haveNetworkConnection(this.getBaseContext());
       Log.i("internet status",""+IS_NETWORK_AVAILABLE);
       
       if(IS_NETWORK_AVAILABLE)
       {
    	getCurrentNetworkTime current = new getCurrentNetworkTime(this.getBaseContext());
		current.execute();
		
//		((MainScreen) this.getBaseContext()).checkUpdate();
       	Toast.makeText(getBaseContext(), "Service Connected To Internet",Toast.LENGTH_LONG).show();
//
//       	GDKCheckVersionAsyncTask checkVersion = new GDKCheckVersionAsyncTask(this.getBaseContext(),"");
//		checkVersion.execute(getString(R.string.CHECK_UPDATE_VERSION_URL), getString(R.string.app_id), versionCode+"","FROMMAIN");

       	
       }
      Toast.makeText(this, "Service Started", Toast.LENGTH_LONG).show();
      
      return START_STICKY;
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

   
   @Override
   public void onDestroy() {
      super.onDestroy();
      Toast.makeText(this, "Service Destroyed", Toast.LENGTH_LONG).show();
   }
}
