package com.government.datakit.utils;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;

import android.app.AlertDialog;
import android.app.AlertDialog.Builder;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.AsyncTask;
import android.preference.PreferenceManager;
import android.telephony.TelephonyManager;
import android.util.Log;

import com.government.datakit.bo.FormsDataInfo;
import com.government.datakit.db.DataBaseAdapter;
import com.government.datakit.interfaces.GDKInterface;
import com.government.datakit.network.HttpWorker;
import com.government.datakit.prefrences.GDKPreferences;
import com.government.datakit.ui.MainScreen;
import com.government.datakit.ui.R;

/**
 * 
 * @author gulfamhassan
 * 
 */

public class GDKUpdateFormsAsyncTask extends AsyncTask<String, Void, Boolean> {

	private ProgressDialog pdialog;
	private Context context;
	private boolean showLoader;
	private GDKInterface gdkInterface;
	private String response;
	private AlertDialog alert;

	public GDKUpdateFormsAsyncTask() {

	}

	public GDKUpdateFormsAsyncTask(Context context, GDKInterface gdkInterface,boolean sl) {
		this.context = context;
		this.showLoader=sl;
		this.gdkInterface = gdkInterface;
	}

	@SuppressWarnings("unused")
	@Override
	protected Boolean doInBackground(String... params) {

//		TelephonyManager telephonyManager = (TelephonyManager) context.getSystemService(Context.TELEPHONY_SERVICE);
//		String IMEI = telephonyManager.getDeviceId();
		String IMEI = Utility.getDeviceUniqueId(context);
		String URL = params[0];
		String UpdateOnlySetting=params[1];
//		if(UpdateOnlySetting!=null)
//		{
//			if(UpdateOnlySetting.equalsIgnoreCase("YES"))
//			{
//				this.showLoader=false;
//			}
//		}
		Log.i("FORM UPDATE URL", URL);
		try {
			List<NameValuePair> listParams = new ArrayList<NameValuePair>();
			listParams.add(new BasicNameValuePair("app_id", context.getString(R.string.app_id)));
			listParams.add(new BasicNameValuePair("imei_no", IMEI));
			response = new HttpWorker().getData(URL, listParams);
			Log.i("FORM RESPONSE", "<>" + response);
			if (response.contains("already_updated")) {
				return false; 
			} else {
				
				

				JSONObject obj = new JSONObject(response);
				JSONArray jArr = obj.getJSONArray("forms");
				JSONArray iconsJArr = obj.getJSONArray("images");
				JSONArray filesJArr = obj.getJSONArray("files");
				JSONArray P = obj.getJSONArray("files");
				JSONObject prefs = obj.getJSONObject("preferences");
				

                SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(this.context);
        		SharedPreferences.Editor editor = preferences.edit();
        		editor.putString("IS_SECURE_APP",prefs.optString("IS_SECURE_APP",""));
        		editor.putString("showHighResOption",prefs.optString("showHighResOption",""));
        		editor.putString("PersistImagesOnDevice",prefs.optString("PersistImagesOnDevice",""));
        		editor.putString("BackgroundUpdate",prefs.optString("BackgroundUpdate",""));
        		editor.putString("ForceUpdate",prefs.optString("ForceUpdate",""));
        		editor.putString("EnableAutoTime",prefs.optString("EnableAutoTime",""));
        		editor.putString("TrackingInterval",prefs.optString("TrackingInterval",""));
        		editor.putString("TrackingDistance",prefs.optString("TrackingDistance",""));
        		editor.putString("TrackingStatus",prefs.optString("TrackingStatus",""));
        		editor.putString("DebugTracking",prefs.optString("DebugTracking",""));
        		editor.putString("hasGeoFencing",prefs.optString("hasGeoFencing",""));
        		editor.putString("geoFence",prefs.optString("geoFence",""));
        		editor.putString("DebugGeoFencing",prefs.optString("DebugGeoFencing",""));
        		
        		DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
        		dbAdapter.open();
        		dbAdapter.SetGeoFence(prefs.optString("geoFence",""));
        		dbAdapter.close();
        		
        		editor.apply();
        		
				if(UpdateOnlySetting.equalsIgnoreCase("YES"))
				{ 
					return false;
				}

				if (jArr != null && jArr.length() > 0) {
					for (int i = 0; i < jArr.length(); i++) {
						obj = jArr.getJSONObject(i);
						String formId = obj.getString("form_id");
						String formName = obj.getString("form_name");
						String fileName = obj.getString("file_name");
						String fileUrl = obj.optString("file_url", "");
						Log.i("FILE NAME", fileName);
						
						Log.e("FORM FILE", fileUrl);
						if(fileUrl.equalsIgnoreCase(""))
						{
							String formDescription = obj.getString("form_description");
							Log.e("FORM FILE", "USING OLD REFRESH!!");
							updateForms(fileName, formDescription);
						}
						else{
							Log.e("FORM FILE", "USING NEW REFRESH!!");
							byte[] fileData = downloadFile(fileName, fileUrl);
							if (fileData == null) {
								continue;
							} else 
								updateFile(fileName, fileData);
						}
						
					}
				}
				else if (GDKPreferences.getPreferences().isAppFirstLaunch(context)) 
					return false;
				if (iconsJArr != null && iconsJArr.length() > 0) {
					for (int j = 0; j < iconsJArr.length(); j++) {
						JSONObject picObj = iconsJArr.getJSONObject(j);
						String fileName = picObj.getString("image_name");
						String fileUrl = picObj.getString("image_url");
						Bitmap bitmap = downloadBitmap(fileName, fileUrl);
						if (bitmap == null) {
							continue;
						} else {
							byte[] imageData = Utility.getSimpleBytes(bitmap);
							updateFile(fileName, imageData);
						}
					}
				}
				else if (GDKPreferences.getPreferences().isAppFirstLaunch(context)) 
					return false;
				if (filesJArr != null && filesJArr.length() > 0) {
					for (int j = 0; j < filesJArr.length(); j++) {
						JSONObject fileObj = filesJArr.getJSONObject(j);
						String fileName = fileObj.getString("file_name");
						String fileUrl = fileObj.getString("file_url");
						byte[] fileData = downloadFile(fileName, fileUrl);
						if (fileData == null) {
							continue;
						} else 
							updateFile(fileName, fileData);
					}
				}
				else if (GDKPreferences.getPreferences().isAppFirstLaunch(context)) 
					return false;
				return true;
			}
		} catch (Exception e) {
			e.printStackTrace();
		}
		return Boolean.FALSE;
	}

	@Override
	protected void onPostExecute(Boolean result) {
		super.onPostExecute(result);
		if(this.showLoader)
		{
			pdialog.dismiss();
			pdialog = null;
		}
		if (result) {
			GDKPreferences.getPreferences().setAppFirstLaunch(context, false);
			this.gdkInterface.formsUpdated();
			if(this.showLoader)
			{
				if (!GDKPreferences.getPreferences().isAppFirstLaunch(context))
				{
//					showAlertDialog("Info", "Updated Successfully",result);
				}
			}
		} else { 
			// something went wrong at server side
			if(this.showLoader)
			{
				if (!GDKPreferences.getPreferences().isAppFirstLaunch(context)) 
					showAlertDialog("Info", "Please refresh again",
							result);
				else{
					GDKPreferences.getPreferences().setAppVersionCode(context, 0); 
					Utility.showErrorDialog(context, context.getString(R.string.internet_error_msg));
				}
			}
		}
	}

	@Override
	protected void onPreExecute() {
		super.onPreExecute();
		
		if(this.showLoader)
		{
			if(pdialog==null)
			{
				pdialog = new ProgressDialog(this.context);
				pdialog.setCancelable(false);
				pdialog.setIcon(R.drawable.info_icon);  
				pdialog.setTitle("Syncing Data");
				pdialog.setMessage("Please Wait...");
				if (!pdialog.isShowing())
				{
					pdialog.show();	
				}
			}
			
		}
	}

	@Override
	protected void onCancelled(){
		if (GDKPreferences.getPreferences().isAppFirstLaunch(context)) 
			GDKPreferences.getPreferences().setAppVersionCode(context, 0);
	}

	private void showAlertDialog(String title, String message,
			final boolean isSuccess) {

		 alert = new AlertDialog.Builder(this.context).setIcon(R.drawable.info_icon)
		.setTitle(title).setMessage(message)
		.setNeutralButton("OK", new DialogInterface.OnClickListener() {

			public void onClick(DialogInterface dialog, int which) {

			}
		}).create();
		
		if(!alert.isShowing())
		{
			alert.show();	
		}
		
		
	}

	private Bitmap downloadBitmap(String fileName, String fileUrl) {
		if (fileUrl.equals("null") || fileUrl.equals("")) {
			return null;
		}
		Bitmap bmImg;
		URL myFileUrl = null;
		try {
			Log.i("IMAGE File URL", "<>" + fileUrl);
			myFileUrl = new URL(fileUrl);
		} catch (MalformedURLException e) {
			e.printStackTrace();
		}
		try {
			HttpURLConnection conn = (HttpURLConnection) myFileUrl
					.openConnection();
			conn.setDoInput(true);
			conn.connect();
			InputStream is = conn.getInputStream();
			bmImg = BitmapFactory.decodeStream(is);
			return bmImg;
		} catch (IOException e) {
			e.printStackTrace();
			return null;
		}
	}	

	private byte[] downloadFile(String fileName, String fileUrl) {
		if (fileUrl.equals("null") || fileUrl.equals("")) {
			return null;
		}
		URL myFileUrl = null;
		try {
			Log.i("IMAGE File URL", "<>" + fileUrl);
			myFileUrl = new URL(fileUrl);
		} catch (MalformedURLException e) {
			e.printStackTrace();
		}
		try {
			HttpURLConnection conn = (HttpURLConnection) myFileUrl
					.openConnection();
			conn.setDoInput(true);
			conn.connect();
			InputStream is = conn.getInputStream();
			byte[] bytes= null;
			try
			{
				ByteArrayOutputStream bos = new ByteArrayOutputStream();
				byte data[] = new byte[2048];
				int count;
				while ((count = is.read(data)) != -1)
				{
					bos.write(data, 0, count);
				}
				bos.flush();
				bos.close();
				is.close();
				bytes = bos.toByteArray();
			}
			catch (IOException e)
			{
				e.printStackTrace();
			}
			return bytes;
		} catch (IOException e) {
			e.printStackTrace();
			return null;
		}
	}
	
	/**
	 * Used to update icons/images from network
	 * 
	 * @param formName
	 * @param formData
	 */
	private void updateFile(String fileName, byte[] imageData) {

		OutputStream out = null;
		String newFileName = this.context.getFilesDir().getPath() + "/"
				+ MainScreen.htmlFilesDirectory + "/" + fileName;
		File file = new File(newFileName);
		boolean deleted = false;
		if (file.exists()) {
			deleted = file.delete();
		}
		Log.e("IMAGE FILE NAME> " + deleted, "<>" + newFileName);
		Log.e("IMAGE FILE PATH> ", "<Writing in Memory> " + newFileName);
//		Log.e("IMAGE FILE PATH> ", "<New File Data> " + new String(imageData));
		try {
			out = new FileOutputStream(newFileName);
			out.write(imageData);
			out.flush();
			out.close();
			out = null;
		} catch (Exception e) {
			Log.i("UPDATE ICONS/IMAGES", e.getMessage());
		}
	}

	/**
	 * Used to update Forms from network
	 * 
	 * @param formName
	 * @param formData
	 */
	private void updateForms(String formName, String formData) {

		OutputStream out = null;
		String newFileName = this.context.getFilesDir().getPath() + "/"
				+ MainScreen.htmlFilesDirectory + "/" + formName;
		Log.e("UPDATE FORM FILE", "<> " + newFileName);
		try {
			out = new FileOutputStream(newFileName);
			out.write(formData.getBytes());
			out.flush();
			out.close();
			out = null;
		} catch (Exception e) {
			Log.i("UPDATE FORM", e.getMessage());
		}
	}
}
