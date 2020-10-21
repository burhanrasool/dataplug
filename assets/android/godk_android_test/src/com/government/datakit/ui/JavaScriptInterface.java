package com.government.datakit.ui;

import java.io.File;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.concurrent.TimeUnit;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.Environment;
import android.os.SystemClock;
import android.preference.PreferenceManager;
import android.provider.MediaStore;
import android.util.Log;
import android.webkit.JavascriptInterface;
import android.widget.Toast;

import com.government.datakit.bo.FormsDataInfo;
import com.government.datakit.bo.LocationInfo;
import com.government.datakit.db.DataBaseAdapter;
import com.government.datakit.uiadapters.UnsentDataAdapter;
import com.government.datakit.utils.CONSTANTS;
import com.government.datakit.utils.GDKSubmitDataAsyncTask;
import com.government.datakit.utils.GDKSubmitPictureDataAsyncTask;
import com.government.datakit.utils.Utility;
import com.government.datakit.utils.getCurrentNetworkTime;
import com.manateeworks.CNICScanner.ActivityCapture;

/**
 * This class is to used capture all the events which passed by the HTML using
 * Java Script.
 * 
 * @author gulfamhassan
 */

public class JavaScriptInterface {

	private Activity context;
	private LocationInfo location = null;
	private String versionName = null;
	private UnsentDataAdapter adapter;
	private ArrayList<FormsDataInfo> arrayList;

	JavaScriptInterface(Activity context) {
		this.context = context;
	}

	/**
	 * Used to show toast message
	 * 
	 * @param toast
	 */
	@JavascriptInterface
	public void showToastMessage(String toast) {
		Toast.makeText(context, toast, Toast.LENGTH_SHORT).show();
	}

	/**
	 * Used to show alert dialog
	 * 
	 * @param message
	 */
	@JavascriptInterface
	public void showAlertDialog(String message) {
 
		AlertDialog.Builder myDialog = new AlertDialog.Builder(context);
		myDialog.setTitle(this.context.getString(R.string.info));
		myDialog.setMessage(message);
		myDialog.setPositiveButton(this.context.getString(R.string.ok), null);
		myDialog.show();
	}

	/**
	 * Used to start unsent data activity and populate the list with only unsent cases
	 */
	@JavascriptInterface
	public void showOnlyUnsentAndNotSaved() {
		
		Log.e("AHOOO","sirf unsent aye gee");
		Intent intent = new Intent(this.context, UnsentDataListScreen.class);
		intent.putExtra("show", "onlyunsent");
		this.context.startActivity(intent);
	}
	

	/**
	 * Used to start unsent data activity and populate the list with only saved cases
	 */
	@JavascriptInterface
	public void showOnlySavedAndNotUnsent() {
		
		Log.e("AHOOO","sirf sent aaye gee");
		Intent intent = new Intent(this.context, UnsentDataListScreen.class);
		intent.putExtra("show", "onlyedit");
		this.context.startActivity(intent);
	}


	/**
	 * Used to start unsent data activity and populate the list with both saved and editables
	 */
	@JavascriptInterface
	public void onUnsentDataClick() {
		Intent intent = new Intent(this.context, UnsentDataListScreen.class);
		this.context.startActivity(intent);
	}

	
	/**
	 * Used to refresh forms
	 */
	@JavascriptInterface
	public void onRefreshDataClick(boolean isForPlayStore) {
		Log.e("Refresh Clicked", "Refreshing");
		((MainScreen) this.context).updateForms(isForPlayStore);
	}


	/**
	 * Used to show alert on back button in webview
	 */
	@JavascriptInterface
	public void onBackPressShowAlert(boolean show) {
		
		((MainScreen) this.context).setShowBackAlert(show);
	}

	
	/**
	 * Used to refresh forms
	 */
	@JavascriptInterface
	public String GetTrackingStatus() {

		return ((MainScreen) this.context).GetTrackingStatus();
	}
	

	/**
	 * Used to capture location
	 */
	@JavascriptInterface
	public void onTakeLocationCLick() {
		location = ((MainScreen) this.context).getLocationHandler().getCurrentBestLocationInfo();
		if (location != null && location.getLocation().length() > 0) {
			Toast.makeText(this.context, "Location has been taken",
					Toast.LENGTH_SHORT).show();
		} else {
			((MainScreen) this.context).getLocationHandler().getCurrentBestLocationInfo();
			showAlertDialog("Location not found, please try again later");
		}
	}

	@JavascriptInterface
	public void takeTime(String timeId) {

		LocationInfo L=((MainScreen) this.context).getLocationHandler().getCurrentBestLocationInfo();
		((MainScreen) this.context).pushTimeToForm(timeId,L);
	}


	@JavascriptInterface
	public void startTracking() {
		((MainScreen) this.context).beginService();
	}

	@JavascriptInterface
	public void stopTracking() {
		((MainScreen) this.context).stopService();
	}

	

	/**
	 * Used to launch camera and take picture
	 */
	@JavascriptInterface
	public void takePicture(final String pictureId) {

		
//		String showHighResOption = this.context.getResources().getString(R.string.showHighResOption);
		String showHighResOption = ((MainScreen) this.context).ReadSetting("showHighResOption");
		String PersistImage = this.context.getResources().getString(R.string.PersistImagesOnDevice);
		
		if(showHighResOption.equals("YES"))
		{	
			((MainScreen) this.context).setPictureResolution("HIGH");
			AlertDialog.Builder builder = new AlertDialog.Builder(context);
			builder.setMessage("Select Picture Quality")
			   .setCancelable(true)
			   .setPositiveButton("High Resolution", new DialogInterface.OnClickListener() {
			       public void onClick(DialogInterface dialog, int id) {
			    	   ((MainScreen) context).setPictureResolution("HIGH");
			    	   ((MainScreen) context).setPictureId(pictureId);
			    	   
			   		Intent cameraIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
			   		cameraIntent.putExtra(MediaStore.EXTRA_OUTPUT, Uri.fromFile(((MainScreen) context).getTempFile(context)) );
			   		
			   		context.startActivityForResult(cameraIntent, MainScreen.CAMERA_PIC_REQUEST);
			   		
			       }
			   })
			   .setNeutralButton("Low Resolution", new DialogInterface.OnClickListener() {
			       public void onClick(DialogInterface dialog, int id) {
//			            dialog.cancel();
			    	   ((MainScreen) context).setPictureResolution("LOW");
			    	   ((MainScreen) context).setPictureId(pictureId);
			   		Intent cameraIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
			   		
			   		context.startActivityForResult(cameraIntent, MainScreen.CAMERA_PIC_REQUEST);
			       }
			   })
			   .setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
				
				@Override
				public void onClick(DialogInterface dialog, int which) {
					// TODO Auto-generated method stub
					dialog.cancel();
					
				}
			})
			   ;
			AlertDialog alert = builder.create();
			alert.show();
		}
		else{
			
			((MainScreen) this.context).setPictureResolution("LOW");
			((MainScreen) this.context).setPictureId(pictureId);
			
			Intent cameraIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
			
			this.context.startActivityForResult(cameraIntent, MainScreen.CAMERA_PIC_REQUEST);
		}
		
	}

	/**
	 * Used to move back in history
	 */
	@JavascriptInterface
	public void onGoBackClick() {
		
		context.runOnUiThread(new Runnable() {
			@Override
			public void run() { 
				try{
					
					((MainScreen) context).goBackClick();
					
				}
				catch(Exception e)
				{
					Log.e("EXCEPTION",""+e);
//					finish();
					try {
						Thread.sleep(1900);
					} catch (InterruptedException ee) {
						// TODO Auto-generated catch block
						ee.printStackTrace();
					}
//					context.finish();
				}
				
			}
		});
		
		
	}

	/**
	 * Used to move back in history
	 */
	@JavascriptInterface
	public boolean isLocationAvailable() {

		location = ((MainScreen) this.context).getLocationHandler().getCurrentBestLocationInfo();
		if (location == null) {
			((MainScreen) this.context).getLocationHandler().getCurrentBestLocationInfo();
			return false;
		} else {
			return true;
		}
	}
	
	
	/**
	 * Used to move back in history
	 */
	@JavascriptInterface
	public String myCurrentLocation() {

		location = ((MainScreen) this.context).getLocationHandler().getCurrentBestLocationInfo();
		if (location == null) {
			((MainScreen) this.context).getLocationHandler().getCurrentBestLocationInfo();
			return "0,0";
		} else {
			return ((MainScreen) this.context).getLocationHandler().getCurrentBestLocationInfo().getLatitude()+","+((MainScreen) this.context).getLocationHandler().getCurrentBestLocationInfo().getLongitude();
		}
	}
	
	@JavascriptInterface
	public String getAboutData() {
		
		String AppId=this.context.getString(R.string.app_id);
		String Response="";
		String VN="";
		String VC="";
		try {
			VN = this.context.getPackageManager().getPackageInfo(
					this.context.getPackageName(), 0).versionName;
			VC = ""+this.context.getPackageManager().getPackageInfo(
					this.context.getPackageName(), 0).versionCode;
		} catch (Exception e) {}
		Response=AppId+"_"+VN+"_"+VC;
		return Response;
	}
	

	/**
	 * Used to save data to local database
	 * 
	 * @param formData
	 */
	
	@JavascriptInterface
	public String getDeviceTS() {
		
		return Utility.getFormattedTime(System.currentTimeMillis()).toString();
	}
	@JavascriptInterface
	public void onSaveClick(String formData) {

		Log.i("FORM DATA IN SAVE", "<><> " + formData);
		String formIconPath = null;
		boolean isTakePicture = true;
		try {
			JSONObject obj = new JSONObject(formData);
			isTakePicture = obj.getBoolean("is_take_picture");
			formIconPath = obj.getString("form_icon_name");
			Log.i("onSaveClick--ICON NAME", "<><> " + formIconPath);
			
 
			 
		} catch (Exception e) {
			e.printStackTrace();
		}
 
		String[] pictureData=null;
		if (isTakePicture) {
			pictureData = ((MainScreen) this.context).getPicturePath();
			Log.e("PICTURE DATA IN JS",pictureData.toString());
			if (pictureData == null || pictureData.length <= 0) {
				showAlertDialog("Please take picture first.");
				return;
			}
		} else
			pictureData = null;

		if (location == null)
			location = ((MainScreen) this.context).getLocationHandler().getCurrentBestLocationInfo();

		try {
			versionName = this.context.getPackageManager().getPackageInfo(
					this.context.getPackageName(), 0).versionName;
		} catch (Exception e) {}

		String date = null;
				
		
		long timediff = ((MainScreen) this.context).getTimeDifference();
		boolean LocalTimeTampered = ((MainScreen) this.context).timeTampered;
		boolean isNegativeTime=false;
		long millis=timediff;
		if(timediff<0)
		{
			millis=-1*timediff;
			isNegativeTime=true;
		}
		
		DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
//		dbAdapter.open();
//		long dbsntpTime=dbAdapter.getSntpTS();
//		long dbdevicetime=dbAdapter.getSntpDeviceTS();
//		long elaspedInDB=dbAdapter.getElaspedTS();
//		dbAdapter.close();
//		DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
//		long timeDiffInMinutes=TimeUnit.MILLISECONDS.toMinutes(millis);
//		if(new Date().getTime()<dbsntpTime)
//		{
//			Log.i("time check","Time was backward");
//			long timeA=dbsntpTime-dbdevicetime;		//agar ghari peechay ki to yeh positive aye ga.
//			long timeB=new Date().getTime()-dbdevicetime;
//			long timeC=SystemClock.elapsedRealtime()-elaspedInDB;
//			long fixedMillis=dbsntpTime+timeC;			//adding ticks to the nettime
//			date=df.format(new Date(fixedMillis));
//		}
//		else{
//			if(timeDiffInMinutes<=10)
//			{
//				Log.i("time check","Time is correct");
//				date=df.format(new Date().getTime());
//			}
//			else{
//				
//				//if the clock was tampered while the app was in background
//				if(LocalTimeTampered)
//				{
//					Log.i("time check","Time is local tamper");
//					Log.i("time check","Time was backward");
//					long timeA=dbsntpTime-dbdevicetime;		//agar ghari peechay ki to yeh positive aye ga.
//					
//					long timeB=new Date().getTime()-dbdevicetime;
//					
//					long timeC=SystemClock.elapsedRealtime()-elaspedInDB;
//					
//					long fixedMillis=dbsntpTime+timeC;			//adding ticks to the nettime
//					date=df.format(new Date(fixedMillis));
//				}else{
//					Log.i("time check","Time is DB tamper");
//					Log.i("time check","Time was backward");
//					long timeA=dbsntpTime-dbdevicetime;		//agar ghari peechay ki to yeh positive aye ga.
//					long timeB=new Date().getTime()-dbdevicetime;
//					long timeC=SystemClock.elapsedRealtime()-elaspedInDB;
//					long fixedMillis=dbsntpTime+timeC;			//adding ticks to the nettime
//					date=df.format(new Date(fixedMillis));
//					
//				}
//				
//			}
//		}
		
		date=((MainScreen) this.context).getDate();
		
		SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");  
    	try {  
    	    Date parsedDate = format.parse(date);  
    	    Log.i("parsedDate",""+parsedDate);
    	    Calendar cal = Calendar.getInstance();
            cal.setTime(parsedDate);
            Log.i("Calender",""+cal.get(Calendar.YEAR));
            if(cal.get(Calendar.YEAR)==1970)
            {
            	Date d=new Date();
            	date=format.format(d.getTime());
            	location.setTimeSource("Device");
            	Log.i("in 1970 if",date);
            }
            
//    	    System.out.println(date);  
    	} catch (ParseException e) {  
    	    // TODO Auto-generated catch block  
    	    e.printStackTrace();  
    	    
    	    Date d=new Date();
    	    date=format.format(d.getTime());
    	    location.setTimeSource("Device");
    	    Log.i("in 1970 else",date);
    	}
    	
    	
    	
		
		
    	Log.e("PUNCHING DATE after",date);
		
		
		
    	
		dbAdapter.open();
		Log.e("SAVING THE DATA IN FORM",formData);
		long id = dbAdapter.insertFormsData(formData, date, location.getLocation(),
				pictureData, location.getLocationSource(),
				location.getTimeSource(), CONSTANTS.USER_SAVE, formIconPath);
		dbAdapter.close();
		((MainScreen) this.context).setPictureData();
		Log.e("******* in save", id+";;");
		if(id!=-1)
			Utility.showInfoDialog(context, "Data saved successfully.");
		else
			Utility.showInfoDialog(context, "Save failed. Please retry.");
	}

	
	
	
	@JavascriptInterface
	public long onSaveDraft(String rowId, String formData) {

		Log.i("FORM DATA IN SAVE", "<><> " + formData);
		String formIconPath = null;
		boolean isTakePicture = true;
		try {
			JSONObject obj = new JSONObject(formData);
			isTakePicture = obj.getBoolean("is_take_picture");
			formIconPath = obj.getString("form_icon_name");
			Log.i("onSaveClick--ICON NAME", "<><> " + formIconPath);
			
 
			 
		} catch (Exception e) {
			e.printStackTrace();
		}
		
		String[] pictureData=null;
		if (isTakePicture) {
			
			pictureData = ((MainScreen) this.context).getPicturePath();
			Log.e("PICTURE DATA IN JS",pictureData.toString());
			if (pictureData == null || pictureData.length <= 0) {
				showAlertDialog("Please take picture first.");
				return -1;
			}
		} else
			pictureData = null;
		
		
		if (location == null)
		{
			location = ((MainScreen) this.context).getLocationHandler().getCurrentBestLocationInfo();
		}	
			

		try {
			versionName = this.context.getPackageManager().getPackageInfo(
					this.context.getPackageName(), 0).versionName;
		} catch (Exception e) {}

		String date = null;
		long timediff=0;
		boolean LocalTimeTampered=false;
		
		timediff = ((MainScreen) this.context).getTimeDifference();
		LocalTimeTampered = ((MainScreen) this.context).timeTampered;
		
		boolean isNegativeTime=false;
		long millis=timediff;
		if(timediff<0)
		{
			millis=-1*timediff;
			isNegativeTime=true;
		}
		
		DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
		
		date=((MainScreen) this.context).getDate();
		
		SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");  
    	try {  
    	    Date parsedDate = format.parse(date);  
    	    Log.i("parsedDate",""+parsedDate);
    	    Calendar cal = Calendar.getInstance();
            cal.setTime(parsedDate);
            Log.i("Calender",""+cal.get(Calendar.YEAR));
            if(cal.get(Calendar.YEAR)==1970)
            {
            	Date d=new Date();
            	date=format.format(d.getTime());
            	location.setTimeSource("Device");
            	Log.i("in 1970 if",date);
            }
            
//    	    System.out.println(date);  
    	} catch (ParseException e) {  
    	    // TODO Auto-generated catch block  
    	    e.printStackTrace();  
    	    
    	    Date d=new Date();
    	    date=format.format(d.getTime());
    	    location.setTimeSource("Device");
    	    Log.i("in 1970 else",date);
    	}
    	
    	
    	
		
		
    	Log.e("PUNCHING DATE after",date);
		
		
		
    	
		dbAdapter.open();
		Log.e("SAVING THE DATA IN FORM",formData);
		
		
		long id=-1;
		try{
			
			if (rowId!=null && !rowId.equalsIgnoreCase(""))
			{
				
				long rid=Long.parseLong(rowId);
				id = dbAdapter.updateFormsData(rid, formData, date, location.getLocation(),
						pictureData, location.getLocationSource(),
						location.getTimeSource(), 9, formIconPath);
//				dbAdapter.updateFormsData(formData,9,Integer.parseInt(rowId));
				id=rid;
				
			}
			else{
				
				id = dbAdapter.insertFormsData(formData, date, location.getLocation(),
						pictureData, location.getLocationSource(),
						location.getTimeSource(), 9, formIconPath);
			}
			
			
			
			dbAdapter.close();
			((MainScreen) this.context).setPictureData();
			((MainScreen) this.context).setRowId(Integer.parseInt(""+id));
			Log.e("******* in save", id+";;");
			
			
		}
		catch(Exception e)
		{
			e.printStackTrace();
			Log.e("error while saving draft", "Error");
		}
		
		return id;
		
			
	}

	/**
	 * Used to submit data at server
	 * 
	 * @param formData
	 */
	@JavascriptInterface
	public void onSubmitClick(String formData) {

		
		 if (this.context instanceof MainScreen) {
			
			
		}
		
		getCurrentNetworkTime current = new getCurrentNetworkTime(this.context);
		current.execute();
				
		DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		String timeSettings = android.provider.Settings.System.getString(
                this.context.getContentResolver(),
                android.provider.Settings.Global.AUTO_TIME);
        if (timeSettings.contentEquals("0")) {
            android.provider.Settings.System.putString(
                    this.context.getContentResolver(),
                    android.provider.Settings.Global.AUTO_TIME, "1");
        }
        
        Date now = new Date(System.currentTimeMillis());
        Log.d("Date", now.toString());

        
//		((MainScreen) this.context).captureUserLocation();	
		Log.i("FORM DATA", "<><> " + formData);
		String formIconPath = null;
		boolean isTakePicture = true;
		try {
			JSONObject obj = new JSONObject(formData);
			
			obj.put("deviceTS", Utility.getFormattedTime(System.currentTimeMillis()));
			
			
			Log.e("!!!!!!!!!!!!DEVICE TS",Utility.getFormattedTime(System.currentTimeMillis()));
			isTakePicture = obj.getBoolean("is_take_picture");
			formIconPath = obj.getString("form_icon_name");
			
			formData=obj.toString();
			Log.i("onSubmitClick--ICON NAME", "<><> " + formIconPath);
		} catch (Exception e) {
			e.printStackTrace();
		}

//		byte[] pictureData = ((MainScreen) this.context).getPictureData();
		String[] pictureData = ((MainScreen) this.context).getPicturePath();
		Log.e("PICTURE DATA",""+pictureData);
		if (isTakePicture) {
			if (pictureData == null || pictureData.length <= 0) {
				showAlertDialog("Please take picture first.");
				return;
			}
		} else {
			pictureData = null;
		}

//		if (location == null) {
//			location = ((MainScreen) this.context).getAccurateLocation();
//		}
		location = ((MainScreen) this.context).getLocationHandler().getCurrentBestLocationInfo();
		

		try {
			versionName = this.context.getPackageManager().getPackageInfo(
					this.context.getPackageName(), 0).versionName;
		} catch (Exception e) {

		}
		String date = null;
				
		
		long timediff = ((MainScreen) this.context).getTimeDifference();
		boolean LocalTimeTampered = ((MainScreen) this.context).timeTampered;
		boolean isNegativeTime=false;
		long millis=timediff;
		if(timediff<0)
		{
			millis=-1*timediff;
			isNegativeTime=true;
		}
		
		DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
		dbAdapter.open();
		long dbsntpTime=dbAdapter.getSntpTS();
		long dbdevicetime=dbAdapter.getSntpDeviceTS();
		long elaspedInDB=dbAdapter.getElaspedTS();
		dbAdapter.close();
		
//		location = ((MainScreen) this.context).getAccurateLocation();
		date=((MainScreen) this.context).getDate();

		
		Log.e("PUNCHING DATE before",date);

		  
    	SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");  
    	try {  
    	    Date parsedDate = format.parse(date);  
    	    Log.i("parsedDate",""+parsedDate);
    	    Calendar cal = Calendar.getInstance();
            cal.setTime(parsedDate);
            Log.i("Calender",""+cal.get(Calendar.YEAR));
            if(cal.get(Calendar.YEAR)==1970)
            {
            	Date d=new Date();
            	date=format.format(d.getTime());
            	location.setTimeSource("Device");
            	Log.i("in 1970 if",date);
            }
            
//    	    System.out.println(date);  
    	} catch (ParseException e) {  
    	    // TODO Auto-generated catch block  
    	    e.printStackTrace();  
    	    
    	    Date d=new Date();
    	    date=format.format(d.getTime());
    	    location.setTimeSource("Device");
    	    Log.i("in 1970 else",date);
    	}
    	
    	
    	
		
		
    	Log.e("PUNCHING DATE after",date);
		
		
		int currentId=((MainScreen) this.context).getRowId();
		if (Utility.isInternetAvailable(this.context)) {
			
				
		 
			if (pictureData != null && pictureData.length > 0) {
//				tempdialog.hide();
				
				GDKSubmitPictureDataAsyncTask submitData = new GDKSubmitPictureDataAsyncTask(this.context, pictureData);
				submitData.execute(this.context.getString(R.string.FORM_SUBMIT_URL),
						formData, location.getLocation(), date, versionName,
						location.getLocationSource(), location.getTimeSource(),formIconPath,"YES",""+currentId);
			} else {
//				tempdialog.hide();
				
				GDKSubmitDataAsyncTask submitData = new GDKSubmitDataAsyncTask(this.context);
				submitData.execute(this.context.getString(R.string.FORM_SUBMIT_URL),
						formData, location.getLocation(), date, versionName,
						location.getLocationSource(), location.getTimeSource(), formIconPath,"YES",""+currentId);
			}
			
			
			
			dbAdapter.open();
			dbAdapter.SaveLastActivtiy(formData, date, location.getLocation(),
					pictureData, location.getLocationSource(),
					location.getTimeSource(), CONSTANTS.AUTO_SAVE, formIconPath);
			dbAdapter.close();
			
		} else {

			

//			GDKSubmitDataAsyncTask submitData = new GDKSubmitDataAsyncTask(this.context);
//			submitData.execute(this.context.getString(R.string.FORM_SUBMIT_URL),
//					formData, location.getLocation(), date, versionName,
//					location.getLocationSource(), location.getTimeSource(), formIconPath,"YES");
			dbAdapter.open();
			if (currentId!=-1)
			{
				dbAdapter.updateFormsData(currentId, formData, date,  location.getLocation(), pictureData, location.getLocationSource(), location.getTimeSource(), 1, formIconPath);
			}
			else{
				dbAdapter.insertFormsData(formData, date, location.getLocation(),
						pictureData, location.getLocationSource(),
						location.getTimeSource(), CONSTANTS.AUTO_SAVE, formIconPath);
			}
			dbAdapter.close();
//			
//			showAlertDialog("No internet, data saved locally.");
			showToastMessage("No internet, data saved locally.");
			((MainScreen) this.context).setPictureData();
		}
		
		// refresh the locationHandler so it looks for the new data
		((MainScreen) this.context).getLocationHandler().onPause();
		((MainScreen) this.context).getLocationHandler().onResume();
	}

	@JavascriptInterface
	public void onSubmitClickAfterEdit(String formData) {
		Log.e("*********JS FORM DATA onSubmitClickAfterEdit", "<><> " + "asdasdasd");
		Log.e("*********JS FORM DATA onSubmitClickAfterEdit", "<><> " + formData);
		try {
			versionName = this.context.getPackageManager().getPackageInfo(
					this.context.getPackageName(), 0).versionName;
			Log.e("*********JS versionName", "<><> " + versionName);
		} catch (Exception e) {
			e.printStackTrace();
		}
		Log.e("*********JS onSubmitClickAfterEdit", "<><> " + context);
		FormsDataInfo dataInfo = CONSTANTS.dataInfo;
//		FormsDataInfo dataInfo=((UnsentDataListScreen) this.context).getCurrentItem();
		
		
//		String[] pictureData = ((MainScreen) this.context).getPicturePath();
		String[] pictureData = null;
		Log.e("*********JS onSubmitClickAfterEdit", "1");
		try {
			
			if(dataInfo.imagePaths!=null)
			{
				
				
				JSONArray imageArray1 = new JSONArray(dataInfo.imagePaths);
				pictureData=new String[imageArray1.length()];
			
				JSONArray jsonArray = (JSONArray)imageArray1; 
				if (jsonArray != null) { 
				   for (int i=0;i<jsonArray.length();i++){ 
	//				   listOfImages.add(jsonArray.get(i).toString());
					   pictureData[i]=jsonArray.get(i).toString();
				   } 
				}
			}
			
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		Log.e("*********JS onSubmitClickAfterEdit", "2");
//		Log.e("IMAGES IN SAVED ACTIVITY",""+dataInfo.imagePaths);
//		Log.e("FORM DATA onSubmitClickAfterEdit", "<><> " + dataInfo.imagePaths+";;"+ dataInfo.dateTime+";;"+dataInfo.id+";;"+dataInfo.location+";;"+dataInfo.locationSource+";;"+dataInfo.timeSource);
		if (Utility.isInternetAvailable(this.context)) {
			if (pictureData != null && pictureData.length > 0) {
				Log.e("*********JS onSubmitClickAfterEdit", "3");
				Log.e("Total Images",""+pictureData.length);
				Log.e("IN IF",""+pictureData.toString());
				
				GDKSubmitPictureDataAsyncTask submitData = new GDKSubmitPictureDataAsyncTask(
						this.context, pictureData);
				Log.e("*********JS onSubmitClickAfterEdit", "4");
				submitData.execute(
						this.context.getString(R.string.FORM_SUBMIT_URL),
						formData, dataInfo.location, dataInfo.dateTime, versionName,
						dataInfo.locationSource, dataInfo.timeSource, dataInfo.formIconName,"NO");
			} else {
				Log.e("GDKSubmitDataAsyncTask", "<><> " + ";;");
				Log.e("*********JS onSubmitClickAfterEdit", "5");
				GDKSubmitDataAsyncTask submitData = new GDKSubmitDataAsyncTask(
						this.context);
				submitData.execute(
						this.context.getString(R.string.FORM_SUBMIT_URL),
						formData, dataInfo.location, dataInfo.dateTime, versionName,
						dataInfo.locationSource, dataInfo.timeSource, dataInfo.formIconName,"NO");
			}
			
			
		}else {
			Log.e("*********JS onSubmitClickAfterEdit", "6");
			Log.e("GDKSubmitDataAsyncTask", "<><> " + "NO INTERNET");
			DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
			dbAdapter.open();
			dbAdapter.updateFormsData(formData, CONSTANTS.AUTO_SAVE, dataInfo.id);
			dbAdapter.close();
			showAlertDialog("No internet, data saved locally.");
		}
	}

	@JavascriptInterface
	public void OnScanIDCard() {

		Intent intent = new Intent(this.context, ActivityCapture.class);
		this.context.startActivityForResult(intent,
				MainScreen.SCAN_CNIC_REQUEST);
	}
	

	@JavascriptInterface
	public String GetCountOfUnSentActivities() {
		
		DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
		dbAdapter.open();
		arrayList = dbAdapter.readFormsData();
		dbAdapter.close();
		
		if(arrayList!=null && arrayList.size()>0){
			return ""+arrayList.size();
		}else{
			return ""+0;
		}
		
	}
	
	

	@JavascriptInterface
	public String GetLastActivity(String fid) {
		Log.i("JS INTERFACE","CAME TO GET LAST ACTIVITY");
		String json="";
		try{
			((MainScreen) this.context).clearImgHashTable();
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		if(this.context instanceof MainScreen)
		{
			if(fid.equalsIgnoreCase("null") || fid.equalsIgnoreCase("undefined") || fid==null)
			{
				
			}
			else{
				DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
				dbAdapter.open();
				json = dbAdapter.getLastSentAcitivity(fid);
				Log.e("ACTIVITY JSON",json);
				dbAdapter.close();
			}
		
		}
		return json;
	}
	
	
	//Using for Time satus
	@JavascriptInterface
	public String checkTimeStatus(String id) {
		 
		DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
		dbAdapter.open();
		String timeValue = dbAdapter.getTimeFromTempTable(id);
		dbAdapter.close();
		return timeValue;
	}
	
	@JavascriptInterface
	public void insertTimeIntoDB(String id, String timeValue, String statusValue) {
		
		DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
		dbAdapter.open();
		dbAdapter.insertTempTime(id, timeValue, statusValue);
		dbAdapter.close();
	}
	
	@JavascriptInterface
	public void removeTimeData(String status) {
		
		DataBaseAdapter dbAdapter = new DataBaseAdapter(this.context);
		dbAdapter.open();
		dbAdapter.deleteAllTempTimeData(status);
		dbAdapter.close();
	}

}
