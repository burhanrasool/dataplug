package com.government.datakit.ui;

import java.io.File;
import java.io.FileOutputStream;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.Hashtable;
import java.util.Locale;
import java.util.UUID;
import java.util.concurrent.TimeUnit;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.annotation.SuppressLint;
import android.annotation.TargetApi;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.Fragment;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.DialogInterface.OnClickListener;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.content.pm.ApplicationInfo;
import android.graphics.Bitmap;
import android.graphics.Bitmap.CompressFormat;
import android.graphics.BitmapFactory;
import android.location.Location;
import android.location.LocationListener;
import android.net.Uri;
import android.os.AsyncTask.Status;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.os.SystemClock;
import android.preference.PreferenceManager;
import android.provider.MediaStore.Images.Media;
import android.provider.Settings;
import android.provider.Settings.SettingNotFoundException;
import android.support.v4.content.LocalBroadcastManager;
import android.telephony.TelephonyManager;
import android.util.Log;
import android.view.KeyEvent;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.Toast;

import com.government.datakit.bo.FormsDataInfo;
import com.government.datakit.bo.LocationInfo;
import com.government.datakit.bo.trackingPoint;
import com.government.datakit.db.DataBaseAdapter;
import com.government.datakit.interfaces.GDKInterface;
import com.government.datakit.prefrences.GDKPreferences;
import com.government.datakit.uiadapters.UnsentDataAdapter;
import com.government.datakit.utils.BootReceiver;
import com.government.datakit.utils.CONSTANTS;
import com.government.datakit.utils.GDKCheckVersionAsyncTask;
import com.government.datakit.utils.GDKCopyFilesAsyncTask;
import com.government.datakit.utils.GDKUpdateFormsAsyncTask;
import com.government.datakit.utils.LocationHandler;
import com.government.datakit.utils.Resources;
import com.government.datakit.utils.Utility;
import com.government.datakit.utils.bgService;
import com.government.datakit.utils.getCurrentNetworkTime;
import com.government.datakit.utils.submitTrackerPoint;
import com.government.datakit.utils.trackerService;

/**
 * This is the main activity which holds the webview to show
 * all HTML contents/forms to user. 
 * @author gulfamhassan
 */

public class MainScreen extends Activity implements GDKInterface{
     //Location code moved to separate class LocationHandler
//	private LocationManager locationManager;
//	private Location gpsLocation = null;
//	private Location networkLocation = null;
	//private String accurateLocation = "";
		//private String DATE_TIME;
	
	private LocationHandler locationHandler=null;
	public WebView webView;
	private Button versionButton;
	public static String htmlFilesDirectory;
	public static GDKInterface gdkInterface;
	private int versionCode; 
	private String versionUpdateUrl;
	public static final int CAMERA_PIC_REQUEST = 1;
	public static final int SCAN_CNIC_REQUEST = 2;
	private byte [] pictureBytes;
	private String [] picturePath;
	private String pictureId;
	private String pictureResolution;
	public boolean timeTampered;
	public long timeTamperedAmount;
	public ProgressDialog tempdialog;
	public boolean showBackAlert;
	public boolean processingTrackerPoints;
	AlertDialog alert;
	private int auto_save;
	private String formData;
	private int rowId;
	private String form_name;
	private boolean visited;
	private boolean Editing;
	public String ImageData;
	private Hashtable<String, byte[]> hashTable = new Hashtable<String, byte[]>();
	private Hashtable<String, String> ImgPathhashTable = new Hashtable<String, String>();

//	@SuppressWarnings("static-access")
//	@SuppressLint({ "SetJavaScriptEnabled", "NewApi" })
	@SuppressLint({ "SetJavaScriptEnabled", "NewApi" })
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		this.Editing=false;
		try{
		  versionCode = getPackageManager().getPackageInfo(getPackageName(), 0).versionCode;
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		setContentView(R.layout.main_screen);
		webView = (WebView)findViewById(R.id.webview);
		WebSettings ws = webView.getSettings();
		ws.setJavaScriptEnabled(true);
		webView.clearCache(true);
		htmlFilesDirectory = "APPID"+getString(R.string.app_id);
		visited=false;
		
		final JavaScriptInterface jsi = new JavaScriptInterface(this);
		webView.addJavascriptInterface(jsi, "AndroidFunction");
		
		this.auto_save=0;
		this.rowId=-1;
		Bundle xtra = getIntent().getExtras(); 
		if (xtra != null) {
			formData = xtra.getString("form_data");
			this.auto_save = xtra.getInt("auto_save",0);
			this.rowId = xtra.getInt("rowId",-1);
			ImageData= xtra.getString("picture_data","");
			
			JSONArray temp;
			try {
				
				
//				ImgPathhashTable.clear();
				temp = new JSONArray(ImageData);
				String[] mArray = temp.join(",").split(",");
			    for(int i=0;i<mArray.length;i++){
			    	String Img=mArray[i];
			    	Img=Img.replace("\"", "");
			    	addPicturesPathToArray("",Img);	
			    }
			    
			    
			    
			    
			} catch (JSONException e1) {
				// TODO Auto-generated catch block
				e1.printStackTrace();
			}
			
		    
			try{
				form_name=new JSONObject(formData).getString("landing_page");
			}catch(Exception e){
				e.printStackTrace();
			}
			
			
							
		}

		
		
		
		
		
		
		
		
		
		////////////////////////////////////////////////////////////////////////////////////////////////
		if (auto_save==9){
			webView.loadUrl("file:///"+this.getFilesDir().getPath() + "/"+htmlFilesDirectory + "/" + form_name);
			this.Editing=true;
			webView.setWebViewClient(new WebViewClient() {
				
				public void onPageFinished(WebView view, String url) {
					if(!visited){
//						ImgPathhashTable.clear();
						visited=true;
						Log.e("FORM DATA LOADING TO VIEW", ""+formData);
						if (auto_save==9)
						{
							webView.loadUrl("javascript:load_draft("+rowId+",'"+formData+"','"+ImageData+"')");
						}
						else{
							webView.loadUrl("javascript:load_record('"+formData+"')");
						}
						
						
						
	  					

					}
				}
			});
		}
		if (auto_save!=9)
		{
		Editing=false;
		ImgPathhashTable.clear();
		SharedPreferences tempp = PreferenceManager.getDefaultSharedPreferences(getBaseContext());
		boolean firstLaunch=tempp.getBoolean("firstLaunch", true);
		
		if(firstLaunch){
			
			DataBaseAdapter dbAdapter = new DataBaseAdapter(this.getBaseContext());
			dbAdapter.open();
			
			dbAdapter.recreateTables();
			dbAdapter.close();
			
			Editor editor = tempp.edit();
			editor.putBoolean("firstLaunch", false);
			editor.commit();
		}
		
		
		
		setContentView(R.layout.main_screen);
		webView = (WebView)findViewById(R.id.webview);
//		webView.clearCache(true);
		webView.setWebViewClient(new WebViewClient());
		
		versionButton = (Button)findViewById(R.id.version_btn_id);
		WebSettings webSettings = webView.getSettings();
		webSettings.setBuiltInZoomControls(false);
		webSettings.setJavaScriptEnabled(true);
		if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.KITKAT) {
		    webView.setLayerType(View.LAYER_TYPE_HARDWARE, null);
		    webView.setWebContentsDebuggingEnabled(true);
		} else {
		    webView.setLayerType(View.LAYER_TYPE_SOFTWARE, null);
		}
		
		
		this.timeTampered=false;
		this.processingTrackerPoints=false;
		gdkInterface = this;
		try{
			versionCode = getPackageManager().getPackageInfo(getPackageName(), 0).versionCode;
		File f=new File(Environment.getExternalStorageDirectory().getPath()+"/"+getString(R.string.app_id)+"_"+versionCode+".apk");
		if(f.exists())
		{
			f.delete();
		}
		}catch(Exception e){

		}
		this.tempdialog=new ProgressDialog(this);
		this.tempdialog.setMessage("Please wait, preparing data");
		this.tempdialog.setCancelable(false);
		this.tempdialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);

		this.tempdialog.setInverseBackgroundForced(false);
		this.showBackAlert=false;
		

		//Used to register JavaScriptInterface with HTML to get call backs from HTML
		final JavaScriptInterface myJavaScriptInterface = new JavaScriptInterface(this);
		webView.addJavascriptInterface(myJavaScriptInterface, "AndroidFunction");
		htmlFilesDirectory = "APPID"+getString(R.string.app_id);

		
		
	
		//Used to check new or fresh version. If fresh or new version install it will copy all files from
		//Assets to internal directory and show UI from internal directory instead of showing form Assets 
		if(GDKPreferences.getPreferences().getAppVersionCode(this) != versionCode){
			GDKCopyFilesAsyncTask copyFiles = new GDKCopyFilesAsyncTask(this, this);
			copyFiles.execute("HTML", htmlFilesDirectory);
			GDKPreferences.getPreferences().setAppVersionCode(this, versionCode);
		}else{		
			webView.loadUrl("file:///"+this.getFilesDir().getPath() + "/"+htmlFilesDirectory + CONSTANTS.LANDING_PAGE);		
		}
		
		//icon_notify_counter
		
		updateBubble();
//		putLastActivityData();
		Bundle extras = getIntent().getExtras();
		if(extras !=null) {
		    String value = extras.getString("showunsent");
		    Intent unsentIntent = new Intent(this,UnsentDataListScreen.class);
		    startActivity(unsentIntent);
    		
		}

		
		
		/////////////////////////////////////////////////////////////
		

		  IntentFilter filter = new IntentFilter();
		  filter.addAction("Action.abc");
		  LocalBroadcastManager.getInstance(this).registerReceiver(new BootReceiver(), filter);
		
		
		//////////////////////////////////////////////////////////////
	

	   
		//Send auto request to server to check for updates
		     
		  GDKUpdateFormsAsyncTask updateForms = new GDKUpdateFormsAsyncTask(this, this,false);
			updateForms.execute(getString(R.string.FORM_UPDATE_URL),"YES");
			
				GDKCheckVersionAsyncTask checkVersion = new GDKCheckVersionAsyncTask(this, this);//Auto Refresh Close after Burhaan's message
				checkVersion.execute(getString(R.string.CHECK_UPDATE_VERSION_URL), getString(R.string.app_id), versionCode+"","FROMMAIN");
 

			// Location related code will be called in onResume and onPause from class LocationHandler
				
//				locationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
//				if (!locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER) || !locationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER)){  
//					createGpsDisabledAlert();  
//				}
//				
//				if(isMockSettingsON(this.getBaseContext()))
//				{
//					
//					try {
//					          Log.d("mock" ,"Removing Test providers");
//					          locationManager.removeTestProvider(LocationManager.GPS_PROVIDER);
//					          locationManager.removeTestProvider(LocationManager.NETWORK_PROVIDER);
//					     } catch (IllegalArgumentException error) {
//					          Log.d("mock","Got exception in removing test  provider");
//					     }
//					createMockDisabledAlert();
//
//					
//				}
				
//				captureUserLocation();
				
			//	LocationInfo l=getAccurateLocation();

				
				
				long clockDifference=getTimeDifference();
				 
				SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(getBaseContext());
				Editor editor = prefs.edit();
				editor.putLong("timediffMain", clockDifference);
				editor.commit();
				
				getCurrentNetworkTime current = new getCurrentNetworkTime(this.getBaseContext());
				
				
				
				DataBaseAdapter dba=new DataBaseAdapter(this.getBaseContext());
				dba.open();
				long systemElapsedTsInDb=dba.getElaspedTS();
				long systemDateAtPause=dba.getElaspedDeviceTS();
				dba.close();
				
				long differenceAtPause=systemDateAtPause-systemElapsedTsInDb;
				
				long CurrentElapsed=SystemClock.elapsedRealtime();
				long CurrentDeviceTime=new Date().getTime();
				
				long differenceNow=CurrentDeviceTime-CurrentElapsed;
				
				long totaldiff=differenceNow-differenceAtPause;
				
				Log.i("TIME DIFFERENCE",""+totaldiff);
				if(totaldiff>=10*60*1000 || totaldiff<=10*60*1000)		//tolrence of 10 mins
				{
					Log.e("Tampering","Clock Not Tampered");
					this.timeTampered=false;
				}
				else{
					this.timeTampered=true;
					Log.e("Tampering","Clock Has been Tampered");
					Log.e("tamper","TamperedDiff "+differenceNow);
					Log.e("tamper","TamperedThan "+differenceAtPause);
					
					Log.e("tamper","Date Now Diff "+new Date(differenceNow));
					Log.e("tamper","Date Than Diff "+new Date(differenceAtPause));
				}
			
				
				
				putLastActivityData();
				
		}
		
	}
	
	public void showTempLoader(){
		this.tempdialog.show();
	}
	
	public void hideTempLoader(){
		this.tempdialog.hide();
	} 
	
	public void setShowBackAlert(boolean s)
	{
		this.showBackAlert=s;
	}
	
	
	public LocationHandler getLocationHandler(){
		return this.locationHandler;
	
	}
	
	public void testButtonClick(View v){
		
		
//	    if(v.getId() == R.id.testbutton){
//	    	
//	    	DataBaseAdapter dbAdapter = new DataBaseAdapter(getBaseContext());
//			dbAdapter.open();
//			String appId=getBaseContext().getString(R.string.app_id);
//			String routeId = UUID.randomUUID().toString();
//			
//			for (int i=0; i<10000; i++)
//			{
//			dbAdapter.saveTrackerPoint(""+"32,74", ""+"32" , ""+"74", ""+"1",
//					""+"11", ""+"1",""+Utility.getCurrentDate(new Date()),Utility.getCurrentDate(new Date()),"12312312312312",appId,routeId,"1","NO","0");
//			Log.e("dummy data inserted #", ""+i);
//			}
//			
//			
//			dbAdapter.close();
//			
////			beginService();			//starting the background service which will update sntp time
//	
//	    	
//	    	
//	    }
	}
//	public void testButtonClick2(View v){
//		
//		
//	    if(v.getId() == R.id.testbutton2){
//	
//	    	stopService();			//stop the background service which will update sntp time
//	
//	    	
//	    	
//	    }
//	}

	public void checkUpdate(){
		GDKCheckVersionAsyncTask checkVersion = new GDKCheckVersionAsyncTask(this, this);//Auto Refresh Close after Burhaan's message
		checkVersion.execute(getString(R.string.CHECK_UPDATE_VERSION_URL), getString(R.string.app_id), versionCode+"","FROMMAIN");


	}
	public void putLastActivityData(){
		webView.loadUrl("javascript:loadLastActivity()");
//		Toast.makeText(this, "Populated Fields from the recent activity", Toast.LENGTH_SHORT).show();
		
	}
	
	
	public void updateBubble(){
		Log.i("update Bubble","in function");
		webView.loadUrl("javascript:resetCounter()");
		
		

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
		
		

	}
	@Override
    public void onBackPressed() {
        // TODO Auto-generated method stub
		
	       
	       
		updateBubble();
		putLastActivityData();
        super.onBackPressed();


    }
	

	 // Method to start the service
	   public String getDate() {
		   String dt="";
		   long dtime=new Date().getTime();
		   dt=Utility.getFormattedTime(dtime);
		   if(locationHandler.getCurrentBestLocationInfo()!=null)
		   {
			   dt=Utility.getFormattedTime(locationHandler.getCurrentBestLocationInfo().getLocationTime());
		   }
	      return dt;
	   }
	   
	
	 // Method to start the service
	   public void beginService() {
	      startService(new Intent(getBaseContext(), trackerService.class));
	   }

	   // Method to stop the service
	   public void stopService() {
	      stopService(new Intent(getBaseContext(), trackerService.class));
	   }
 
	@Override
	protected void onNewIntent(Intent intent) {
	   
	   updateBubble();
	   putLastActivityData();
	   super.onNewIntent(intent);
	}
	
	/**
	 * Used to download new version or update forms
	 * @param view
	 */
	public void onVersionClick(View view){

		if(versionUpdateUrl != null && versionUpdateUrl.length()>0){

			Intent i = new Intent(Intent.ACTION_VIEW, Uri.parse(versionUpdateUrl));
			startActivity(i);
			this.finish();
		}else{

			GDKUpdateFormsAsyncTask updateForms = new GDKUpdateFormsAsyncTask(this, this,true);
			updateForms.execute(getString(R.string.FORM_UPDATE_URL),"NO");
		}
	}

	@Override
	public void fileSuccessCopied() {
//		String isSecureApp = getString(R.string.IS_SECURE_APP);
		String isSecureApp = ReadSetting("IS_SECURE_APP");
		if(isSecureApp.equalsIgnoreCase("yes")){
			
			GDKUpdateFormsAsyncTask updateForms = new GDKUpdateFormsAsyncTask(this, this,true);
			updateForms.execute(getString(R.string.FORM_UPDATE_URL),"NO");
		}else{
			GDKPreferences.getPreferences().setAppFirstLaunch(this, false);
			webView.loadUrl("file:///"+this.getFilesDir().getPath() + "/"+htmlFilesDirectory + CONSTANTS.LANDING_PAGE);
		}
	}

	@Override
	public void formsUpdated() {	
		try{
			versionButton.setVisibility(View.GONE);	
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
		
		webView.clearCache(true);
		webView.loadUrl("file:///"+this.getFilesDir().getPath() + "/"+htmlFilesDirectory + CONSTANTS.LANDING_PAGE);
		
	}

	@Override
	public void updateAvailable(String response) {
		try{

			JSONObject jObj =  new JSONObject(response);
			int appStatus = jObj.getInt("status");
			versionUpdateUrl = jObj.getString("url");
			if(appStatus == CONSTANTS.NEW_VERSION_AVAILABLE){

				versionButton.setVisibility(View.VISIBLE);
				versionButton.setText("Download New Version");
			}else if(appStatus == CONSTANTS.NEW_FORMS_AVAILABLE){

				GDKUpdateFormsAsyncTask updateForms = new GDKUpdateFormsAsyncTask(this, this,true);
				updateForms.execute(getString(R.string.FORM_UPDATE_URL),"NO");
			}else if(appStatus == CONSTANTS.LATEST_VERSION){

				versionButton.setVisibility(View.GONE);
				Toast.makeText(this, "No New Updates..", Toast.LENGTH_SHORT).show();
			}else{
				GDKUpdateFormsAsyncTask updateForms = new GDKUpdateFormsAsyncTask(this, this,true);
				updateForms.execute(getString(R.string.FORM_UPDATE_URL),"NO");
			}
		}catch(Exception e){
			e.printStackTrace();
		}
	}	

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		getMenuInflater().inflate(R.menu.main_screen, menu);
		return true;
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		Log.i("bubble","item selected");
		updateBubble();
		putLastActivityData();
		if(item.getItemId() == R.id.action_update){

			GDKUpdateFormsAsyncTask updateForms = new GDKUpdateFormsAsyncTask(this, this,true);
			updateForms.execute(getString(R.string.FORM_UPDATE_URL),"NO");

		}else if(item.getItemId() == R.id.action_unsent){
			
			Intent intent = new Intent(this, UnsentDataListScreen.class);
			startActivity(intent);
		}
		//		Intent intent = new Intent(this, ActivityCapture.class);
		//		startActivityForResult(intent, MainScreen.SCAN_CNIC_REQUEST);

		return super.onOptionsItemSelected(item);
	}


	public void dataSubmitSuccessfully() {
		this.dataSubmitSuccessfullyCB();		

		if(this.Editing)
		{
			finish();
		}
		
	}
	
	public void LockEntry(){
		DataBaseAdapter dbAdapter = new DataBaseAdapter(this);
		dbAdapter.open();
		
		dbAdapter.lockEntry(this.rowId);
		dbAdapter.close();
	
	}
	
	public void setRowId(int rid)
	{
		this.rowId=rid;
	}
	public int getRowId()
	{
		return this.rowId;
	}
	
	
	public void dataSubmitSuccessfullyCB() {
//		
//		if(this.Editing)
//		{
			DataBaseAdapter dbAdapter = new DataBaseAdapter(this);
			dbAdapter.open();
			
			dbAdapter.deleteFormsDataItem(this.rowId);
			dbAdapter.close();
			this.rowId=-1;
//		}
		
		
	}


	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		// Check if the key event was the Back button and if there's history
		Log.i("bubble", "Backbutton handler");
		if ((keyCode == KeyEvent.KEYCODE_BACK) && webView.canGoBack()) {
			
			if(this.showBackAlert && !this.Editing)
			{
				new AlertDialog.Builder(this)
				.setTitle("Info")
				.setMessage("Are you sure you want to back?")
				.setPositiveButton("Yes", new OnClickListener() {
					@Override
					public void onClick(DialogInterface dialog, int which) {
						webView.goBack();
					}
				}).setNegativeButton("No", null).show();
				Log.i("bubble", "Backbutton handler condition 2");
				updateBubble();
				putLastActivityData();
				return true;
			}
			else{
				webView.goBack();
				return true;
			}
			
		}else if(keyCode == KeyEvent.KEYCODE_BACK){
			
			if (!this.Editing){
				new AlertDialog.Builder(this)
				.setTitle("Info")
				.setMessage("Are you sure you want to Exit?")
				.setPositiveButton("Yes", new OnClickListener() {
					@Override
					public void onClick(DialogInterface dialog, int which) {
						MainScreen.this.finish();
					}
				}).setNegativeButton("No", null).show();
			}
			else{
				if(webView.canGoBack())
				{
					webView.goBack();
				}
				else{
					MainScreen.this.finish();	
				}
				
			}
			Log.i("bubble", "Backbutton handler condition 2");
			updateBubble();
			putLastActivityData();
			return true;
		}
		
		return super.onKeyDown(keyCode, event);
	}
	
	

	public File getTempFile(Context context){
	  //it will return /sdcard/image.tmp
	  final File path = new File( Environment.getExternalStorageDirectory(), context.getPackageName() );
	  if(!path.exists()){
	    path.mkdir();
	  }
	  return new File(path, "image.tmp");
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
			else if(S.equalsIgnoreCase("TrackingStatus"))
			{
				value=this.getBaseContext().getResources().getString(R.string.TrackingStatus);
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
//			switch (S) {
//	         case "app_name":
//	             value = this.getBaseContext().getResources().getString(R.string.app_name);
//             break;
//	         case "app_id":
//	             value = this.getBaseContext().getResources().getString(R.string.app_id);
//             break;
//	         case "CHECK_UPDATE_VERSION_URL":
//	             value = this.getBaseContext().getResources().getString(R.string.CHECK_UPDATE_VERSION_URL);
//             break;
//	         case "FORM_SUBMIT_URL":
//	             value = this.getBaseContext().getResources().getString(R.string.FORM_SUBMIT_URL);
//             break;
//	         case "FORM_UPDATE_URL":
//	             value = this.getBaseContext().getResources().getString(R.string.FORM_UPDATE_URL);
//             break;
//	         case "IS_SECURE_APP":
//	             value = this.getBaseContext().getResources().getString(R.string.IS_SECURE_APP);
//             break;
//	         case "showHighResOption":
//	             value = this.getBaseContext().getResources().getString(R.string.showHighResOption);
//             break;
//	         case "PersistImagesOnDevice":
//	             value = this.getBaseContext().getResources().getString(R.string.PersistImagesOnDevice);
//             break;
//	         case "BackgroundUpdate":
//	             value = this.getBaseContext().getResources().getString(R.string.BackgroundUpdate);
//             break;
//	         case "ForceUpdate":
//	             value = this.getBaseContext().getResources().getString(R.string.ForceUpdate);
//             break;
//	         case "action_settings":
//	             value = this.getBaseContext().getResources().getString(R.string.action_settings);
//             break;
//	         case "info":
//	             value = this.getBaseContext().getResources().getString(R.string.info);
//             break;
//	         case "error":
//	             value = this.getBaseContext().getResources().getString(R.string.error);
//             break;
//	         case "data_edit_msg":
//	             value = this.getBaseContext().getResources().getString(R.string.data_edit_msg);
//             break;
//	         case "edit":
//	             value = this.getBaseContext().getResources().getString(R.string.edit);
//             break;
//	         case "cancel":
//	             value = this.getBaseContext().getResources().getString(R.string.cancel);
//             break;
//	         case "yes":
//	             value = this.getBaseContext().getResources().getString(R.string.yes);
//             break;
//	         case "delete":
//	             value = this.getBaseContext().getResources().getString(R.string.delete);
//             break;
//	         case "no":
//	             value = this.getBaseContext().getResources().getString(R.string.no);
//             break;
//	         case "ok":
//	             value = this.getBaseContext().getResources().getString(R.string.ok);
//             break;
//	         case "data_upload_msg":
//	             value = this.getBaseContext().getResources().getString(R.string.data_upload_msg);
//             break;
//	         case "internet_error_msg":
//	             value = this.getBaseContext().getResources().getString(R.string.internet_error_msg);
//             break;
//	         case "button_cancel":
//	             value = this.getBaseContext().getResources().getString(R.string.button_cancel);
//             break;
//	         case "button_ok":
//	             value = this.getBaseContext().getResources().getString(R.string.button_ok);
//             break;
//	         case "button_open_mobi":
//	             value = this.getBaseContext().getResources().getString(R.string.button_open_mobi);
//             break;
//	         case "button_open_license":
//	             value = this.getBaseContext().getResources().getString(R.string.button_open_license);
//             break;
//	         case "menu_ip":
//	             value = this.getBaseContext().getResources().getString(R.string.menu_ip);
//             break;
//	         case "mobi_url":
//	             value = this.getBaseContext().getResources().getString(R.string.mobi_url);
//             break;
//	         case "msg_default_contents":
//	             value = this.getBaseContext().getResources().getString(R.string.msg_default_contents);
//             break;
//	         case "msg_default_format":
//	             value = this.getBaseContext().getResources().getString(R.string.msg_default_format);
//             break;
//	         case "msg_default_status":
//	             value = this.getBaseContext().getResources().getString(R.string.msg_default_status);
//             break;
//	         case "msg_about":
//	             value = this.getBaseContext().getResources().getString(R.string.msg_about);
//             break;
//	         case "msg_buggy":
//	             value = this.getBaseContext().getResources().getString(R.string.msg_buggy);
//             break;
//	         case "msg_camera_framework_bug":
//	             value = this.getBaseContext().getResources().getString(R.string.msg_camera_framework_bug);
//             break;
//	         case "msg_default_type":
//	             value = this.getBaseContext().getResources().getString(R.string.msg_default_type);
//             break;
//	         case "title_about":
//	             value = this.getBaseContext().getResources().getString(R.string.title_about);
//             break;
//	         case "view_description":
//	             value = this.getBaseContext().getResources().getString(R.string.view_description);
//             break;
//	         case "license_url":
//	             value = this.getBaseContext().getResources().getString(R.string.license_url);
//             break;
//	         case "title_activity_main":
//	             value = this.getBaseContext().getResources().getString(R.string.title_activity_main);
//             break;
//	         default:
//	             value="";
//			}
	    
	     
		
		}
		
		return value;
	}


	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		super.onActivityResult(requestCode, resultCode, data);

		if (resultCode == RESULT_OK) {
			if(requestCode == CAMERA_PIC_REQUEST){
				
				
				
				String AppName=getResources().getString(R.string.app_name);
				String resolution=getPictureResolution();
				String PersistImage = this.getBaseContext().getResources().getString(R.string.PersistImagesOnDevice);
				
				
				String[] separated = pictureId.split("-");
				String Caption="caption-"+separated[1];
				if(resolution=="HIGH")
				{
					Log.i("camera","in high res");
					try{	
						final File file = getTempFile(this);
						
//						Bitmap bitmap = (Bitmap) data.getExtras().get("data");
						Bitmap bitmap = Media.getBitmap(getContentResolver(), Uri.fromFile(file) );
//						Bitmap resizedBitmap = Bitmap.createScaledBitmap(bitmap, bitmap.getWidth()*2, bitmap.getHeight()*2, true);
						File photoFile = null;
						FileOutputStream stream;
						String fileName = java.text.DateFormat.getDateTimeInstance().format(Calendar.getInstance().getTime());
						fileName = fileName.replaceAll(" ", "");
						fileName = fileName.replaceAll(":", "");
						fileName = fileName.replaceAll(",", "");
						
						if(Caption!="")
						{
							fileName = fileName + "_"+Caption;
						}
						fileName = fileName + ".jpg";
						
						File imgsFolder = new File(android.os.Environment.getExternalStorageDirectory(), "DCIM/"+AppName+"/HighRes/");
						if (!imgsFolder.exists()) {
							imgsFolder.mkdirs();
						}
						photoFile = new File(imgsFolder, fileName);
						stream = new FileOutputStream(photoFile);
						bitmap.compress(CompressFormat.JPEG, 70, stream);
						
	
						Uri selectedImageUri = Uri.fromFile(photoFile);
						String selectedImagePath = selectedImageUri.getPath();
						BitmapFactory.Options options = new BitmapFactory.Options();
						options.inSampleSize = 2;
//						pictureBytes = Utility.getBytes(resizedBitmap,selectedImagePath);
						
//						System.out.println("--array.length--"+array.length);
						
//					    List<String> list = new ArrayList<String>();
//					    list = Arrays.asList(picturePath);
						
						
					    ArrayList<String> arrayList = new ArrayList<String>();
//					    ImgPathhashTable
					    
					    
//					    for(int i=0;i<picturePath.length;i++)
//					    {
//					    	arrayList.add(picturePath[i]);
//					    }
					    
					    
					    
					    
//						Log.i("IMAGE PATH> "+pictureBytes.length, "<> "+selectedImagePath);
					    addPicturesPathToArray(pictureId, selectedImagePath);
					    
						
//						addPicturesToArray(pictureId, pictureBytes);
						
//						arrayList.add(selectedImagePath);

					    for(String key:ImgPathhashTable.keySet()){
				    	   arrayList.add(ImgPathhashTable.get(key));
				    	}
					    
						
//						picturePath=
						picturePath = arrayList.toArray(new String[arrayList.size()]);
						
//						if(PersistImage.equals("NO"))
//						{
//							photoFile.delete();
//						}
						
						
						webView.loadUrl("javascript:pictureTaken('"+pictureId+"','"+selectedImagePath+"')");
					}catch(Exception e){
						e.printStackTrace();
					}
	
					
					
				}
				else{
					
					try{	
						Bitmap bitmap = (Bitmap) data.getExtras().get("data");
						Bitmap resizedBitmap = Bitmap.createScaledBitmap(bitmap, bitmap.getWidth() * 2, bitmap.getHeight() * 2, true);
						File photoFile = null;
						FileOutputStream stream;
						String fileName = java.text.DateFormat.getDateTimeInstance().format(Calendar.getInstance().getTime());
						fileName = fileName.replaceAll(" ", "");
						fileName = fileName.replaceAll(":", "");
						fileName = fileName.replaceAll(",", "");

						if(Caption!="")
						{
							fileName = fileName + "_"+Caption;
						}
						fileName = fileName + ".jpg";
						
						File imgsFolder = new File(android.os.Environment.getExternalStorageDirectory(), "DCIM/"+AppName+"/LowRes/");
						if (!imgsFolder.exists()) {
							imgsFolder.mkdirs();
						}
						photoFile = new File(imgsFolder, fileName);
						stream = new FileOutputStream(photoFile);
						resizedBitmap.compress(CompressFormat.JPEG, 100, stream);
	
						Uri selectedImageUri = Uri.fromFile(photoFile);
						String selectedImagePath = selectedImageUri.getPath();
						BitmapFactory.Options options = new BitmapFactory.Options();
						options.inSampleSize = 2;
						pictureBytes = Utility.getBytes(resizedBitmap,selectedImagePath);
						


					    ArrayList<String> arrayList = new ArrayList<String>();
//					    for(int i=0;i<picturePath.length;i++)
//					    {
//					    	arrayList.add(picturePath[i]);
//					    }
//					    
					    
//						ArrayList<String> arrayList = new ArrayList<String>();
//					    arrayList.add(selectedImagePath);
	 
					    
						Log.i("IMAGE PATH> "+pictureBytes.length, "<> "+selectedImagePath);
	
						webView.loadUrl("javascript:pictureTaken('"+pictureId+"','"+selectedImagePath+"')");
						addPicturesToArray(pictureId, pictureBytes);
						addPicturesPathToArray(pictureId, selectedImagePath);
//						arrayList.add(selectedImagePath);

						for(String key:ImgPathhashTable.keySet()){
				    	   arrayList.add(ImgPathhashTable.get(key));
				    	}
						    
						picturePath = arrayList.toArray(new String[arrayList.size()]);
//					
//						if(PersistImage.equals("NO"))
//						{
//							photoFile.delete();
//						}
					}catch(Exception e){
						e.printStackTrace();
					}
					
				}
					
			}
			if(requestCode == SCAN_CNIC_REQUEST)
			{
				String result = (String) data.getExtras().get("Result");
				submitData(result);
			}
		}
	}

	//	public String getGPSDateTime(){
	//		
	//		return GPS_TIME;
	//	}


	public void pushTimeToForm(final String timeId, final LocationInfo L){

		Toast.makeText(this, "Time has been taken", Toast.LENGTH_SHORT).show();
		
		webView.post(new Runnable() {
		    @Override
		    public void run() {
		    	
		    	String DATE_TIME=Utility.getFormattedTime(L.getLocationTime());
		    	String dtStart = DATE_TIME;  
		    	Log.e("LOCATION DATA",L.getLocation());
		    	Log.e("LOCATION DATA provider",L.getLocationProvider());
		    	Log.e("LOCATION DATA source",L.getLocationSource());
		    	Log.e("LOCATION DATA time source",L.getTimeSource());
		    	Log.e("LOCATION DATA Accuracy",""+L.getAccuracy());
		    	Log.e("LOCATION DATA Location",""+L.getLocation());
		    	Log.e("LOCATION DATA Lat",""+L.getLatitude());
		    	Log.e("LOCATION DATA Lng",""+L.getLongitude());
		    	Log.e("LOCATION DATA time",""+L.getLocationTime());
		    	
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
		               
		    	} catch (ParseException e) {  
		    	    // TODO Auto-generated catch block  
		    	    e.printStackTrace();  
		    	    
		    	    Date d=new Date();
	            	DATE_TIME=format.format(d.getTime());
		    	}
		    	
		    	Log.i("JS TIMESTAMP", DATE_TIME);
		    	webView.loadUrl("javascript:timeTaken('"+timeId+"','"+DATE_TIME+"','"+L.getLocationProvider()+"')");
		    }
		});	
//		webView.loadUrl("javascript:timeTaken('"+timeId+"','"+DATE_TIME+"')");
	}

	
	/**
	 * Used to send update forms request
	 */
	public void updateForms(boolean isForPlayStore){

		if(isForPlayStore){

			GDKUpdateFormsAsyncTask updateForms = new GDKUpdateFormsAsyncTask(this, this,true);
			if (updateForms.getStatus() == Status.RUNNING){
						
			}
			else{
				updateForms.execute(getString(R.string.FORM_UPDATE_URL),"NO");	
			}
		}else{

			GDKCheckVersionAsyncTask checkVersion = new GDKCheckVersionAsyncTask(this, this);
			checkVersion.execute(getString(R.string.CHECK_UPDATE_VERSION_URL), getString(R.string.app_id), versionCode+"");
			

			GDKUpdateFormsAsyncTask updateForms = new GDKUpdateFormsAsyncTask(this, this,true);
			if (updateForms.getStatus() == Status.RUNNING){
				
			}
			else{
				updateForms.execute(getString(R.string.FORM_UPDATE_URL),"NO");
			}
				
				
			
		}
	}
 
	public void goBackClick(){
		Log.i("backbutton", "Backbutton handler via thread");
		runOnUiThread(new Runnable() {
			@Override
			public void run() {
				updateBubble();
				putLastActivityData();
				webView.goBack();
				
			}
		});
	}

	
	 public static boolean isMockSettingsON(Context context) {
//		 try{
//		  if (Settings.Secure.getString(context.getContentResolver(),Settings.Secure.ALLOW_MOCK_LOCATION).equals("0"))
//		     return false;
//		  else
//		     return true;
//		 } catch (Exception e) {
//			 return false;
//		 }
		 boolean isMock = false;
		 if (android.os.Build.VERSION.SDK_INT >= 18) {
		     // this will be handled in onLocationUpdate
		 } else {
		     isMock = !Settings.Secure.getString(context.getContentResolver(), Settings.Secure.ALLOW_MOCK_LOCATION).equals("0");
		 }
		 return isMock;

	 }
	
	/**
	 * Used to get picture data
	 * @return
	 */
	public byte[] getPictureData(){

		return pictureBytes;
	}

	public void setPictureData(){

		pictureBytes = null;
	}
	
	public String[] getPicturePath(){

		ArrayList<String> arrayList = new ArrayList<String>();
		for(String key:ImgPathhashTable.keySet()){
			String value=ImgPathhashTable.get(key);
			String CleanUri=value.replace("\\", "");
    	   arrayList.add(CleanUri);
    	}
		picturePath = arrayList.toArray(new String[arrayList.size()]);
		return picturePath;
	}

	public void setPicturePath(){

		picturePath = null;
	}

	/**
	 * Used to set picture id on "Take Picture" button click.
	 * @param pictureId
	 */
	public void setPictureId(String pictureId){

		this.pictureId = pictureId;
	}
	
	public void setPictureResolution(String resolution){

		this.pictureResolution = resolution;
	}
	
	public String getPictureResolution(){

		return this.pictureResolution;
	}

	
	/**
	 * Used to store multiple pictures data
	 * @param id
	 * @param pictureData
	 */
	private void addPicturesToArray(String id, byte[] pictureData){

		hashTable.put(id, pictureData);
		Resources.getResources().setMultiplePictureData(hashTable);
		Log.i("PICTURE ARRAY SIZE", id+"<<><><>>"+hashTable.size());
	}
	
	public void addPicturesPathToArray(String id, String pictureData){

		Log.i("IMG ID", id);
		
		try{
//		
		if(id.equalsIgnoreCase(""))
		{
			String[] chunks=pictureData.split("caption-");
			String[] imgIdChunks=chunks[1].split(".jpg");
			id="picselect-"+imgIdChunks[0];
		}
		ImgPathhashTable.put(id, pictureData);
		Resources.getResources().setMultiplePicturePathData(ImgPathhashTable);
		Log.i("PICTURE ARRAY SIZE", id+"<<><><>>"+ImgPathhashTable.size());
		
//		ArrayList<String> arrayList = new ArrayList<String>();
//	    for(String key:ImgPathhashTable.keySet()){
//	    	   arrayList.add(ImgPathhashTable.get(key));
//	    	}
//			picturePath = arrayList.toArray(new String[arrayList.size()]);



		
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}

	}
	
	public void clearImgHashTable(){
		try{
			if(!Editing)
			{
				ImgPathhashTable.clear();
//				ArrayList<String> arrayList = new ArrayList<String>();
//			    for(String key:ImgPathhashTable.keySet()){
//		    	   arrayList.add(ImgPathhashTable.get(key));
//		    	}
//				picturePath = arrayList.toArray(new String[arrayList.size()]);

			}
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
	}
	
	public long getTimeDifference(){
		
		
		if(this.timeTampered)
		{
			return TimeUnit.MINUTES.toMillis(this.timeTamperedAmount);
		}
		else
		{
			SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
			Date CurrentMobileTime=new Date();
			long CurrentTS=new Date().getTime();
			//LocationInfo l=getAccurateLocation();
			getCurrentNetworkTime current = new getCurrentNetworkTime(this.getBaseContext());
			
			
			DataBaseAdapter dba=new DataBaseAdapter(this.getBaseContext());
			dba.open();
			long sntpDeviceTS=dba.getSntpDeviceTS();
			dba.close();
			
			long differenceInTime=0;
			long mobileTimeInMilliseconds = CurrentMobileTime.getTime();
			 
			differenceInTime=mobileTimeInMilliseconds-sntpDeviceTS;
			 
			return differenceInTime;
		}
		
		
	    
	}

	
//	public long getClockDifferenceLocalAndSntp(){
//		
//		
//		SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
//		Date CurrentMobileTime=new Date();
//		long CurrentTS=new Date().getTime();
//		LocationInfo l=getAccurateLocation();
//		getCurrentNetworkTime current = new getCurrentNetworkTime(this.getBaseContext());
//		
//		
//		DataBaseAdapter dba=new DataBaseAdapter(this.getBaseContext());
//		dba.open();
//		long sntpTS=dba.getSntpTS();
//		long sntpDeviceTS=dba.getSntpDeviceTS();
//		dba.close();
//		
//		long differenceInTime=0;
//		 
//		differenceInTime=sntpTS-sntpDeviceTS;
//		 
//		return differenceInTime;	
//	    
//	}


	@Override
	protected void onResume() {
		super.onResume();	
		locationHandler=new LocationHandler(this);
		locationHandler.onResume();
		
//		if(isMockSettingsON(this.getBaseContext()))
//		{
//
//			try {
//			          Log.d("mock" ,"Removing Test providers");
//			          locationManager.removeTestProvider(LocationManager.GPS_PROVIDER);
//			          locationManager.removeTestProvider(LocationManager.NETWORK_PROVIDER);
//			     } catch (IllegalArgumentException error) {
//			          Log.d("mock","Got exception in removing test  provider");
//			     }
//			createMockDisabledAlert();
//
//		}
//
//		locationManager.removeUpdates(LocationListener);
//		locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 10000, 10, LocationListener);
//		locationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER, 10000, 10, LocationListener);
//		updateBubble();
////		putLastActivityData();
//		locationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
//
//		if (!locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER) || !locationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER)){  
//			createGpsDisabledAlert();  
//		}
//		captureUserLocation();
//		LocationInfo l=getAccurateLocation();
	

		DataBaseAdapter dba=new DataBaseAdapter(this.getBaseContext());
		dba.open();
		long systemElapsedTsInDb=dba.getElaspedTS();
		long systemDateAtPause=dba.getElaspedDeviceTS();
		dba.close();
		
		long differenceAtPause=systemDateAtPause-systemElapsedTsInDb;
		
		Log.e("difference at pause",""+differenceAtPause);
		long CurrentElapsed=SystemClock.elapsedRealtime();
		
		long CurrentDeviceTime=new Date().getTime();
		
		long differenceNow=CurrentDeviceTime-CurrentElapsed;
		Log.e("difference now",""+differenceNow);
		
		long totaldiff=differenceNow-differenceAtPause;
		totaldiff = TimeUnit.MILLISECONDS.toMinutes(totaldiff);
		Log.e("time diff value","<<<<>>>>>"+totaldiff);
		this.timeTamperedAmount=totaldiff;
		
		long tolernce=10;
		if(totaldiff<0)
		{
			totaldiff=-1*totaldiff;
		}
		
		
		if(totaldiff<=tolernce)		//tolrence of 10 mins
		{
			Log.e("Tampering","Clock Not Tampered");
			this.timeTampered=false;
		}
		else{
			Log.e("Tampering","Clock Has been Tampered");
			this.timeTampered=true;
		}
		if (!this.Editing)
		{
		File f=new File(Environment.getExternalStorageDirectory().getPath()+"/"+getString(R.string.app_id)+"_"+versionCode+".apk");
		if(f.exists())
		{
			f.delete();
		}
		
		SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(this);
		String DownloadPath = preferences.getString("apkupdate", "");
		Long fs=preferences.getLong("apkupdatesize", 0);
		Log.e("DOWNLOADED APP",DownloadPath);
		Log.e("DOWNLOADED APP Size",""+fs);
		File file = new File(DownloadPath);
        if(file.exists())
        {
	        	long length = file.length();
	        	if(length>=fs)
	        	{
	        		Log.e("Loading from local",DownloadPath);
	//        		String fu=this.getResources().getString(R.string.ForceUpdate);
	        		String fu = ReadSetting("ForceUpdate");
					if(fu.equals("YES"))
					{
						Intent i = new Intent();
				        i.setAction(Intent.ACTION_VIEW);
				        i.setFlags(Intent.FLAG_ACTIVITY_NO_HISTORY);
				        i.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK);
				        
				        i.setFlags(Intent.FLAG_ACTIVITY_NO_ANIMATION);
				        i.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
				        i.setDataAndType(Uri.fromFile(new File(DownloadPath)), "application/vnd.android.package-archive" );
				        Log.d("Lofting", "About to install new .apk");
				        finish();
				        this.startActivity(i);
					}
					
	        	}
	        }
	        
	        String at = ReadSetting("EnableAutoTime");
	        if(at.equalsIgnoreCase("YES"))
	        {
		        if(android.provider.Settings.System.getInt(getContentResolver(), android.provider.Settings.System.AUTO_TIME, 0)==0)
				{
					createTimeFixDisabledAlert();
				}
	        }
	
	
	    	SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(getBaseContext());
	    	String RouteId=prefs.getString("routeId", "");
	    	if(RouteId.equals(""))
	    	{		//meaning tracking is not in progress and there are some points in db, that need to be sent.
	    		sendPendingTrackingData();
	    	}
		}
        
		//captureUserLocation();	
	}
	
	public String GetTrackingStatus(){
		return ReadSetting("TrackingStatus");
	}
	
	public void sendPendingTrackingData(){
        
		if(Utility.isInternetAvailable(this) && !this.processingTrackerPoints)
		{
			
			try{
				submitTrackerPoint submitPoint = new submitTrackerPoint(getBaseContext());
	 			submitPoint.execute(getBaseContext().getString(R.string.TRACKING_URLBulk),"");
			}
			catch(Exception e)
			{
				e.printStackTrace();
			}
			
			
		}
		
//        DataBaseAdapter dbAdapter = new DataBaseAdapter(this.getBaseContext());
//		dbAdapter.open();
//		ArrayList<trackingPoint> trackingarrayList = dbAdapter.readTrackingData();
//		
//		
//		
//		ArrayList<Integer> sentids = new ArrayList<Integer>();
//		if(trackingarrayList!=null && trackingarrayList.size()>0){
//			
//			Log.e("TP","You have "+trackingarrayList.size()+" unsent Tracking Points");
//			JSONArray jsArray = new JSONArray();
//			
//			
//			for (trackingPoint tp : trackingarrayList) 
//			{  
//				JSONObject o=new JSONObject();
//				try {
//					o.putOpt("id", ""+tp.id);
//					sentids.add(tp.id);
//					o.putOpt("location", tp.location);
//					o.putOpt("lat", tp.lat);
//					o.putOpt("lng", tp.lng);
//					o.putOpt("accuracy", tp.accuracy);
//					o.putOpt("altitude", tp.altitude);
//					o.putOpt("speed", tp.speed);
//					o.putOpt("gpsTime", tp.gpsTime);
//					o.putOpt("deviceTS", tp.deviceTS);
//					o.putOpt("imei_no", tp.imei_no);
//					o.putOpt("appId", tp.appId);
//					o.putOpt("routeId", tp.routeId);
//					o.putOpt("distance", tp.distance);
//					o.putOpt("InGeoFence", tp.inGeoFence);
//					o.putOpt("distanceGeo", tp.distanceGeo);
//					jsArray.put(o);
//					
//				} catch (JSONException e) {
//					// TODO Auto-generated catch block
//					e.printStackTrace();
//				}
//				
//				
//			}
//			
//			Log.e("ARRAY SIZE",""+jsArray.length());
//			Log.e("TP","Made the array");
//			
//			if(Utility.isInternetAvailable(this))
//			{
//				
//				submitTrackerPoint submitPoint = new submitTrackerPoint(getBaseContext());
//				submitPoint.execute(getBaseContext().getString(R.string.TRACKING_URLBulk),jsArray.toString());
//				
//				
//			}
//			  
//			dbAdapter.close();
			
			
//		}
	}

	@Override
	protected void onPause() {
		locationHandler.onPause();
//		if(!this.timeTampered)
//		{
			DataBaseAdapter dba=new DataBaseAdapter(this.getBaseContext());
			dba.open();
			dba.SetSystemElapsedTS();
			dba.close();
		//}
		super.onPause();;
	}
	
	
	@Override
	protected void onStop() {
		if(!this.timeTampered)
		{
			DataBaseAdapter dba=new DataBaseAdapter(this.getBaseContext());
			dba.open();
			dba.SetSystemElapsedTS();
			dba.close();
		}
		//locationManager.removeUpdates(LocationListener);
		super.onStop();
	}

	//////////////Location Module//////////////////

	/**
	 * Used to make request for location listener
	 */
//	public void captureUserLocation(){
//		Log.i("Location","Capturing info again");
//		locationManager.removeUpdates(LocationListener);
//		locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 0, 0, LocationListener);
//		locationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER, 0, 0, LocationListener);
//	}

	/**
	 * Used to get callback for location
	 */
	
	private final LocationListener LocationListener = new LocationListener(){

		@TargetApi(Build.VERSION_CODES.JELLY_BEAN_MR2) @Override
		public void onLocationChanged(Location location) {
			boolean isMock = false;
			if (Build.VERSION.SDK_INT >= 18) {
			    isMock = location.isFromMockProvider();
			    if(isMock)
			    {
			    	createMockDisabledAlert();
			    }
			    
			}
			
		}
		@Override
		public void onProviderDisabled(String provider){

		}
		@Override
		public void onProviderEnabled(String provider){

		}
		@Override
		public void onStatusChanged(String provider, int status, Bundle extras){

		}
	};

	/**
	 * Used to get best possible location
	 * @return
	 */
	
	
//	public LocationInfo getAccurateLocation(){
//		
//		Log.i("Location","Getting Accurate Location");
//		
//		locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER, 1000, 100, LocationListener);
//		locationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER, 1000, 100, LocationListener);
//		
//
//		LocationInfo locationInfo = new LocationInfo();
//		DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
//		Calendar cal = Calendar.getInstance();	
//		cal.setTimeZone(TimeZone.getTimeZone("Asia/Karachi"));
//		
//		
//		int SystemSeconds = cal.get(Calendar.SECOND);
//		
//		gpsLocation = locationManager.getLastKnownLocation(LocationManager.GPS_PROVIDER);
//		networkLocation = locationManager.getLastKnownLocation(LocationManager.NETWORK_PROVIDER);
//		
//		
//		
//		Log.e("gps",""+gpsLocation);
//		Log.e("network",""+networkLocation);
//
//		getCurrentNetworkTime current = new getCurrentNetworkTime(this.getBaseContext());
//		Log.i("SNTP TIME",""+current.execute());
//		Log.i("SNTP TIME",""+current.now);
//		
//		SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(this.getBaseContext());
//		long NowMillis=prefs.getLong("nowMillis", 0);
//		
//		LocationInfo betterLocation = null;
//		
//		if(gpsLocation != null){
//			
//			cal.setTimeInMillis(gpsLocation.getTime());
//			
//			Log.e("GPS TIME", "<><> "+ df.format(cal.getTime()));
//			accurateLocation = gpsLocation.getLatitude() + "," + gpsLocation.getLongitude();
//			locationInfo.setLocation(accurateLocation);
//			locationInfo.setLocationSource("gps");
//			locationInfo.setTimeSource("gps");
////			DATE_TIME = df.format(NowMillis);
//			DATE_TIME = df.format(cal.getTime());
//
//			locationInfo.setLocationTime(DATE_TIME);
//			return locationInfo;
//		}
//		else if(networkLocation != null){
//			cal.setTimeInMillis(networkLocation.getTime());
//			Log.e("Network TIME", "<><> "+ df.format(cal.getTime()));
//			DATE_TIME = df.format(cal.getTime());
//			accurateLocation = networkLocation.getLatitude() + "," + networkLocation.getLongitude();
//			locationInfo.setLocation(accurateLocation);
//			locationInfo.setLocationSource("network");
//			locationInfo.setTimeSource("network");
//
//			locationInfo.setLocationTime(DATE_TIME);
//			return locationInfo;
//		}else{
//			
////			Date d=new Date();
////			cal.setTimeInMillis(d.getTime());
////			;
//			DATE_TIME = df.format(System.currentTimeMillis());
//			locationInfo.setLocationTime(DATE_TIME);
//			locationInfo.setLocation("0,0");
//			locationInfo.setLocationSource("device");
//			locationInfo.setTimeSource("device");
//
//			return locationInfo;
//		}	
//	}

//	/**
//	 * Used to calculate for better location
//	 * @param newLocation
//	 * @param currentBestLocation
//	 * @return
//	 */
//	protected LocationInfo getBetterLocation(Location newLocation, Location currentBestLocation) {
//		LocationInfo locationInfo = new LocationInfo();
//		if (currentBestLocation == null) {
//			// A new location is always better than no location
//			accurateLocation = newLocation.getLatitude() + "," + newLocation.getLongitude();
//			locationInfo.setLocation(accurateLocation);
//			locationInfo.setLocationObj(newLocation);
//			locationInfo.setLocationSource("network");
//			locationInfo.setTimeSource("network");
//			return locationInfo;
//		}
//		// Check whether the new location fix is newer or older
//		long timeDelta = newLocation.getTime() - currentBestLocation.getTime();
//		boolean isSignificantlyNewer = timeDelta > (1000 * 60 * 2);
//		boolean isSignificantlyOlder = timeDelta < -(1000 * 60 * 2);
//		boolean isNewer = timeDelta > 0;
//		// If it's been more than two minutes since the current location, use the new location
//		// because the user has likely moved.
//		if (isSignificantlyNewer) {
//			accurateLocation = newLocation.getLatitude() + "," + newLocation.getLongitude();
//			locationInfo.setLocation(accurateLocation);
//			locationInfo.setLocationObj(newLocation);
//			locationInfo.setLocationSource("network");
//			locationInfo.setTimeSource("network");
//			return locationInfo;
//			// If the new location is more than two minutes older, it must be worse
//		} else if (isSignificantlyOlder) {
//			accurateLocation = currentBestLocation.getLatitude() + "," + currentBestLocation.getLongitude();
//			locationInfo.setLocation(accurateLocation);
//			locationInfo.setLocationObj(currentBestLocation);
//			locationInfo.setLocationSource("gps");
//			locationInfo.setTimeSource("gps");
//			return locationInfo;
//		}
//		// Check whether the new location fix is more or less accurate
//		int accuracyDelta = (int) (newLocation.getAccuracy() - currentBestLocation.getAccuracy());
//		boolean isLessAccurate = accuracyDelta > 0;
//		boolean isMoreAccurate = accuracyDelta < 0;
//		boolean isSignificantlyLessAccurate = accuracyDelta > 200;
//		// Check if the old and new location are from the same provider
//		boolean isFromSameProvider = isSameProvider(newLocation.getProvider(),currentBestLocation.getProvider());
//		// Determine location quality using a combination of timeliness and accuracy
//		if (isMoreAccurate) {
//			accurateLocation = newLocation.getLatitude() + "," + newLocation.getLongitude();
//			locationInfo.setLocation(accurateLocation);
//			locationInfo.setLocationObj(newLocation);
//			locationInfo.setLocationSource("network");
//			locationInfo.setTimeSource("network");
//			return locationInfo;
//		} else if (isNewer && !isLessAccurate) {
//			accurateLocation = newLocation.getLatitude() + "," + newLocation.getLongitude();
//			locationInfo.setLocation(accurateLocation);
//			locationInfo.setLocationObj(newLocation);
//			locationInfo.setLocationSource("network");
//			locationInfo.setTimeSource("network");
//			return locationInfo;
//		} else if (isNewer && !isSignificantlyLessAccurate && isFromSameProvider) {
//			accurateLocation = newLocation.getLatitude() + "," + newLocation.getLongitude();
//			locationInfo.setLocation(accurateLocation);
//			locationInfo.setLocationObj(newLocation);
//			locationInfo.setLocationSource("network");
//			locationInfo.setTimeSource("network");
//			return locationInfo;
//		}
//		accurateLocation = currentBestLocation.getLatitude() + "," + currentBestLocation.getLongitude();
//		locationInfo.setLocation(accurateLocation);
//		locationInfo.setLocationObj(currentBestLocation);
//		locationInfo.setLocationSource("gps");
//		locationInfo.setTimeSource("gps");
//		return locationInfo;
//	}

//	private boolean isSameProvider(String provider1, String provider2) {
//		if (provider1 == null){
//			return provider2 == null;
//		}
//
//		return provider1.equals(provider2);
//	}


	/**
	 * Used to handle page duplication issue
	 */

	/*private class MyWebViewClient extends WebViewClient {

		@Override
	    public boolean shouldOverrideUrlLoading(WebView view, String url) {
	    	Log.i("shouldOverrideUrlLoading URL", "<><>"+url);
	        return true;
		}

		@Override
		public void onPageStarted(WebView view, String url, Bitmap favicon) {
			super.onPageStarted(view, url, favicon);			
			Log.i("onPageStarted URL", "<><>"+url);
			Log.i("CURRENT URL", "<><>"+currentUrl);

			 if(currentUrl != null && url != null && url.equals(currentUrl)) {
                 webView.goBack();
                 return;
             }
             //view.loadUrl(url);
             currentUrl = url;
		}
	}*/

	/**
	 * Used to show GPS alert message
	 */
	private void createGpsDisabledAlert(){  

		if(alert == null){

			AlertDialog.Builder builder = new AlertDialog.Builder(this);  
			builder.setMessage("Please enable location services before you proceed.")  
			.setCancelable(false)  
			.setPositiveButton("Enable",  new DialogInterface.OnClickListener(){  
				public void onClick(DialogInterface dialog, int id){  
					alert.dismiss();
					alert = null;
					showGpsOptions();  			
				}  
			});  
			alert = builder.create();  
			alert.show(); 
		}
	}
	
	
	private void createTimeFixDisabledAlert(){  

		if(alert == null){

			AlertDialog.Builder builder = new AlertDialog.Builder(this);  
			builder.setMessage("Please enable Auto Time in the clock settings.")  
			.setCancelable(false)  
			.setPositiveButton("Enable",  new DialogInterface.OnClickListener(){  
				public void onClick(DialogInterface dialog, int id){  
					alert.dismiss();
					alert = null;
					showTimeOptions();  			
				}  
			});  
			alert = builder.create();  
			alert.show(); 
		}
	}
	
	private void createMockDisabledAlert(){  

		if(alert == null){
			
			AlertDialog.Builder builder = new AlertDialog.Builder(this);  
			builder.setMessage("Please Disable mock locations in the phone to continue using this app")  
			.setCancelable(false)
			.setPositiveButton("Disable",  new DialogInterface.OnClickListener(){  
				public void onClick(DialogInterface dialog, int id){  
					alert.dismiss();
					alert = null;
					showDeveloperOptions();  			
				}  
			});  
			alert = builder.create();  
			alert.show(); 
		}
	}

	/**
	 * Used to launch settings
	 */
	private void showDeveloperOptions(){  

		Intent gpsOptionsIntent = new Intent(android.provider.Settings.ACTION_APPLICATION_DEVELOPMENT_SETTINGS);
		startActivity(gpsOptionsIntent);  
	}
	private void showGpsOptions(){  

		Intent gpsOptionsIntent = new Intent(android.provider.Settings.ACTION_LOCATION_SOURCE_SETTINGS);
		startActivity(gpsOptionsIntent);  
	}
	
	private void showTimeOptions(){  

		Intent dateTimeIntent = new Intent(android.provider.Settings.ACTION_DATE_SETTINGS);
		startActivity(dateTimeIntent);  
	}

	public void submitData(final String res)
	{
		Log.i("Scan Result", "Data : "+res);
		webView.post(new Runnable() {
			@Override
			public void run() {
				webView.loadUrl("javascript:afterScanCnic('"+res+"')");//afterScanCnic(json_val)
			}
		});	
	}

}
