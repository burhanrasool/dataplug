package com.government.datakit.utils;
import java.io.IOException;
import java.net.InetAddress;
import java.net.UnknownHostException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.TimeZone;

import org.apache.http.impl.cookie.DateUtils;

import com.government.datakit.db.DataBaseAdapter;

import android.content.Context;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.os.AsyncTask;
import android.os.SystemClock;
import android.preference.PreferenceManager;
import android.util.Log;


public class getCurrentNetworkTime extends AsyncTask<String, Void, Boolean> {

	public String now;
	public long millis;
	private Context mContext;
    private Exception exception;
    public String nowSntpTime="";
    
    public getCurrentNetworkTime(Context baseContext) {
		// TODO Auto-generated constructor stub
    	mContext = baseContext;
	}
	public String getUTCTime(){
        long nowAsPerDeviceTimeZone = 0;
        sntpClient sntpClient = new sntpClient();
        Calendar cal = Calendar.getInstance();
        SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        if (sntpClient.requestTime("pk.pool.ntp.org", 30000)) {
            nowAsPerDeviceTimeZone = sntpClient.getNtpTime();
            
            Log.e("!!SNTP RESPONSE",""+nowAsPerDeviceTimeZone);
             
            this.millis=nowAsPerDeviceTimeZone;
            this.now=""+this.millis;
            cal.setTimeInMillis(this.millis);
            Log.e("DATE TIME AFTER ADJUSTMENT",formatter.format(cal.getTime()));
            
            this.nowSntpTime=formatter.format(cal.getTime());
            
    		SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(this.mContext);
    		Editor editor = prefs.edit();
    		
    		long NowMillis=prefs.getLong("nowMillis", 0);
    		editor.putLong("nowMillis", this.millis);
    		
    		editor.commit();
    		 
        	DataBaseAdapter dbAdapter = new DataBaseAdapter(this.mContext);
     		dbAdapter.open();
     		dbAdapter.SetSNTPTS(NowMillis);
     		dbAdapter.SetSystemElapsedTS();
     		dbAdapter.close();
     		
	     		
            
            
            return this.now;
        }
        else{
        	return "";
        }
       
    }

    protected Boolean doInBackground(String... urls) {
//    	 sntpClient sntpclient = new sntpClient();
         
        	 if(this.getUTCTime()!="" && this.now!=null)
        	 {
        		 return true;	 
        	 }
        	 return false;
         
         
    }

    protected void onPostExecute(boolean local_date) {
        if(!local_date) {
            Log.e("Check ", "dates not equal" + local_date);
        }
    }
}