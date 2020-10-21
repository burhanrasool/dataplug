package com.government.datakit.utils;
import com.government.datakit.db.DataBaseAdapter;
import com.government.datakit.ui.MainScreen;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;
import android.util.Log;
import android.widget.Toast;

public class BootReceiver extends BroadcastReceiver
{

@Override
public void onReceive(Context context, Intent intent) {
    // TODO Auto-generated method stub

		Toast.makeText(context, "Booting Completed, GEODK", Toast.LENGTH_LONG).show(); 
		
		SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(context);
		String routeId=prefs.getString("routeId", "");
		if(routeId.equalsIgnoreCase(""))
		{
			
		}
		else{
			Toast.makeText(context, "resume tracker service", Toast.LENGTH_LONG).show();
			context.startService(new Intent(context, trackerService.class));
		}
		
		
		DataBaseAdapter dbAdapter = new DataBaseAdapter(context);
		dbAdapter.open();
		dbAdapter.SetBootTS();
		dbAdapter.close();
	
	
}

}