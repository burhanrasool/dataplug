package com.government.datakit.utils;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileOutputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.mime.HttpMultipartMode;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.ContentBody;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.entity.mime.content.StringBody;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.protocol.BasicHttpContext;
import org.apache.http.protocol.HttpContext;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.os.AsyncTask;
import android.os.Environment;
import android.os.Handler;
import android.preference.PreferenceManager;
import android.telephony.TelephonyManager;
import android.util.Log;
import android.widget.Toast;

import com.government.datakit.bo.trackingPoint;
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

public class submitTrackerPoint extends AsyncTask<String, Void, Boolean>{

	private ProgressDialog pdialog;
	private Context context;
	private String response;
	private JSONObject jobj;
	private String location;
	private String lat;
	private String lng;
	private String accuracy;
	private String altitude;
	private String speed;
	private String gpstime;
	private String devicets;
	private String routeId;
	private String records;
	private JSONArray jsArray;
	private String location_source;
	private String time_source;
	private String formIconName;
	private String getSNTP;
	private ProgressDialog pd;
	final Handler h = new Handler();
	final int delay = 750; //milliseconds

	public submitTrackerPoint(){

	}

	public submitTrackerPoint(Context context){
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
	private void writeToFile(File filepath, String data,Context context) {
	    try {
	    	FileOutputStream fOut = new FileOutputStream(filepath);
	        OutputStreamWriter outputStreamWriter = new OutputStreamWriter(fOut);
	        outputStreamWriter.write(data);
	        outputStreamWriter.close();
	    }
	    catch (IOException e) {
	        Log.e("Exception", "File write failed: " + e.toString());
	    } 
	}
	
	
	@Override
	protected Boolean doInBackground(String... params) {

		  DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
			dbAdapter.open();
			ArrayList<trackingPoint> trackingarrayList = dbAdapter.readTrackingData();
			
			ArrayList<Integer> sentids = new ArrayList<Integer>();
			if(trackingarrayList!=null && trackingarrayList.size()>0){
				
				Log.e("TP","You have "+trackingarrayList.size()+" unsent Tracking Points");
				this.jsArray= new JSONArray();
				
				
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
//				
//				if(Utility.isInternetAvailable(this.context))
//				{
//					
//					submitTrackerPoint submitPoint = new submitTrackerPoint(this.context);
//					submitPoint.execute(this.context.getString(R.string.TRACKING_URLBulk),jsArray.toString());
//					
//					
//				}
				  
				dbAdapter.close();
				
			}
//		TelephonyManager telephonyManager = (TelephonyManager)context.getSystemService(Context.TELEPHONY_SERVICE);
//		String IMEI = telephonyManager.getDeviceId(); 
		 
		String IMEI=Utility.getDeviceUniqueId(context);
		MultipartEntity multiPartentity = new MultipartEntity(HttpMultipartMode.BROWSER_COMPATIBLE);
		
		String URL = params[0];
		 
		HttpClient httpClient = new DefaultHttpClient();
		HttpContext localContext = new BasicHttpContext();
		HttpPost httpPost = new HttpPost(URL);	 
		if(URL.contains("bulk"))
		{
//			this.records = params[1];
//			this.records=this.jsArray.toString();
			
			try{
				List<NameValuePair> listParams = new ArrayList<NameValuePair>();
				listParams.add(new BasicNameValuePair("imei_no", IMEI));
				listParams.add(new BasicNameValuePair("appId", context.getString(R.string.app_id)));
				multiPartentity.addPart("imei_no", new StringBody(IMEI));
				multiPartentity.addPart("appId", new StringBody(context.getString(R.string.app_id)));
				
//				JSONArray ar=new JSONArray(records);
				JSONArray ar=this.jsArray;
				trackingPoint tp=new trackingPoint();
				if(ar.length()>0)
				{
					
					JSONObject j=ar.getJSONObject(ar.length()-1);
					String rid=j.getString("routeId");
					String gt=j.getString("gpsTime");
					String dt=j.getString("distance");
					String dtGeo=j.getString("distanceGeo");
					listParams.add(new BasicNameValuePair("routeId", rid));
					listParams.add(new BasicNameValuePair("gpsTime", gt));
					listParams.add(new BasicNameValuePair("distanceCovered", dt));
					listParams.add(new BasicNameValuePair("distanceCoveredGeo", dtGeo));
					
					
					
					multiPartentity.addPart("routeId", new StringBody(rid));
					multiPartentity.addPart("gpsTime", new StringBody(gt));
					multiPartentity.addPart("distanceCovered", new StringBody(dt));
					multiPartentity.addPart("distanceCoveredGeo", new StringBody(dtGeo));
//					multiPartentity.addPart("records", new StringBody(ar.toString()));
					
					Log.e("asdcdsc", "ascasdcadsccd");
					
				}
				
				
				File sdCard = Environment.getExternalStorageDirectory();
	            File dir = new File(sdCard.getAbsolutePath());
	            dir.mkdirs();
	            Calendar cal = Calendar.getInstance();
	            SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
	            String fname=formatter.format(cal.getTime());
	            File file = new File(dir, "tracking_"+fname+".txt");
	            
	            
	            writeToFile(file, ar.toString(),this.context);

	            
	            ContentBody cbFile = new FileBody(file, "text/plain");
	            multiPartentity.addPart("trackingFile", cbFile);
	            
	            
	            
//	            
				
//				FileOutputStream fileOutputStream = null;
//		        try {
//		            File sdCard = Environment.getExternalStorageDirectory();
//		            File dir = new File(sdCard.getAbsolutePath());
//		            dir.mkdirs();
//		            Calendar cal = Calendar.getInstance();
//		            SimpleDateFormat formatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
//		            String fname=formatter.format(cal.getTime());
//		            File file = new File(dir, "tracking_"+fname+".txt");
//		            file.getAbsolutePath();
//		            
//		            
//		            
//		            fileOutputStream = new FileOutputStream(file);
////		            fileOutputStream.write(this.jsArray.toString());
//		            
//		            
//		            
////		            BufferedWriter output = new BufferedWriter(new FileWriter(file));
////		            output.write("asadasd");
////	                output.write(this.jsArray.toString());
////	                output.close();
//		            
//	                
//	                writeToFile(file.getAbsolutePath(), this.jsArray.toString());
//	                
//	                
//	                ContentBody cbFile = new FileBody(file, "text/plain");
//		            multiPartentity.addPart("trackingFile", cbFile);
//			        
//		        } catch (Exception exception) {
//
//		        } finally {
//		            if (fileOutputStream != null) {
//		                try {
//		                    fileOutputStream.close();
//		                } catch (Exception e) {
//
//		                }
//		            }
//		        }
		        
		        
//				listParams.add(new BasicNameValuePair("records", records));
				httpPost.setEntity(multiPartentity);
				Log.e("sending tracking points executing request ", ""+httpPost.getRequestLine());
				HttpResponse resp = httpClient.execute(httpPost, localContext);
//				response = new HttpWorker().getData(URL, listParams);
				StringBuilder sb=null;
				String line = null;
				if(resp!=null){
					InputStream in = resp.getEntity().getContent();
					BufferedReader reader = new BufferedReader(new InputStreamReader(in));
					sb = new StringBuilder();
					while((line = reader.readLine()) != null){
						sb.append(line);
					}
				}
				response = sb.toString();
				Log.i("SERVER RESP", "<><><>"+resp);	

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
			 
		}
		else{
			
		
		this.location = params[1];
		this.lat = params[2];
		this.lng=params[3];
		this.accuracy=params[4];
		this.altitude=params[5];
		this.speed=params[6];
		this.gpstime=params[7];
		this.devicets=params[8];
		this.routeId=params[9];
		
		Log.i("~SUBMIT URL", "<>"+URL);
		Log.i("~FORM DATA", "<>"+params[1]);
		Log.i("~USER LOCATION", "<>"+params[2]);
		Log.i("~Date Time", "<>"+params[3]);
		 
		String DATE_TIME=params[7];
		String dtStart = DATE_TIME;  
    	SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");  
    	try {  
    	    Date date = format.parse(dtStart);  
    	    
    	    Calendar cal = Calendar.getInstance();
            cal.setTime(date);
            if(cal.get(Calendar.YEAR)==1970)
            {
            	Date d=new Date();
            	DATE_TIME=format.format(d.getTime());
            }
            
//    	    System.out.println(date);  
    	} catch (ParseException e) {  
    	    // TODO Auto-generated catch block  
    	    e.printStackTrace();  
    	    
    	    Date d=new Date();
        	DATE_TIME=format.format(d.getTime());
    	}
    	params[7]=DATE_TIME;
		try{
			
 
		        
		        
			List<NameValuePair> listParams = new ArrayList<NameValuePair>();
			listParams.add(new BasicNameValuePair("imei_no", IMEI));
			listParams.add(new BasicNameValuePair("appId", context.getString(R.string.app_id)));
			
			listParams.add(new BasicNameValuePair("location", this.location));
			listParams.add(new BasicNameValuePair("lat", this.lat));
			listParams.add(new BasicNameValuePair("lng", this.lng));
			listParams.add(new BasicNameValuePair("accuracy", this.accuracy));
			listParams.add(new BasicNameValuePair("altitude", this.altitude));
			listParams.add(new BasicNameValuePair("speed", this.speed));
			listParams.add(new BasicNameValuePair("gpsTime", this.gpstime));
			listParams.add(new BasicNameValuePair("deviceTS", this.devicets));
			listParams.add(new BasicNameValuePair("routeId", this.routeId));
			
			
			
			
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
		
		}	
		return Boolean.FALSE;
	}

	@Override
	protected void onPostExecute(Boolean result) {
		super.onPostExecute(result);
		

//		h.removeCallbacksAndMessages(null);
//		
//		int progress=pdialog.getProgress();
//		if(progress<=50)
//		{
//			pdialog.setProgress(50);
//			try {
//				Thread.sleep(900);
//			} catch (InterruptedException e) {
//				// TODO Auto-generated catch block
//				e.printStackTrace();
//			}
//		}
//		if(progress<=75)
//		{
//			pdialog.setProgress(75);
//			try {
//				Thread.sleep(900);
//			} catch (InterruptedException e) {
//				// TODO Auto-generated catch block
//				e.printStackTrace();
//			}
//		}
//		
//		pdialog.setProgress(100);
//		try {
//			Thread.sleep(900);
//		} catch (InterruptedException e) {
//			// TODO Auto-generated catch block
//			e.printStackTrace();
//		}
//		
//		pdialog.dismiss();
//		pdialog = null;
		if(result){
			showAlertDialog("Info", response, result);
			if(this.jsArray!=null)
			{
				JSONArray ar;
				try {
//					ar = new JSONArray(this.records);
					ar=this.jsArray;
					trackingPoint tp=new trackingPoint();
					if(ar.length()>0)
					{
						
						DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
						dbAdapter.open();
						for(int i=0; i<ar.length();i++)
						{
							JSONObject record=ar.getJSONObject(i);
							int id=Integer.parseInt(record.getString("id"));
							dbAdapter.deleteTrackingPointItem(id);
						}
						dbAdapter.close();
						
						JSONObject j=ar.getJSONObject(0);
						
					}
					
					
					
				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				
				
				
			}  
			
		}else{

//			if(this.context instanceof MainScreen){
//
//				String date = null;
//				Date currentDate = new Date();
//				date = Utility.getCurrentDate(currentDate);
//
//				DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
//				dbAdapter.open();
//				dbAdapter.insertFormsData(this.formData, date, this.location, null, location_source, time_source, CONSTANTS.AUTO_SAVE, this.formIconName);
//				dbAdapter.SaveLastActivtiy(this.formData, date, this.location, null, location_source, time_source, CONSTANTS.AUTO_SAVE, this.formIconName);
//				dbAdapter.close();
//			}
			showAlertDialog("Error", response, result);
		}
		
		try{
			((MainScreen) context).processingTrackerPoints=false;
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		

	}


	@Override
	protected void onPreExecute() {
		super.onPreExecute();
		try{
		((MainScreen) context).processingTrackerPoints=true;
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
			
		//pdialog.setProgressStyle(ProgressDialog.STYLE_SPINNER); 
//		
//        pdialog = new ProgressDialog(this.context);
//		pdialog.setCancelable(false);
//		pdialog.setIcon(R.drawable.info_icon);
//		pdialog.setTitle("Submitting Data");
//		pdialog.setProgressStyle(ProgressDialog.STYLE_HORIZONTAL);
//		pdialog.setMessage("Please Wait...");
//		pdialog.show();
//		pdialog.setProgress(0);
//		
//
//		h.postDelayed(new Runnable(){
//		    public void run(){
//		        //do something
//		    	pdialog.incrementProgressBy(1);
//		    	h.postDelayed(this, delay);
//		    }
//		}, delay);
//		


	}


	 
	private void showAlertDialog(String title, String message, final boolean isSuccess) {

		Log.e("Server Response on Tracker Event",message);
//		Toast.makeText(this.context, message, Toast.LENGTH_LONG).show();
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
