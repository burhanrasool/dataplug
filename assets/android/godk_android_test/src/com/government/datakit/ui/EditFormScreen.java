package com.government.datakit.ui;

import org.json.JSONException;
import org.json.JSONObject;

import com.government.datakit.utils.LocationHandler;
import com.government.datakit.utils.Utility;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.util.Log;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;

/**
 * This is the edit activity which holds the webview to edit
 * some optional HTML contents/forms to user. 
 * @author Zoe
 */

public class EditFormScreen extends Activity{

	private WebView webView;
	AlertDialog alert;
	String formData;
	private boolean visited;
	private int auto_save;
	private int rowId;
	private String form_name="index.html";
	public static UnsentDataListScreen context;
	public static MainScreen MScontext;
	public static String htmlFilesDirectory;
	public String ImageData;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.main_screen);
		webView = (WebView)findViewById(R.id.webview);
		WebSettings webSettings = webView.getSettings();
		webSettings.setJavaScriptEnabled(true);
		webView.clearCache(true);
		htmlFilesDirectory = "APPID"+getString(R.string.app_id);
		visited=false;
		
		final JavaScriptInterface myJavaScriptInterface = new JavaScriptInterface(this);
		webView.addJavascriptInterface(myJavaScriptInterface, "AndroidFunction");
		Bundle extras = getIntent().getExtras(); 
		auto_save=0;
		if (extras != null) {
			formData = extras.getString("form_data");
//			
			ImageData= extras.getString("picture_data","");
			auto_save = extras.getInt("auto_save",0);
			rowId = extras.getInt("rowId",-1);
			try{
				form_name=new JSONObject(formData).getString("landing_page");
			}catch(Exception e){
				e.printStackTrace();
			}
			
			webView.loadUrl("file:///"+this.getFilesDir().getPath() + "/"+htmlFilesDirectory + "/" + form_name);
			
							
		}
		webView.setWebViewClient(new WebViewClient() {
			
			public void onPageFinished(WebView view, String url) {
				if(!visited){
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


	
	public void dataSubmitSuccessfully() {
		((UnsentDataListScreen)context).dataSubmitSuccessfully();		

		
		//		Intent intent = new Intent(this, UnsentDataListScreen.class);
		//	    startActivity(intent);
		finish();
		
	}

}
