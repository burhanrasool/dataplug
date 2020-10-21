//package com.manateeworks.CNICScanner;
//
//import java.io.InputStream;
//import java.net.URLEncoder;
//import java.nio.charset.Charset;
//import java.util.ArrayList;
//
//import org.apache.http.HttpResponse;
//import org.apache.http.client.HttpClient;
//import org.apache.http.client.methods.HttpGet;
//import org.apache.http.client.methods.HttpPost;
//import org.apache.http.entity.mime.HttpMultipartMode;
//import org.apache.http.entity.mime.MultipartEntity;
//import org.apache.http.entity.mime.content.StringBody;
//import org.apache.http.impl.client.DefaultHttpClient;
//import org.apache.http.params.CoreConnectionPNames;
//import org.apache.http.params.HttpParams;
//import org.apache.http.util.EntityUtils;
//import org.json.JSONArray;
//import org.json.JSONException;
//import org.json.JSONObject;
//
//import android.app.Activity;
//import android.app.ProgressDialog;
//import android.content.Context;
//import android.content.Intent;
//import android.graphics.Color;
//import android.graphics.Typeface;
//import android.graphics.drawable.ColorDrawable;
//import android.graphics.drawable.StateListDrawable;
//import android.net.ConnectivityManager;
//import android.net.NetworkInfo;
//import android.os.Bundle;
//import android.os.Handler;
//import android.os.Message;
//import android.telephony.TelephonyManager;
//import android.util.Log;
//import android.view.Display;
//import android.view.View;
//import android.view.View.OnClickListener;
//import android.widget.Button;
//import android.widget.ImageView;
//import android.widget.LinearLayout;
//import android.widget.RelativeLayout;
//import android.widget.Toast;
//
//import com.manateeworks.database.DbAdapter;
//
//public class MainActivity extends Activity {
//
//	private RelativeLayout layoutHeader = null;
//	private ImageView imageViewIcon = null;
//	private ImageView imageViewHeaderText = null;
//	private Button buttonScan = null;
//
//	private ArrayList<ClassUser> listUsers = null;
//	private String ResponseMessage = null;
//	private int ResponseAction = 0;
//	private ProgressDialog progressDialog = null;
//	private Context contextActivity = null;
//	private final int CODE_SYNC_SUCCESS = 2;
//	private final int CODE_SYNC_ERROR = 3;
//	private DbAdapter Database = null;
//	private int screenWidth = 0;
//	private int screenHeight = 0;
//
//	/*** UI ***/
//	private String buttonBgColorOn = "#03487e";
//	private String buttonBgColorOff = "#127fd6";
//	private String buttonTextColor = "#ffffff";
//	private float textSize = 20;
//	String IsRegistered="";
//	@Override
//	protected void onCreate(Bundle savedInstanceState) {
//		super.onCreate(savedInstanceState);
//		setContentView(R.layout.activity_main);
//		initApp();
//	}
//	private void initApp(){
//		contextActivity = MainActivity.this;
//		Database = new DbAdapter(contextActivity);
//
//		Display display = getWindowManager().getDefaultDisplay();
//		screenWidth = display.getWidth();
//		screenHeight= display.getHeight();
//		//if(!Database.isApplicationInitialized())
//		IsRegistered=Database.IsRegistered();
//		if(!IsRegistered.equals(""))
//			perfromRefresh();
//		
//		generateBody();
//	}
//	
//	@Override
//	protected void onResume() {
//		super.onResume();
//		resetPreviousData();	
//	}
//	
//	private void generateBody(){
//		buttonScan = (Button) findViewById(R.id.button_scan);
//		buttonScan.setOnClickListener(new OnClickListener() {
//			@Override
//			public void onClick(View view) {
//				
//				/*Intent intent = new Intent(MainActivity.this, ListViewActivity.class);
//				startActivity(intent);*/
//				 
//				Intent intent = new Intent(MainActivity.this, ActivityCapture.class);
//				if(!IsRegistered.equals(""))
//					ActivityCapture.CODE_ACTIVITY = ActivityCapture.CODE_QR;
//				else
//					ActivityCapture.CODE_ACTIVITY = ActivityCapture.CODE_REGISTER;
//				startActivity(intent);
//			}
//		});
//		imageViewIcon = (ImageView) findViewById(R.id.imageview_scan);
//
//		LinearLayout.LayoutParams ParamsimageViewIcon = new LinearLayout.LayoutParams((int)(screenWidth * .50), LinearLayout.LayoutParams.WRAP_CONTENT);
//		ParamsimageViewIcon.setMargins(0,(int)(screenHeight * .02),0, (int)(screenHeight * .01));
//		imageViewIcon.setLayoutParams(ParamsimageViewIcon);
//
//		LinearLayout.LayoutParams paramsButtonScan = new LinearLayout.LayoutParams((int)(screenWidth * .60), (int)(screenWidth * .15));
//		paramsButtonScan.setMargins(0, 0, 0, 0);
//		buttonScan.setLayoutParams(paramsButtonScan);
//		buttonScan.setLayoutParams(paramsButtonScan);
//		buttonScan.setTextColor(Color.parseColor(buttonTextColor));
//		buttonScan.setTypeface(Typeface.DEFAULT_BOLD);
//		StateListDrawable statesButtonScan = new StateListDrawable();
//		statesButtonScan.addState(new int[] {android.R.attr.state_pressed}, new ColorDrawable(Color.parseColor(buttonBgColorOn)));
//		statesButtonScan.addState(new int[] {}, new ColorDrawable(Color.parseColor(buttonBgColorOff)));
//		buttonScan.setBackgroundDrawable(statesButtonScan);
//		
//		if(IsRegistered.equals(""))
//			buttonScan.setText("اپنے آپ کو رجسٹر کروایں");
//		else
//			buttonScan.setText("کارڈ سکین کریں");
//			
//	}
//	@Override
//	public void onBackPressed() {
//		finish();
//	}
//	private Handler handler = new Handler() {
//		@Override
//		public void handleMessage(Message msg) {
//			Toast.makeText(getApplicationContext(), ResponseMessage, Toast.LENGTH_LONG).show();
//		}
//	};
//	private boolean isInternetAvailable(){
//		ConnectivityManager connectivityManager = (ConnectivityManager) getApplicationContext().getSystemService(Context.CONNECTIVITY_SERVICE);
//		NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
//		return activeNetworkInfo != null && activeNetworkInfo.isConnected();
//	}
//	private void perfromRefresh(){
//		if(isInternetAvailable()){
//			progressDialog = ProgressDialog.show(contextActivity, "", "Please wait...");
//			new Thread(){
//				public void run(){
//					try{
//						sendUnsent();
//						SyncAppData();
//					}
//					catch (Exception e){
//						Log.e("API",e.getMessage());
//					}
//					progressDialog.dismiss();
//					handler.sendEmptyMessage(0);
//				}
//			}
//			.start();
//		}
//		else{
//			Toast.makeText(getApplicationContext(), ("Internet not Available. Please Connect Internet and try again !!"), Toast.LENGTH_SHORT).show();
//		}
//	}
//	private void sendUnsent(){
//		ArrayList<ClassUser> listUnsent = Database.selectRegisteredUser();
//		for(ClassUser obj: listUnsent){
//			if(performSubmit(obj.Name).contains("true")){
//				Database.deleteRegisteredUser(obj.activity_id);
//			}
//		}
//	}
//	private String performSubmit(String jsonData) {
//		String apiUrl = "http://vvisapp.pitb.gov.pk/api/saveuser";
//		HttpClient Http_Client = new DefaultHttpClient();
//		HttpParams httpParams = Http_Client.getParams();
//		httpParams.setIntParameter(CoreConnectionPNames.SO_TIMEOUT, 160000);
//		MultipartEntity multipartEntity = new MultipartEntity(HttpMultipartMode.BROWSER_COMPATIBLE);
//		HttpPost httpPost = new HttpPost(apiUrl);
//		try{
//	
//			TelephonyManager telephonyManager = (TelephonyManager)getSystemService(Context.TELEPHONY_SERVICE);
//			multipartEntity.addPart("imei_number", new StringBody(telephonyManager.getDeviceId()));
//			Charset chars = Charset.forName("UTF-8");
//			multipartEntity.addPart("users",new StringBody(jsonData, chars));
//			httpPost.setEntity(multipartEntity);
//			HttpResponse httpResponse = Http_Client.execute(httpPost);
//			return EntityUtils.toString(httpResponse.getEntity());
//		}
//		catch (Exception e){
//			e.printStackTrace();
//			return "Error";
//		}
//	}
//	private void SyncAppData() throws Exception{
//		InputStream is = null;
//		StringBuilder sb=null;
//		String resulted = null;
//		try{
//			String apiUrl ="http://vvisapp.pitb.gov.pk/api/sync_users";
//			HttpClient httpclient = new DefaultHttpClient();
//			TelephonyManager telephonyManager = (TelephonyManager)getSystemService(Context.TELEPHONY_SERVICE);
//			String url = String.format("%s?imei_no=%s&last_sync_time=%s", apiUrl, telephonyManager.getDeviceId(),URLEncoder.encode(Database.SelectDate(), "UTF-8"));	
//			//String url = String.format("%s?imei_no=%s&last_sync_time=%s", apiUrl,"35202456123123",URLEncoder.encode(Database.SelectDate(), "UTF-8"));
//			//String url = "http://vvisapp.pitb.gov.pk/api/sync_users?imei_no=35202456123123&last_sync_time=2014-04-10";
//			HttpGet httpget = new HttpGet(url);
//
//			httpclient.getConnectionManager();
//			HttpResponse response = httpclient.execute(httpget);
//			resulted = EntityUtils.toString(response.getEntity());
//			JSONObject json_data = new JSONObject(resulted);
//			if(json_data.getString("status").equals("true")){
//				String jsonResult = json_data.getString("users");
//				String SyncDate = json_data.getString("last_sync_time");
//				String jsonHouseNoVehicle = json_data.getString("houses_vehicles");
//				Database.DeleteDate();
//				Database.insertSyncDate(SyncDate);
//				//Database.resetUserData();
//				populateRawMaterial(jsonResult);
//				populateHouseNoVehicleNo(jsonHouseNoVehicle);
//				ResponseAction = CODE_SYNC_SUCCESS;
//				//ResponseMessage = json_data.getString("message");
//				ResponseMessage = "Synced Successfully";
//			}
//			else if(json_data.getString("status").equals("false") && json_data.getString("message").equals("No Data Found")){
//				ResponseAction = CODE_SYNC_SUCCESS;
//				String SyncDate = json_data.getString("last_sync_time");
//				Database.DeleteDate();
//				Database.insertSyncDate(SyncDate);
//				//ResponseMessage = json_data.getString("message");
//				ResponseMessage = "Synced Successfully";
//			}
//			else{
//				ResponseAction = CODE_SYNC_ERROR;
//				//ResponseMessage = json_data.getString("message");
//				//ResponseMessage = "Please register your IMEI before using application";
//				ResponseMessage = "Your activation is pending";
//			}
//		}
//		catch(Exception ex){
//			ResponseAction = CODE_SYNC_ERROR;
//			ResponseMessage ="Error Occurred... Please Try Again Later";
//		}
//	}
//	private void populateRawMaterial(String jsonResult) throws JSONException{
//		JSONArray jArray = new JSONArray(jsonResult);
//		for(int i = 0; i < jArray.length(); i++){
//			ClassUser object = new ClassUser();
//			JSONObject jsonObject = jArray.getJSONObject(i);
//			object.Name = jsonObject.getString("Name").trim();	
//			object.Father_Name = jsonObject.getString("Father_Name").trim();	
//			object.IDno = jsonObject.getString("IDno").trim();	
//			object.Date_Of_Birth = jsonObject.getString("Date_Of_Birth").trim();	
//			object.Address = jsonObject.getString("Address").trim();	
//			object.District = jsonObject.getString("District").trim();	
//			object.City = jsonObject.getString("City").trim();	
//			object.Family_No = jsonObject.getString("Family_No").trim();	
//			object.House_No = jsonObject.getString("House_No").trim();	
//			object.Vehicle_No = jsonObject.getString("Vehicle_No").trim();	
//			object.Vehicle_Color = jsonObject.getString("Vehicle_Color").trim();
//			object.Vehicle_Brand = jsonObject.getString("Vehicle_Brand").trim();
//			object.User_Type = jsonObject.getString("Type").trim();
//			if(!Database.IsUserExists(object))
//				Database.insertUser(object);
//		}
//	}
//	private void populateHouseNoVehicleNo(String jsonResult) throws JSONException{
//		JSONArray jArray = new JSONArray(jsonResult);
//		Database.resetUserHouseVehicle();
//		for(int i = 0; i < jArray.length(); i++){
//			ClassHouseVehicle object = new ClassHouseVehicle();
//			JSONObject jsonObject = jArray.getJSONObject(i);
//		
//			object.House_No = jsonObject.getString("house_no").trim();	
//			object.Vehicle_No = jsonObject.getString("vehicle_no").trim();	
//			Database.insertHouseVehicle(object);
//		}
//	}
//	public void resetPreviousData() {
//		ListViewActivity.listSearchUsersGuest.clear();
//		ListViewActivity.listSearchUsersResidence.clear();
//		ListViewActivity.listSearchUsersServant.clear();
//		ListViewActivity.SELECTED_TYPE=0;
//		ListViewActivity.VehicleData = null;
//	}
//}