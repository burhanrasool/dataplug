package com.government.datakit.utils;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONObject;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.os.AsyncTask;
import android.os.Handler;
import android.preference.PreferenceManager;
import android.telephony.TelephonyManager;
import android.util.Log;
import android.widget.Toast;

import com.government.datakit.db.DataBaseAdapter;
import com.government.datakit.network.HttpWorker;
import com.government.datakit.ui.EditFormScreen;
import com.government.datakit.ui.MainScreen;
import com.government.datakit.ui.R;
import com.government.datakit.ui.UnsentDataListScreen;

/**
 * 
 * @author gulfamhassan
 *
 */

public class GDKSubmitDataAsyncTask extends AsyncTask<String, Void, Boolean>{

	private ProgressDialog pdialog;
	private Context context;
	private String response;
	private JSONObject jobj;
	private String formData;
	private String location;
	private String location_source;
	private String time_source;
	private String formIconName;
	private String getSNTP;
	private String rowId;
	private long rid;
	private ProgressDialog pd;
	final Handler h = new Handler();
	final int delay = 750; //milliseconds

	public GDKSubmitDataAsyncTask(){

	}

	public GDKSubmitDataAsyncTask(Context context){
		this.context = context;
		 this.pd= new ProgressDialog(context);
	}
	public String getUTCTime(){
        long nowAsPerDeviceTimeZone = 0;
        sntpClient sntpClient = new sntpClient();
        Calendar cal = Calendar.getInstance();
        SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        if (sntpClient.requestTime("pk.pool.ntp.org", 30000)) {
            nowAsPerDeviceTimeZone = sntpClient.getNtpTime();
            String nowSntpTime=formatter.format(cal.getTime());
            	
            
            
            return nowSntpTime;
        }
        else{
        	return "";
        }
       
    }

	@Override
	protected Boolean doInBackground(String... params) {

		this.rid=-1;
//		TelephonyManager telephonyManager = (TelephonyManager)context.getSystemService(Context.TELEPHONY_SERVICE);
//		String IMEI = telephonyManager.getDeviceId(); 
		String IMEI = Utility.getDeviceUniqueId(context);
		String URL = params[0];
		this.formData = params[1];
		this.location = params[2];
		this.location_source = params[5];
		this.time_source = params[6];
		this.formIconName = params[7];
		this.getSNTP=params[8];
		
		try{
			this.rowId=params[9];
			if(this.rowId!=null && !this.rowId.equalsIgnoreCase(""))
			{
				this.rid=Long.parseLong(this.rowId);
			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		
		Log.i("~SUBMIT URL", "<>"+URL);
		Log.i("~FORM DATA", "<>"+params[1]);
		Log.i("~USER LOCATION", "<>"+params[2]);
		Log.i("~Date Time", "<>"+params[3]);
		
		
		
		if(this.getSNTP.equals("YES"))
		{
			params[3]=getUTCTime();
			if(!params[3].equalsIgnoreCase(""))
			{
				if(this.time_source.contains("network"))
				{
					params[6]="SNTP(N)";
				}
				else if(this.time_source.contains("gps"))
				{
					params[6]="SNTP(G)";
				}
				else{
					params[6]="SNTP";
				}
			}
		}
		
		String DATE_TIME=params[3];
		String dtStart = DATE_TIME;  
    	SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");  
    	try {  
    	    Date date = format.parse(dtStart);  
    	    
    	    Calendar cal = Calendar.getInstance();
            cal.setTime(date);
            if(cal.get(Calendar.YEAR)==1970)
            {
            	Date d=new Date();
            	params[6]="Device";
            	DATE_TIME=format.format(d.getTime());
            }
            
//    	    System.out.println(date);  
    	} catch (ParseException e) {  
    	    // TODO Auto-generated catch block  
    	    e.printStackTrace();  
    	    
    	    Date d=new Date();
    	    params[6]="Device";
        	DATE_TIME=format.format(d.getTime());
    	}
    	params[3]=DATE_TIME;
		try{
			List<NameValuePair> listParams = new ArrayList<NameValuePair>();
			listParams.add(new BasicNameValuePair("imei_no", IMEI));
			listParams.add(new BasicNameValuePair("form_data", params[1]));
			listParams.add(new BasicNameValuePair("location", params[2]));
			listParams.add(new BasicNameValuePair("dateTime", params[3]));
			listParams.add(new BasicNameValuePair("version_name", params[4]));
			listParams.add(new BasicNameValuePair("location_source", params[5]));
			listParams.add(new BasicNameValuePair("time_source", params[6]));
			
			response = new HttpWorker().getData(URL, listParams);
			Log.i("FORM RESPONSE", "<>"+response);
			if(response.contains("success")){
				jobj = new JSONObject(response);
				response = jobj.getString("success");
				return true;
			}else{
				jobj = new JSONObject(response);
				response = jobj.getString("error");
				return false;
			}
		}catch(Exception e){
			response = "Network problem, please try later";
			e.printStackTrace();
		}
		
				
		return Boolean.FALSE;
	}

	@Override
	protected void onPostExecute(Boolean result) {
		super.onPostExecute(result);
		h.removeCallbacksAndMessages(null);
		if(pdialog!=null)
		{
		int progress=pdialog.getProgress();
		if(progress<=50)
		{
			pdialog.setProgress(50);
			try {
				Thread.sleep(900);
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
		if(progress<=75)
		{
			pdialog.setProgress(75);
			try {
				Thread.sleep(900);
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
		
		pdialog.setProgress(100);
		try {
			Thread.sleep(900);
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
			
			if(pdialog.isShowing())
			{
				pdialog.dismiss();
			}
			
		}
		
		pdialog = null;
		if(result){
			showAlertDialog("Info", response, result);
			if(this.context instanceof UnsentDataListScreen)				
				((UnsentDataListScreen)this.context).dataSubmitSuccessfully();
			else if(this.context instanceof EditFormScreen)				
				((EditFormScreen)this.context).dataSubmitSuccessfully();
			else if(this.context instanceof MainScreen)				
				((MainScreen)this.context).dataSubmitSuccessfully();
		}else{

			if(this.context instanceof MainScreen){

				String date = null;
				Date currentDate = new Date();
				date = Utility.getCurrentDate(currentDate);

				DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
				dbAdapter.open();
				
				if(this.rid==-1)
				{
					dbAdapter.insertFormsData(this.formData, date, this.location, null, location_source, time_source, CONSTANTS.AUTO_SAVE, this.formIconName);
				}
				else{
					dbAdapter.updateFormsData(rid, this.formData, date, this.location,null, location_source,time_source, 1, this.formIconName);
				}
				dbAdapter.SaveLastActivtiy(this.formData, date, this.location, null, location_source, time_source, CONSTANTS.AUTO_SAVE, this.formIconName);
				dbAdapter.close();
			}
			showAlertDialog("Error", response, result);
		}

	}


	@Override
	protected void onPreExecute() {
		super.onPreExecute();
		//pdialog.setProgressStyle(ProgressDialog.STYLE_SPINNER); 
		try{
        pdialog = new ProgressDialog(this.context);
		pdialog.setCancelable(false);
		pdialog.setIcon(R.drawable.info_icon);
		pdialog.setTitle("Submitting Data");
		pdialog.setProgressStyle(ProgressDialog.STYLE_HORIZONTAL);
		pdialog.setMessage("Please Wait...");
		pdialog.show();
		pdialog.setProgress(0);
		
		if(this.context instanceof MainScreen)				
			((MainScreen)this.context).LockEntry();
		
		h.postDelayed(new Runnable(){
		    public void run(){
		        //do something
		    	pdialog.incrementProgressBy(1);
		    	h.postDelayed(this, delay);
		    }
		}, delay);
		}
		catch(Exception e)
		{
			Log.e("EXCEPTION",""+e);
		}


	}


	 
	private void showAlertDialog(String title, String message, final boolean isSuccess) {

		
		Toast.makeText(this.context, message, Toast.LENGTH_LONG).show();
//		new AlertDialog.Builder(this.context)
//		.setIcon(R.drawable.info_icon)
//		.setTitle(title)
//		.setMessage(message)
//		.setNeutralButton("OK", new DialogInterface.OnClickListener() {
//
//			public void onClick(DialogInterface dialog, int which) {
//				if(context instanceof EditFormScreen)
//					((Activity) context).finish();
//			}
//		}).show();
	}

}
