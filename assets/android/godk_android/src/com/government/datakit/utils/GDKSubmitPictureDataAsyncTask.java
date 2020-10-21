package com.government.datakit.utils;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileOutputStream;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.Enumeration;
import java.util.Hashtable;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.mime.HttpMultipartMode;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.ByteArrayBody;
import org.apache.http.entity.mime.content.ContentBody;
import org.apache.http.entity.mime.content.FileBody;
import org.apache.http.entity.mime.content.StringBody;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.protocol.BasicHttpContext;
import org.apache.http.protocol.HttpContext;
import org.json.JSONObject;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Bitmap.CompressFormat;
import android.media.MediaScannerConnection;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Environment;
import android.os.Handler;
import android.telephony.TelephonyManager;
import android.util.Log;

import com.government.datakit.db.DataBaseAdapter;
import com.government.datakit.ui.EditFormScreen;
import com.government.datakit.ui.MainScreen;
import com.government.datakit.ui.R;
import com.government.datakit.ui.UnsentDataListScreen;

/**
 * This class is used to upload data(Forms and Images) at server.
 * @author gulfamhassan
 *
 */

public class GDKSubmitPictureDataAsyncTask extends AsyncTask<String, Void, Boolean>{


	private ProgressDialog pdialog;
	private Context context;
	private String serverResp;
	private JSONObject jobj;
	private byte[] pictureBytes;
	private String[] picturePaths;

	private String formData;
	private String location;
	private String location_source;
	private String time_source;
	private String formIconName;
	private String getSNTP;
	private String rowId;
	private long rid;
	final Handler h = new Handler();
	final int delay = 750; //milliseconds
	public GDKSubmitPictureDataAsyncTask(){

	}

	public GDKSubmitPictureDataAsyncTask(Context context, String[] imageArray){
		this.context = context;
//		this.pictureBytes = listOfImages;
		this.picturePaths = imageArray;
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
//		Log.i("SUBMIT URL", "<>"+URL);
		//		Log.i("USER LOCATION", "<>"+params[2]);
		//		Log.i("Date Time", "<>"+params[3]);

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
		
		HttpClient httpClient = new DefaultHttpClient();
		HttpContext localContext = new BasicHttpContext();
		HttpPost httpPost = new HttpPost(URL);	 

		try {

			int fileCount = 0; 
			MultipartEntity multiPartentity = new MultipartEntity(HttpMultipartMode.BROWSER_COMPATIBLE);
			Hashtable<String, byte[]> picturesDataTable = Resources.getResources().getMultiplePictureData();
			if(picturesDataTable != null && picturesDataTable.size()>0 && false){
				Enumeration<String> keys = picturesDataTable.keys();
				while(keys.hasMoreElements()) {
					String key = keys.nextElement();
					fileCount++;
					byte [] pictureData = picturesDataTable.get(key);
					//					ByteArrayBody pictureBody  = new ByteArrayBody(pictureData, "image/jpeg", "Picture.jpg");
					ByteArrayBody pictureBody  = new ByteArrayBody(pictureData, "image/jpeg", key);
					multiPartentity.addPart("picture_file_"+fileCount, pictureBody);
					//					multiPartentity.addPart("picture_id", new StringBody(key));
					Log.i("DIRECT PIC BODY>> "+key, pictureData.length+" <<COUNT>> "+fileCount);
				}
			}else if(this.picturePaths != null && this.picturePaths.length>0){

				for(fileCount = 1; fileCount<=picturePaths.length;fileCount++)
				{
					
				
				
				Log.e("PicturePaths",""+picturePaths.toString());
				
//				File sd = Environment.getExternalStorageDirectory();
				File image = new File(picturePaths[fileCount-1]);
//				BitmapFactory.Options bmOptions = new BitmapFactory.Options();
//				Bitmap bitmap = BitmapFactory.decodeFile(image.getAbsolutePath(),bmOptions);
//				bitmap = Bitmap.createScaledBitmap(bitmap,parent.getWidth(),parent.getHeight(),true);
//				imageView.setImageBitmap(bitmap);
				
				if(image.exists())
				{
					ContentBody cbFile = new FileBody(image, "image/jpeg");

//					pictureBytes = Utility.getBytes(bitmap,picturePaths[0]);
//					Log.i("IMAGE PATH> "+pictureBytes.length, "<> "+picturePaths[0]);
					
//					ByteArrayBody pictureBody  = new ByteArrayBody(pictureBytes, "image/jpeg", "Picture.jpg");
					multiPartentity.addPart("picture_file_"+fileCount, cbFile);
//					Log.i("DB PIC BODY>> ", pictureBytes.length+" <<COUNT>> "+fileCount);
				}
				
				}
			}
			

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
	            
	    	} catch (ParseException e) {  
	    	    // TODO Auto-generated catch block  
	    	    e.printStackTrace();  
	    	    
	    	    Date d=new Date();
	    	    params[6]="Device";
	        	DATE_TIME=format.format(d.getTime());
	    	}
	    	params[3]=DATE_TIME;
	    	
			multiPartentity.addPart("imei_no", new StringBody(IMEI));
			multiPartentity.addPart("form_data", new StringBody(params[1]));
			multiPartentity.addPart("location", new StringBody(params[2]));
			multiPartentity.addPart("dateTime", new StringBody(params[3]));
			multiPartentity.addPart("version_name", new StringBody(params[4]));
			multiPartentity.addPart("location_source", new StringBody(params[5]));
			multiPartentity.addPart("time_source", new StringBody(params[6]));
			
			
			
			httpPost.setEntity(multiPartentity);
			Log.e("executing request ", ""+httpPost.getRequestLine());
			HttpResponse response = httpClient.execute(httpPost, localContext);
			StringBuilder sb=null;
			String line = null;
			if(response!=null){
				InputStream in = response.getEntity().getContent();
				BufferedReader reader = new BufferedReader(new InputStreamReader(in));
				sb = new StringBuilder();
				while((line = reader.readLine()) != null){
					sb.append(line);
				}
			}
			serverResp = sb.toString();
			Log.i("SERVER RESP", "<><><>"+serverResp);	

			if(serverResp.contains("success")){
				jobj = new JSONObject(serverResp);
				serverResp = jobj.getString("success");
				
				
				return true;
			}else{
				jobj = new JSONObject(serverResp);
				serverResp = jobj.getString("error");
				return false;
			}
		}catch(Exception e){
			e.printStackTrace();
		}
		return Boolean.FALSE;
	}
 
	@Override
	protected void onPostExecute(Boolean result) {
		super.onPostExecute(result);
		h.removeCallbacksAndMessages(null);
		
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
		
		pdialog.dismiss();
		pdialog = null;
		if (result) {
			showAlertDialog("Info", serverResp, true);
			
//			String PersistImage =  this.context.getResources().getString(R.string.PersistImagesOnDevice);
//			String PersistImage =  ((MainScreen) this.context).ReadSetting("PersistImagesOnDevice");
			String PersistImage="YES";
			
			
			if(this.context instanceof MainScreen)
			{
				PersistImage = ((MainScreen) this.context).ReadSetting("PersistImagesOnDevice");
			}
			else if(this.context instanceof UnsentDataListScreen)
			{
				PersistImage = ((UnsentDataListScreen) this.context).ReadSetting("PersistImagesOnDevice");
			}
			else{
				PersistImage = ((EditFormScreen) this.context).ReadSetting("PersistImagesOnDevice");
			}
			
			 
			Log.e("Should I save the iamge",PersistImage);
			if(PersistImage.equals("NO"))
			{
				Log.e("DOING","IN IF OF REMOVE ");
				for(int i = 0; i<picturePaths.length;i++)
				{
					Log.e("DOING FILE NUMBER"," "+i);
					Log.e("FILE NAME",picturePaths[i]);
					
					File photoFile = new File(picturePaths[i]);
					if (photoFile.exists()) {
					    if (photoFile.getAbsoluteFile().delete()) {
					    	Log.e("file Deleted :","" + picturePaths[i]);
					    } else {
					    	Log.e("file not Deleted :","" + picturePaths[i]);
					    }
					}
//					context.sendBroadcast(new Intent(Intent.ACTION_MEDIA_MOUNTED,Uri.parse("file://" +  Environment.getExternalStorageDirectory())));
					context.sendBroadcast(new Intent(Intent.ACTION_MEDIA_SCANNER_SCAN_FILE, Uri.fromFile(new File(picturePaths[i]).getAbsoluteFile())));
					callBroadCast();

				}
			}

			
			
			if(this.context instanceof UnsentDataListScreen)
			{
				((UnsentDataListScreen)this.context).dataSubmitSuccessfully();
			}
			else if(this.context instanceof EditFormScreen){				
				((EditFormScreen)this.context).dataSubmitSuccessfully();
			}
			else if(this.context instanceof MainScreen)
			{
				((MainScreen)this.context).dataSubmitSuccessfully();
				((MainScreen)this.context).setPictureData();
			
			}
			
		} else if(serverResp!=null && serverResp.length()>0){

			showAlertDialog("Error", serverResp, false);
		}else{

			if(this.context instanceof UnsentDataListScreen || this.context instanceof EditFormScreen){

				showAlertDialog("Error", "No internet, please try later.", false);
			}else if(this.context instanceof MainScreen){
				
				String date = null;
				Date currentDate = new Date();
				date = Utility.getCurrentDate(currentDate);

				DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
				dbAdapter.open();
//				dbAdapter.insertFormsData(this.formData, date, this.location, this.picturePaths, location_source, time_source, CONSTANTS.AUTO_SAVE, this.formIconName);

				if(this.rid==-1)
				{
					dbAdapter.insertFormsData(this.formData, date, this.location, this.picturePaths, location_source, time_source, CONSTANTS.AUTO_SAVE, this.formIconName);
				}
				else{
					dbAdapter.updateFormsData(rid, this.formData, date, this.location,this.picturePaths, location_source,time_source, 1, this.formIconName);
				}
				dbAdapter.close();
				((MainScreen)this.context).setPictureData();
				showAlertDialog("Error", "No internet, data saved locally.", false);
			}
		}
	}
	
	public void callBroadCast() {
        if (Build.VERSION.SDK_INT >= 14) {
            Log.e("-->", " >= 14");
            MediaScannerConnection.scanFile(context, new String[]{Environment.getExternalStorageDirectory().toString()}, null, new MediaScannerConnection.OnScanCompletedListener() {
                /*
                 *   (non-Javadoc)
                 * @see android.media.MediaScannerConnection.OnScanCompletedListener#onScanCompleted(java.lang.String, android.net.Uri)
                 */
                public void onScanCompleted(String path, Uri uri) {
                    Log.e("ExternalStorage", "Scanned " + path + ":");
                    Log.e("ExternalStorage", "-> uri=" + uri);
                }
            });
        } else {
            Log.e("-->", " < 14");
            context.sendBroadcast(new Intent(Intent.ACTION_MEDIA_MOUNTED,
                    Uri.parse("file://" + Environment.getExternalStorageDirectory())));
        }
    }


	@Override
	protected void onPreExecute() {
		super.onPreExecute();

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

	private void showAlertDialog(String title, String message, final boolean isSuccess) {

		new AlertDialog.Builder(this.context)
		.setIcon(R.drawable.info_icon)
		.setTitle(title)
		.setMessage(message)
		.setNeutralButton("OK", new DialogInterface.OnClickListener() {

			public void onClick(DialogInterface dialog, int which) {
				if(context instanceof EditFormScreen)
					((Activity) context).finish();

			}
		}).show();
	}
}
