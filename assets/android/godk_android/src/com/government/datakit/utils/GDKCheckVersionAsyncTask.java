package com.government.datakit.utils;

import java.io.BufferedInputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLConnection;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONException;
import org.json.JSONObject;
import org.json.JSONTokener;

import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Environment;
import android.telephony.TelephonyManager;
import android.util.Log;
import android.widget.Toast;

import com.government.datakit.interfaces.GDKInterface;
import com.government.datakit.network.HttpWorker;
import com.government.datakit.ui.MainScreen;
import com.government.datakit.ui.R;

/**
 * 
 * @author gulfamhassan
 *
 */

public class GDKCheckVersionAsyncTask extends AsyncTask<String, Void, Boolean>{
	
	private ProgressDialog pdialog;
	private Context context;
	private String response;
	private GDKInterface gdkInterface;
	private boolean showInternalButton;
	public GDKCheckVersionAsyncTask(){
		
	}
	
	public GDKCheckVersionAsyncTask(Context context, GDKInterface gdkInterface){
		this.context = context;
		this.gdkInterface = gdkInterface;
		this.showInternalButton=true;
	}
	
	@Override
	protected Boolean doInBackground(String... params) {
		
		
//		TelephonyManager telephonyManager = (TelephonyManager)context.getSystemService(Context.TELEPHONY_SERVICE);
//		String IMEI = telephonyManager.getDeviceId(); 
		String IMEI = Utility.getDeviceUniqueId(context);
		String URL = params[0];
		Log.i("update", "<>"+URL);

		try{
			List<NameValuePair> listParams = new ArrayList<NameValuePair>();
			listParams.add(new BasicNameValuePair("imei_no", IMEI));
			listParams.add(new BasicNameValuePair("app_id", params[1]));
			listParams.add(new BasicNameValuePair("version_code", params[2]));
			
			
			if(params.length==4)
			{
				this.showInternalButton=false;
			}
			response = new HttpWorker().getData(URL, listParams);
			Log.i("update", "<>"+response);
			
			
			return true;
		}catch(Exception e){
			response = "Network problem, could not check version status";
			e.printStackTrace();
		}
		return Boolean.FALSE;
	}

	@Override
	protected void onPostExecute(Boolean result) {
		super.onPostExecute(result);

		if(result){
			Log.i("update","got result for update");
			JSONObject json;
			try {
				
				if(response!="Network Error")
				{
					
					json = (JSONObject) new JSONTokener(response).nextValue();
					String status = (String) json.get("status");
					Log.i("update","Status = "+status);
					
					
					if(Integer.parseInt(status)==1)
					{
						String url = (String) json.get("url");
						String vc=(String)json.get("version");
						Log.i("update","URL = "+url);
						
						
//						String backgroundUpdate=context.getResources().getString(R.string.BackgroundUpdate);
						String backgroundUpdate = ((MainScreen) this.context).ReadSetting("BackgroundUpdate");
						if(backgroundUpdate.equals("YES"))
						{
							backgroundUpdator bu = new backgroundUpdator(this.context);
							bu.execute(url,vc);
							this.showInternalButton=false;
						}
						else{
							showUpdateNotification(url);
						}
						
//						checkVersion.execute(getString(R.string.CHECK_UPDATE_VERSION_URL), getString(R.string.app_id), versionCode+"","FROMMAIN");
		 
					}
					else{
						Log.i("update","URL = NA");
						NotificationManager notificationManager = (NotificationManager) this.context.getSystemService(this.context.NOTIFICATION_SERVICE);
						notificationManager.cancel(1);
						
					}
				}
				
				
			} catch (Exception e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
	        
			
			if(this.showInternalButton)
			{
				this.gdkInterface.updateAvailable(response);	
			}
			
			
			
		}else{
			Log.i("update","got no result for update");
			Toast.makeText(this.context, response, Toast.LENGTH_SHORT).show();
		}
		
	}

	public void showUpdateNotification(String urls)
	{
		Log.i("updateMain","Got in update notifcation");
		Context context=this.context;
//		Context context=mcontext;
		 	    
	    
		Intent intent = new Intent(android.content.Intent.ACTION_VIEW, Uri.parse(urls));
		 
	     
		PendingIntent pIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT);
		
		
		Notification noti = new Notification.Builder(context)
		.setTicker(context.getResources().getString(R.string.app_name))
		.setContentTitle(context.getResources().getString(R.string.app_name))
		.setContentText("Update is available, Tap to update")
		.setSmallIcon(R.drawable.icon)
		.setSound(RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION))
		.setContentIntent(pIntent).getNotification();
		
//		noti.flags=Notification.FLAG_AUTO_CANCEL;
		noti.flags=Notification.FLAG_ONGOING_EVENT | Notification.FLAG_NO_CLEAR;

		NotificationManager notificationManager = (NotificationManager) context.getSystemService(context.NOTIFICATION_SERVICE);
		notificationManager.notify(1, noti); 
		
		
	}

	private String updateAPK(String u)
	{
		InputStream input = null;
        OutputStream output = null;
        HttpURLConnection connection = null;
        try {
            URL url = new URL(u);
            connection = (HttpURLConnection) url.openConnection();
            connection.connect();

            // expect HTTP 200 OK, so we don't mistakenly save error report
            // instead of the file
            if (connection.getResponseCode() != HttpURLConnection.HTTP_OK) {
                return "Server returned HTTP " + connection.getResponseCode()
                        + " " + connection.getResponseMessage();
            }

            // this will be useful to display download percentage
            // might be -1: server did not report the length
            int fileLength = connection.getContentLength();
            
            String AppId=context.getResources().getString(R.string.app_id);
            
            // download the file
            input = connection.getInputStream();
            output = new FileOutputStream("/sdcard/"+AppId+".apk");

            byte data[] = new byte[4096];
            long total = 0;
            int count;
            while ((count = input.read(data)) != -1) {
                // allow canceling with back button
                if (isCancelled()) {
                    input.close();
                    return null;
                }
                total += count;
                // publishing the progress....
                if (fileLength > 0) // only if total length is known
                    Log.e("APK DOWNLOAD PROGRESS",""+(int) (total * 100 / fileLength));
                output.write(data, 0, count);
            }
        } catch (Exception e) {
            return e.toString();
        } finally {
            try {
                if (output != null)
                    output.close();
                if (input != null)
                    input.close();
            } catch (IOException ignored) {
            }

            if (connection != null)
                connection.disconnect();
        }
        return null;
	}

	@Override
	protected void onPreExecute() {
		super.onPreExecute();
		
//		pdialog = new ProgressDialog(this.context);
//		pdialog.setCancelable(false);
//		pdialog.setIcon(R.drawable.info_icon);
//		pdialog.setTitle("Checking Version Status");
//		pdialog.setMessage("Please Wait...");
//		pdialog.show();

	}
}
