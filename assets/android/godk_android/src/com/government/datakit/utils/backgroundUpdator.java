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
import android.content.SharedPreferences;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Environment;
import android.preference.PreferenceManager;
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

public class backgroundUpdator extends AsyncTask<String, Void, Boolean>{
	
	private ProgressDialog pdialog;
	private Context context;
	private String response;
	private GDKInterface gdkInterface;
	private String DownloadPath;
	private boolean showInternalButton;
	public backgroundUpdator(){
		
	}
	
	public backgroundUpdator(Context context){
		this.context = context;
		this.gdkInterface = gdkInterface;
		this.showInternalButton=true;
		this.DownloadPath="";
		
	}
	
	@Override
	protected Boolean doInBackground(String... params) {
		
		
		InputStream input = null;
        OutputStream output = null;
        HttpURLConnection connection = null;
        response="";
        String AppId=context.getResources().getString(R.string.app_id);
        
        String tempDownloadPath=Environment.getExternalStorageDirectory().getPath()+"/"+AppId+"_"+params[1]+".tmp";
        String DownloadPath=Environment.getExternalStorageDirectory().getPath()+"/"+AppId+"_"+params[1]+".apk";
        
        
        try {
            URL url = new URL(params[0]);
            connection = (HttpURLConnection) url.openConnection();
            connection.connect();

            // expect HTTP 200 OK, so we don't mistakenly save error report
            // instead of the file
            if (connection.getResponseCode() != HttpURLConnection.HTTP_OK) {
                Log.e("Server returned HTTP ",connection.getResponseCode()+ " " + connection.getResponseMessage());
                return false;
            }

            // this will be useful to display download percentage
            // might be -1: server did not report the length
            
            int fileLength = connection.getContentLength();
            File file = new File(DownloadPath);
            if(file.exists())
            {
            	long length = file.length();
            	if(length>=fileLength)
            	{
            		Log.e("Loading from local",DownloadPath);
            		response=DownloadPath;
            		return true;
            	}
            }
 
			
            // download the file
            input = connection.getInputStream();
            
            Log.e("SAVING THE APK ON", tempDownloadPath);
            output = new FileOutputStream(tempDownloadPath);
            
            byte data[] = new byte[4096];
            long total = 0;
            int count;
            while ((count = input.read(data)) != -1) {
            	
            	if(isCancelled())
            	{
            		input.close();
            		return false;
            	}
            	
                total += count;
                Log.e("Downloader =",""+total);
                // publishing the progress....
                if (fileLength > 0) // only if total length is known
                    Log.e("APK DOWNLOAD PROGRESS",""+(int) (total * 100 / fileLength));
                output.write(data, 0, count);
                
            }
        } catch (Exception e) {
            return false;
        } finally {
            try {
                if (output != null)
                {
                	output.close();
                	

                    File from = new File(tempDownloadPath);
                    File to = new File(DownloadPath);
                    if(to.exists())
                    {
                    	to.delete();
                    }
                    from.renameTo(to);
                    
                    SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(this.context);
            		SharedPreferences.Editor editor = preferences.edit();
            		editor.putString("apkupdate",DownloadPath);
            		editor.putLong("apkupdatesize",0);
            		editor.apply();
            		response=DownloadPath;
            		this.DownloadPath=DownloadPath;
                    
                }
                if (input != null)
                    input.close();
            } catch (IOException ignored) {
            }

            if (connection != null)
                connection.disconnect();
        }
        

        
        Log.e("CLOSING TRANSACTION","ALL SAVED");
        return true;
	}

    @Override
    
    protected void onCancelled(Boolean result) {
    	
    	File f=new File(this.DownloadPath);
    	if(f.exists())
    	{
    		f.delete();
    	}
    	super.onCancelled(result);
    	
    }

	@Override
	protected void onPostExecute(Boolean result) {
		super.onPostExecute(result);

		if(result){
			Log.i("update","got result for update");
			
			if(response!="")
			{
				   
//				String fu=this.context.getResources().getString(R.string.ForceUpdate);
				String fu = ((MainScreen) this.context).ReadSetting("ForceUpdate");
				if(fu.equals("YES"))
				{
					Intent i = new Intent();
			        i.setAction(Intent.ACTION_VIEW);
			        i.setFlags(Intent.FLAG_ACTIVITY_NO_HISTORY);
			        i.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK);
			        i.setFlags(Intent.FLAG_ACTIVITY_NO_ANIMATION);
			        i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
			        i.setDataAndType(Uri.fromFile(new File(response)), "application/vnd.android.package-archive" );
			        Log.d("Lofting", "About to install new .apk");
			        this.context.startActivity(i);
				}
				else{
					Intent i = new Intent();
			        i.setAction(Intent.ACTION_VIEW);
			        i.setFlags(Intent.FLAG_ACTIVITY_NO_HISTORY);
			        i.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK);
			        i.setFlags(Intent.FLAG_ACTIVITY_NO_ANIMATION);
			        i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
			        i.setDataAndType(Uri.fromFile(new File(response)), "application/vnd.android.package-archive" );
			        Log.d("Lofting", "About to install new .apk");
			        this.context.startActivity(i);
					Toast.makeText((MainScreen) this.context, "New APK Downloaded to sd card", Toast.LENGTH_SHORT).show();
				}
		        
		     
			}
//			JSONObject json;
//			try {
//				
//				if(response!="Network Error")
//				{
//					
//					json = (JSONObject) new JSONTokener(response).nextValue();
//					String status = (String) json.get("status");
//					Log.i("update","Status = "+status);
//					
//					
//					if(Integer.parseInt(status)==1)
//					{
//						String url = (String) json.get("url");
//						Log.i("update","URL = "+url);
//						showUpdateNotification(url);
//					}
//					else{
//						Log.i("update","URL = NA");
//						NotificationManager notificationManager = (NotificationManager) this.context.getSystemService(this.context.NOTIFICATION_SERVICE);
//						notificationManager.cancel(1);
//						
//					}
//				}
//				
//				
//			} catch (JSONException e) {
//				// TODO Auto-generated catch block
//				e.printStackTrace();
//			}
	        
			 
			
			
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

	@Override
	protected void onPreExecute() {
		super.onPreExecute();
		File f=new File(this.DownloadPath);
    	if(f.exists())
    	{
    		f.delete();
    	}
//		pdialog = new ProgressDialog(this.context);
//		pdialog.setCancelable(false);
//		pdialog.setIcon(R.drawable.info_icon);
//		pdialog.setTitle("Checking Version Status");
//		pdialog.setMessage("Please Wait...");
//		pdialog.show();

	}
}
