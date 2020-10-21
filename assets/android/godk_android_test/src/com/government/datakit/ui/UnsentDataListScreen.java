package com.government.datakit.ui;

import java.util.ArrayList;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.AlertDialog;
import android.app.ListActivity;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.DialogInterface;
import android.content.SharedPreferences;
import android.content.DialogInterface.OnClickListener;
import android.content.SharedPreferences.Editor;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.os.SystemClock;
import android.preference.PreferenceManager;
import android.util.Log;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.government.datakit.bo.FormsDataInfo;
import com.government.datakit.db.DataBaseAdapter;
import com.government.datakit.uiadapters.UnsentDataAdapter;
import com.government.datakit.utils.CONSTANTS;
import com.government.datakit.utils.GDKSubmitDataAsyncTask;
import com.government.datakit.utils.GDKSubmitPictureDataAsyncTask;
import com.government.datakit.utils.Resources;
import com.government.datakit.utils.Utility;

public class UnsentDataListScreen extends ListActivity {

	private UnsentDataAdapter adapter;
	private ArrayList<FormsDataInfo> arrayList;
	private String versionName = null;

	private int unsentDataId;
	private FormsDataInfo dataInfo;
	private ArrayList<Integer> unsentDataIds;
	
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		 
		Intent intent = getIntent();
		String show = intent.getStringExtra("show");
 
		
		
		setContentView(R.layout.unsentdata_list_screen);

		DataBaseAdapter dbAdapter = new DataBaseAdapter(this);
		dbAdapter.open();
		
		
		Log.e("SHOW",""+show);
		if(show!=null){
			
			SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(getBaseContext());
			Editor editor = prefs.edit();
			editor.putString("show", show);
			editor.commit();
			if(show.equalsIgnoreCase("onlyedit"))
			{
				
				TextView textView= (TextView)this.findViewById(R.id.textView1);
				textView.setText("Saved Items Only");
				
				arrayList = dbAdapter.readFormsEditData();
			}
			else if(show.equalsIgnoreCase("onlyunsent"))
			{
				TextView textView= (TextView)this.findViewById(R.id.textView1);
				textView.setText("Unsent Items Only");
				arrayList = dbAdapter.readFormsUnsentData();
			}
			else{
				arrayList = dbAdapter.readFormsData();
			}
		}
		else{
			arrayList = dbAdapter.readFormsData();
		}
		
		dbAdapter.close();

		adapter = new UnsentDataAdapter(this, arrayList);
		setListAdapter(adapter);
		
		resetNotification();
		
		this.unsentDataIds=new ArrayList<Integer>();

		if(arrayList != null)
		{
			for (FormsDataInfo fd: arrayList) {
				if(fd.autoSave==CONSTANTS.AUTO_SAVE)
				{
					Log.e("setting Button","ok");
					Button btn = (Button) findViewById(R.id.sendAllButton);
					btn.setVisibility(View.VISIBLE);
				}
				
			}
		}

//		Button sendAll = (Button) findViewById(R.id.sendAllButton);
//		sendAll.setOnClickListener(this);
//		Button btn1 = (Button)findViewById(R.id.button1);
		
	}
	

	public String ReadSetting(String S)
	{
		SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(this);
		String value = preferences.getString(S, "");
		
		if(value=="")
		{ 
			if(S.equalsIgnoreCase("IS_SECURE_APP"))
			{
				value=this.getBaseContext().getResources().getString(R.string.IS_SECURE_APP);
			}
			else if(S.equalsIgnoreCase("showHighResOption"))
			{
				value=this.getBaseContext().getResources().getString(R.string.showHighResOption);
			}
			else if(S.equalsIgnoreCase("PersistImagesOnDevice"))
			{
				value=this.getBaseContext().getResources().getString(R.string.PersistImagesOnDevice);
			}
			else if(S.equalsIgnoreCase("BackgroundUpdate"))
			{
				value=this.getBaseContext().getResources().getString(R.string.BackgroundUpdate);
			}
			else if(S.equalsIgnoreCase("ForceUpdate"))
			{
				value=this.getBaseContext().getResources().getString(R.string.ForceUpdate);
			}
			else if(S.equalsIgnoreCase("EnableAutoTime"))
			{
				value=this.getBaseContext().getResources().getString(R.string.EnableAutoTime);
			}
			else if(S.equalsIgnoreCase("TrackingInterval"))
			{
				value=this.getBaseContext().getResources().getString(R.string.TrackingInterval);
			}
			else if(S.equalsIgnoreCase("TrackingDistance"))
			{
				value=this.getBaseContext().getResources().getString(R.string.TrackingDistance);
			}
			else if(S.equalsIgnoreCase("DebugTracking"))
			{
				value=this.getBaseContext().getResources().getString(R.string.DebugTracking);
			}
			else if(S.equalsIgnoreCase("hasGeoFencing"))
			{
				value=this.getBaseContext().getResources().getString(R.string.hasGeoFencing);
			}
			else if(S.equalsIgnoreCase("DebugGeoFencing"))
			{
				value=this.getBaseContext().getResources().getString(R.string.DebugGeoFencing);
			}
			else{
				value="";
			}
		}
		
		return value;
	}

	
	public void resetNotification(){

		DataBaseAdapter dbAdapter = new DataBaseAdapter(this.getBaseContext());
		dbAdapter.open();
		ArrayList<FormsDataInfo> arrayList = dbAdapter.readFormsData();
		dbAdapter.close();
		
		if(arrayList!=null && arrayList.size()>0){
			
			Context context = getApplicationContext();
			
			Intent intent = new Intent(this, MainScreen.class);
			
			intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TOP
		            | Intent.FLAG_ACTIVITY_SINGLE_TOP);
			
			
			
			
			intent.putExtra("showunsent", "yes");
			intent.putExtra("notification", "yes");
			intent.setData((Uri.parse("foobar://"+SystemClock.elapsedRealtime())));
			
			PendingIntent pIntent = PendingIntent.getActivity(context, 0, intent, 0);
			Notification noti = new Notification.Builder(this)
			.setTicker(getResources().getString(R.string.app_name))
			.setContentTitle(getResources().getString(R.string.app_name))
			.setContentText("You have "+arrayList.size()+" unsent activites")
			.setSmallIcon(R.drawable.icon)
			.setContentIntent(pIntent).getNotification();
			noti.flags=Notification.FLAG_ONGOING_EVENT | Notification.FLAG_NO_CLEAR;
			NotificationManager notificationManager = (NotificationManager) getSystemService(NOTIFICATION_SERVICE);
			notificationManager.notify(0, noti); 
			
			
			
		}else{
			
			NotificationManager notificationManager = (NotificationManager) getSystemService(NOTIFICATION_SERVICE);
			notificationManager.cancel(0);
			
			
		}

		
//		((MainScreen) getActivity()).updateBubble();
		
//		
//		  WebView webView=(WebView)findViewById(R.id.webview);
//		  webView.loadUrl("javascript:resetCounter()");
		
		
		Bundle extras = getIntent().getExtras();
		if(extras !=null) {
		    String value = extras.getString("showunsent");
		    Intent unsentIntent = new Intent(this,UnsentDataListScreen.class);
		    startActivity(unsentIntent);
    		
		}

		
		
	}
	
	public void onSendAllClicked(View v){
	    if(v.getId() == R.id.sendAllButton){
	    	boolean hasAutoSave=false;
	    	Button btn = (Button) findViewById(R.id.sendAllButton);
			btn.setVisibility(View.GONE);
	    	if(arrayList != null)
	    	{
	    		
		    	
		    	for(int i=0; i<arrayList.size();i++)
		    	{
		    		
		    		FormsDataInfo dataInfo=arrayList.get(i);
		    		if(dataInfo.autoSave==1)
		    		{
		    			hasAutoSave=true;
		    		}
		    	}
		    	
		    	if(hasAutoSave)
		    	{
		    		Toast.makeText(this.getApplicationContext(), "Submitting Data", 5000).show();
		    	}
		    	else{
		    		Toast.makeText(this.getApplicationContext(), "No Data ready for submission", 5000).show();
		    	}
		    	
		    	for(int i=0; i<arrayList.size();i++)
		    	{
		    		FormsDataInfo dataInfo=arrayList.get(i);
		    		Log.d("ADebugTag", "Value: " + dataInfo.autoSave);
		    		Log.i("dataInfo","Below is datainfo object");
	//	    		System.out.print(dataInfo);
	
		    		if(dataInfo.autoSave==1)
		    		{
		    			onSubmitClick(dataInfo);
		    		}
	
		    	}
		    	
		    	resetNotification();
		    	
	    	}else{
	    		Toast.makeText(this.getApplicationContext(), "Nothing to submit", Toast.LENGTH_SHORT).show();
	    	}

	    	
	    }
	}

	@Override
	protected void onListItemClick(ListView lv, View v, int position, long id) {
		super.onListItemClick(lv, v, position, id);
		dataInfo = (FormsDataInfo) lv.getItemAtPosition(position);
		CONSTANTS.dataInfo=dataInfo;
		if(dataInfo.autoSave==1)
			showOptionAlert(dataInfo);
		else{
			showOptionAlertForEdit(dataInfo);
		}
	}

	@Override
	protected void onRestart() {
		super.onRestart();

	}
	
	public FormsDataInfo getCurrentItem(){
		return dataInfo;
	}

	/**
	 * Used to show option dialog for Edit, delete and cancel current operation
	 * @param dataInfo
	 */
	public void showOptionAlertForEdit(final FormsDataInfo dataInfo) {

		new AlertDialog.Builder(this)
		.setIcon(R.drawable.info_icon)
		.setTitle(getString(R.string.info))
		.setMessage(getString(R.string.data_edit_msg))
		.setPositiveButton(getString(R.string.edit), new OnClickListener() {

			@Override
			public void onClick(DialogInterface dialog, int which) {
				
				EditFormScreen.context = UnsentDataListScreen.this;
				unsentDataId = dataInfo.id;
				Intent intent=null;
				if (dataInfo.autoSave==9){
					intent = new Intent(UnsentDataListScreen.this, MainScreen.class);
				}
				else{
					intent = new Intent(UnsentDataListScreen.this, EditFormScreen.class);	
				}
				
			    
			    intent.putExtra("form_data", dataInfo.formData);
			    intent.putExtra("picture_data", dataInfo.imagePaths);
			    intent.putExtra("auto_save", dataInfo.autoSave);
			    intent.putExtra("rowId", dataInfo.id);
			    startActivity(intent);
				dialog.dismiss();
			}
		}).setNeutralButton(getString(R.string.delete),new OnClickListener() {

			@Override
			public void onClick(DialogInterface dialog, int which) {
				
				DataBaseAdapter dbAdapter = new DataBaseAdapter(UnsentDataListScreen.this);
				dbAdapter.open();
				dbAdapter.deleteFormsDataItem(dataInfo.id);
				arrayList = dbAdapter.readFormsData();
				dbAdapter.close();
				// adapter = new UnsentDataAdapter(this, arrayList);
				// setListAdapter(adapter);
				adapter.setFormData(arrayList);
				adapter.notifyDataSetChanged();
				dialog.dismiss();
			}
		}).setNegativeButton(getString(R.string.cancel), new OnClickListener() {
			
			@Override
			public void onClick(DialogInterface dialog, int which) {
				dialog.dismiss();
			}
		})
		.show();
	}
	
	
	
	/**
	 * Used to show option dialog for submit, delete and cancel current operation
	 * @param dataInfo
	 */
	public void showOptionAlert(final FormsDataInfo dataInfo) {

		new AlertDialog.Builder(this)
		.setIcon(R.drawable.info_icon)
		.setTitle(getString(R.string.info))
		.setMessage(getString(R.string.data_upload_msg))
		.setPositiveButton(getString(R.string.yes), new OnClickListener() {

			@Override
			public void onClick(DialogInterface dialog, int which) {
				onSubmitClick(dataInfo);
				dialog.dismiss();
			}
		}).setNeutralButton(getString(R.string.delete),new OnClickListener() {

			@Override
			public void onClick(DialogInterface dialog, int which) {
				
				DataBaseAdapter dbAdapter = new DataBaseAdapter(UnsentDataListScreen.this);
				dbAdapter.open();
				dbAdapter.deleteFormsDataItem(dataInfo.id);
				arrayList = dbAdapter.readFormsData();
				dbAdapter.close();
				// adapter = new UnsentDataAdapter(this, arrayList);
				// setListAdapter(adapter);
				adapter.setFormData(arrayList);
				adapter.notifyDataSetChanged();
				dialog.dismiss();
			}
		}).setNegativeButton(getString(R.string.no), new OnClickListener() {
			
			@Override
			public void onClick(DialogInterface dialog, int which) {
				dialog.dismiss();
				
			}
		} )
		.show();
	}
	
	
	

	
	public void onSubmitClick(FormsDataInfo dataInfo) {

		String location = null;
		String dateTime = null;
		Log.e("SENDING DATA",dataInfo.toString());
		try {
			versionName = getPackageManager().getPackageInfo(getPackageName(),0).versionName;
		} catch (Exception e) {

		}
 
		//remove || true after fix;
		if (Utility.isInternetAvailable(this)) {
			Log.e("form image array",""+dataInfo.imagePaths);
			String[] imageArray=null;
			ArrayList<String> listOfImages = new ArrayList<String>();     
			if(dataInfo.imagePaths!="" && dataInfo.imagePaths!=null )
			{
				try {
					
					JSONArray imageArray1 = new JSONArray(dataInfo.imagePaths);
					imageArray=new String[imageArray1.length()];
					
					JSONArray jsonArray = (JSONArray)imageArray1; 
					if (jsonArray != null) { 
					   for (int i=0;i<jsonArray.length();i++){ 
						   listOfImages.add(jsonArray.get(i).toString());
						   imageArray[i]=jsonArray.get(i).toString();
					   } 
					}
					   
					Log.e("First value in array",""+listOfImages.get(0));
					
				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
			
			
			
			
			if (listOfImages != null && listOfImages.size() > 0) {

				Resources.getResources().setMultiplePictureData(null);
				location = dataInfo.location;
				dateTime = dataInfo.dateTime;
				GDKSubmitPictureDataAsyncTask submitData = new GDKSubmitPictureDataAsyncTask(
						this, imageArray);
				submitData.execute(getString(R.string.FORM_SUBMIT_URL),
						dataInfo.formData, location, dateTime, versionName,
						dataInfo.locationSource, dataInfo.timeSource, dataInfo.formIconName,"NO");
			} else {

				location = dataInfo.location;
				dateTime = dataInfo.dateTime;
				GDKSubmitDataAsyncTask submitData = new GDKSubmitDataAsyncTask(
						this);
				submitData.execute(getString(R.string.FORM_SUBMIT_URL),
						dataInfo.formData, location, dateTime, versionName,
						dataInfo.locationSource, dataInfo.timeSource, dataInfo.formIconName,"NO");
			}
			unsentDataId = dataInfo.id;
			this.unsentDataIds.add(unsentDataId);
			
			
			if(arrayList!=null)
			{
				for (FormsDataInfo fd: arrayList) {
					if(fd.autoSave==CONSTANTS.AUTO_SAVE)
					{  
						Log.e("setting Button","ok");
						Button btn = (Button) findViewById(R.id.sendAllButton);
						btn.setVisibility(View.VISIBLE);
					}
					
				}
			}
			/*
			 * DataBaseAdapter adapter = new DataBaseAdapter(this);
			 * adapter.open(); adapter.deleteFormsDataItem(dataInfo.id);
			 * adapter.close();
			 */
		} else {
			Toast.makeText(this, getString(R.string.internet_error_msg),
					Toast.LENGTH_SHORT).show();
		}

	}
	
	@Override
	protected void onResume() {
		SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(getBaseContext());
		String show=prefs.getString("show", "");
		DataBaseAdapter dbAdapter = new DataBaseAdapter(this);
		dbAdapter.open();
			if(show!=null){
			
			if(show.equalsIgnoreCase("onlyedit"))
			{
				
				TextView textView = (TextView)this.findViewById(R.id.textView1);
				textView.setText("Saved Items Only");
				
				arrayList = dbAdapter.readFormsEditData();
			}
			else if(show.equalsIgnoreCase("onlyunsent"))
			{
				TextView textView= (TextView)this.findViewById(R.id.textView1);
				textView.setText("Unsent Items Only");
				arrayList = dbAdapter.readFormsUnsentData();
			}
			else{
				arrayList = dbAdapter.readFormsData();
			}
		}
		else{
			arrayList = dbAdapter.readFormsData();
		}
		
//			adapter.setFormData(arrayList);
			if(adapter!=null)
			{
				adapter = new UnsentDataAdapter(this, arrayList);
				setListAdapter(adapter);
//				adapter.notifyDataSetChanged();
			}
			
		dbAdapter.close();
		super.onResume();
		
	}

	public void dataSubmitSuccessfully() {
		
		DataBaseAdapter dbAdapter = new DataBaseAdapter(this);
		dbAdapter.open();
		for(int i=0; i<this.unsentDataIds.size();i++)
		{
			int udi=this.unsentDataIds.get(i);
			dbAdapter.deleteFormsDataItem(udi);
			arrayList = dbAdapter.readFormsData();
			adapter.setFormData(arrayList);
			adapter = new UnsentDataAdapter(this, arrayList);
			setListAdapter(adapter);
			adapter.notifyDataSetChanged();
		}
		 
		if(this.unsentDataIds.size()==0)
		{
			FormsDataInfo fd= getCurrentItem();
			dbAdapter.deleteFormsDataItem(fd.id);
			arrayList = dbAdapter.readFormsData();
			adapter.setFormData(arrayList);
		}
		arrayList = dbAdapter.readFormsData();
		dbAdapter.close();
		adapter.notifyDataSetChanged();
	 
		resetNotification();
	}

}
