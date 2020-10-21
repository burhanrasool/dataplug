package com.government.datakit.prefrences;

import android.content.Context;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;

/**
 * This class is used to store and retrieve data from preferences.
 * @author gulfamhassan
 *
 */

public class GDKPreferences {

	private static GDKPreferences userInfo;

	public static GDKPreferences getPreferences() {
		if (userInfo == null) {
			userInfo = new GDKPreferences();
		}
		return userInfo;
	}

	public boolean isAppFirstLaunch(Context context) {
		
		SharedPreferences manager = PreferenceManager.getDefaultSharedPreferences(context);
		return manager.getBoolean("app_launch",true);
	}

	public void setAppFirstLaunch(Context context, boolean isLaunch) {
		
		PreferenceManager.getDefaultSharedPreferences(context).edit().putBoolean("app_launch", isLaunch).commit();
	}
	
	
	public int getAppVersionCode(Context context) {
		
		SharedPreferences manager = PreferenceManager.getDefaultSharedPreferences(context);
		return manager.getInt("app_vcode",0);
	}

	public void setAppVersionCode(Context context, int code) {
		
		PreferenceManager.getDefaultSharedPreferences(context).edit().putInt("app_vcode", code).commit();
	}
	
}
