package com.government.datakit.db;

import java.util.ArrayList;
import java.util.Date;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.annotation.SuppressLint;
import android.annotation.TargetApi;
import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.DatabaseUtils;
import android.database.SQLException;
import android.database.sqlite.SQLiteDatabase;
import android.os.Build;
import android.os.SystemClock;
import android.util.Log;

import com.government.datakit.bo.FormsDataInfo;
import com.government.datakit.bo.trackingPoint;

/**
 * This class is used to manage all Database operations.
 * @author gulfamhassan
 *
 */
public class DataBaseAdapter{

	private Context context;
	private SQLiteDatabase database;
	private DataBaseHelper dbHelper;

	public static final String COLUMN_NAME_ID 					= "_id";
	public static final String COLUMN_FORM_ID 					= "formID";
	public static final String COLUMN_NAME_FORMDATA				= "forms_data";
	public static final String COLUMN_NAME_DATETIME 			= "date";
	public static final String COLUMN_NAME_LOCATION 			= "location";
	public static final String COLUMN_NAME_IMAGEARRAY 			= "image_array";
	public static final String COLUMN_NAME_LOCATION_SOURCE 		= "location_source";
	public static final String COLUMN_NAME_TIME_SOURCE 			= "time_source";
	public static final String COLUMN_NAME_AUTO_SAVE 			= "auto_save";
	public static final String COLUMN_FORM_ICON_NAME 			= "icon_name";

	public static final String FORMS_DATA_TABLE 				= "forms_data_table";
	public static final String FORMS_LAST_DATA_TABLE 				= "forms_last_data_table";


	public static final String TEMP_TIME_TABLE = "temp_time_table";
	public static final String COLUMN_NAME_TIME_ID = "time_id";
	public static final String COLUMN_NAME_DATETIME_VALUE = "date_time_value";
	public static final String COLUMN_NAME_STATUS_VALUE = "status_value";
	
	private final String[] FORMSDATA_PROJECTION = new String[]{COLUMN_NAME_ID, COLUMN_NAME_FORMDATA, COLUMN_NAME_DATETIME, COLUMN_NAME_LOCATION, COLUMN_NAME_IMAGEARRAY, COLUMN_NAME_LOCATION_SOURCE, COLUMN_NAME_TIME_SOURCE, COLUMN_NAME_AUTO_SAVE,COLUMN_FORM_ICON_NAME};
	private final String[] TEMPTIME_PROJECTION = new String[]{COLUMN_NAME_ID, COLUMN_NAME_TIME_ID, COLUMN_NAME_DATETIME_VALUE,COLUMN_NAME_STATUS_VALUE};
	private final String[] TRACKING_PROJECTION = new String[]{"_id", "location", "lat","lng","accuracy","altitude","speed","gpsTime","deviceTS","imei_no","appId","routeId","distance","inGeoFence","distanceGeo"};

	public DataBaseAdapter(Context context){
		this.context = context;
		dbHelper = new DataBaseHelper(this.context);
	}

	public DataBaseAdapter open() throws SQLException{
		database = dbHelper.getWritableDatabase();
		return this;
	}

	public void close(){
		dbHelper.close();
	}

	/**
	 * Used to insert data in database
	 * @param formsData
	 * @param date
	 * @param location
	 * @param pictureData
	 * @param time_source 
	 * @param location_source 
	 * @param autoSave
	 */
	public long insertFormsData(String formsData, String date,  String location, String[] pictureData, String location_source, String time_source, int autoSave, String formIconName){

		ContentValues values = new ContentValues();
		values.put(COLUMN_NAME_FORMDATA, formsData);
		values.put(COLUMN_NAME_DATETIME, date);
		values.put(COLUMN_NAME_LOCATION, location);
		
		if(pictureData!=null)
		{
			if(pictureData.length>0)
			{
				JSONArray jsArray;
				//				jsArray = new JSONArray(pictureData);
				jsArray = new JSONArray();
				for(int i=0;i<pictureData.length;i++)
				{
					jsArray.put(pictureData[i]);
				}
				
	//				jsArray.p
				
				Log.e("SAVING THESE IAMGES FOR LATER ACCESS",jsArray.toString());
				values.put(COLUMN_NAME_IMAGEARRAY, jsArray.toString());
			}
		}
		
		values.put(COLUMN_NAME_LOCATION_SOURCE, location_source);
		values.put(COLUMN_NAME_TIME_SOURCE, time_source);
		values.put(COLUMN_NAME_AUTO_SAVE, autoSave);
		values.put(COLUMN_FORM_ICON_NAME, formIconName);
		
//		String CheckWhere=COLUMN_NAME_FORMDATA+" = '"+formsData+"'";
//		String[] checkcolumns=new String[]{COLUMN_NAME_FORMDATA};
//		Cursor checkcursor = database.query(FORMS_DATA_TABLE, checkcolumns, CheckWhere, null, null, null, null);
//		
		long row_id=-1;
//		if(checkcursor.getCount()==0)
//		{
			row_id = database.insert(FORMS_DATA_TABLE, null, values);	
//		}
//		else{
//			while(checkcursor.moveToNext())
//			{
//				long rowId=checkcursor.getInt(checkcursor.getColumnIndexOrThrow(COLUMN_NAME_ID));
//				
//				this.convertFormFromDraftToFinal(rowId,1);
//			}
//		}
//		
		
		
		try {
			JSONObject jObject = new JSONObject(formsData);
			String formId = jObject.getString("form_id");
			values.put(COLUMN_FORM_ID, formId);
			
			String where = COLUMN_FORM_ID+" = "+formId;
			String[] columns=new String[]{COLUMN_FORM_ID,COLUMN_NAME_FORMDATA};
			Cursor cursor = database.query(FORMS_LAST_DATA_TABLE, columns, where, null, null, null, null);

			if(cursor.getCount()==0){

			long lastid=database.insert(FORMS_LAST_DATA_TABLE, null, values);
			
			}else{
				ContentValues v = new ContentValues();
				v.put(COLUMN_NAME_FORMDATA, formsData);
				int st=database.update(FORMS_LAST_DATA_TABLE, v, where, null);	
				Log.e("updated Last entry or not?", st+";;");
				
			}
			
			
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
		
		
		
		
		
		
		return row_id;
	}
	


	/**
	 * Used to insert data in database
	 * @param formsData
	 * @param date
	 * @param location
	 * @param pictureData
	 * @param time_source 
	 * @param location_source 
	 * @param autoSave
	 */
	public long updateFormsData(long rowId, String formsData, String date,  String location, String[] pictureData, String location_source, String time_source, int autoSave, String formIconName){

		ContentValues values = new ContentValues();
		values.put(COLUMN_NAME_FORMDATA, formsData);
		values.put(COLUMN_NAME_DATETIME, date);
		values.put(COLUMN_NAME_LOCATION, location);
		
		if(pictureData!=null)
		{
			if(pictureData.length>0)
			{
				JSONArray jsArray;
				//				jsArray = new JSONArray(pictureData);
				jsArray = new JSONArray();
				for(int i=0;i<pictureData.length;i++)
				{
					jsArray.put(pictureData[i]);
				}
				
	//				jsArray.p
				
				Log.e("SAVING THESE IAMGES FOR LATER ACCESS",jsArray.toString());
				values.put(COLUMN_NAME_IMAGEARRAY, jsArray.toString());
			}
		}
		
		values.put(COLUMN_NAME_LOCATION_SOURCE, location_source);
		values.put(COLUMN_NAME_TIME_SOURCE, time_source);
		values.put(COLUMN_NAME_AUTO_SAVE, autoSave);
		values.put(COLUMN_FORM_ICON_NAME, formIconName);
		String where = COLUMN_NAME_ID+" = "+rowId;
		long row_id = database.update(FORMS_DATA_TABLE, values, where,null);
		
		
		return row_id;
	}
	
	


	/**
	 * Used to insert data in database
	 * @param formsData
	 * @param date
	 * @param location
	 * @param pictureData
	 * @param time_source 
	 * @param location_source 
	 * @param autoSave
	 */
	public long lockEntry(long rowId){
		long row_id=-1;
		try{
		ContentValues values = new ContentValues();
		
		values.put(COLUMN_NAME_AUTO_SAVE, 1);
		String where = COLUMN_NAME_ID+" = "+rowId;
		row_id = database.update(FORMS_DATA_TABLE, values, where,null);
		}
		catch(Exception e)
		{
			e.printStackTrace();
			
		}
		
		return row_id;
	}
	
	


	/**
	 * Used to insert data in database
	 * @param formsData
	 * @param date
	 * @param location
	 * @param pictureData
	 * @param time_source 
	 * @param location_source 
	 * @param autoSave
	 */
	public long convertFormFromDraftToFinal(long rowId, int autoSave){

		ContentValues values = new ContentValues();
		
		
		values.put(COLUMN_NAME_AUTO_SAVE, autoSave);
		String where = COLUMN_NAME_ID+" = "+rowId;
		long row_id = database.update(FORMS_DATA_TABLE, values, where,null);
		
		
		return row_id;
	}
	

//	"CREATE TABLE tracking_table (_id INTEGER PRIMARY KEY AUTOINCREMENT, " +
//    "location"+ TYPE_TEXT + COMMA_SEP +
//    "lat"+ TYPE_TEXT + COMMA_SEP +
//    "lng"+ TYPE_TEXT + COMMA_SEP +
//    "accuracy"+ TYPE_TEXT + COMMA_SEP +
//    "altitude"+ TYPE_TEXT + COMMA_SEP +
//    "speed"+ TYPE_TEXT + COMMA_SEP +
//    "gpsTime"+ TYPE_TEXT + COMMA_SEP +
//    "deviceTS"+ TYPE_TEXT + COMMA_SEP +
//    "imei_no"+ TYPE_TEXT + COMMA_SEP +
//    "appId"+ TYPE_TEXT + 
//    ");";

	
	public long saveTrackerPoint(String location, String lat,  String lng, String accuracy, String altitude, String speed, String gpsTime, String deviceTS, String imei_no, String appId,String routeId,String Distance,String inGeoFence,String DistanceGeo){

		ContentValues values = new ContentValues();
		values.put("location", location);
		values.put("lat", lat);
		values.put("lng", lng);
		values.put("accuracy", accuracy);
		values.put("altitude", altitude);
		values.put("speed", speed);
		values.put("gpsTime", gpsTime);
		values.put("deviceTS", deviceTS);
		values.put("imei_no", imei_no);
		values.put("appId", appId);
		values.put("routeId", routeId);
		values.put("distance", Distance);
		values.put("inGeoFence", inGeoFence);
		values.put("distanceGeo", DistanceGeo);
		
		long row_id = database.insert("tracking_table", null, values);
		
		
		return row_id;
	}
	
	
	public long SaveLastActivtiy(String formsData, String date,  String location, String[] imageArray, String location_source, String time_source, int autoSave, String formIconName){
		ContentValues values = new ContentValues();
		values.put(COLUMN_NAME_FORMDATA, formsData);
		values.put(COLUMN_NAME_DATETIME, date);
		values.put(COLUMN_NAME_LOCATION, location);
		

		if(imageArray!=null)
		{
			if(imageArray.length>0)
			{
				JSONArray jsArray;
				//				jsArray = new JSONArray(imageArray);
								jsArray = new JSONArray();
	//							jsArray.put(imageArray)
								for(int i=0;i<imageArray.length;i++)
								{
									jsArray.put(imageArray[i]);
								};
								values.put(COLUMN_NAME_IMAGEARRAY, imageArray.toString());
				
			}
		}
		
		values.put(COLUMN_NAME_LOCATION_SOURCE, location_source);
		values.put(COLUMN_NAME_TIME_SOURCE, time_source);
		values.put(COLUMN_NAME_AUTO_SAVE, autoSave);
		values.put(COLUMN_FORM_ICON_NAME, formIconName);
		long lastid=0;
		try {
			JSONObject jObject = new JSONObject(formsData);
			String formId = jObject.getString("form_id");
			values.put(COLUMN_FORM_ID, formId);
			
			String where = COLUMN_FORM_ID+" = "+formId;
			String[] columns=new String[]{COLUMN_FORM_ID,COLUMN_NAME_FORMDATA};
			Cursor cursor = database.query(FORMS_LAST_DATA_TABLE, columns, where, null, null, null, null);

			if(cursor.getCount()==0){

			lastid=database.insert(FORMS_LAST_DATA_TABLE, null, values);
			
			}else{
				ContentValues v = new ContentValues();
				v.put(COLUMN_NAME_FORMDATA, formsData);
				int st=database.update(FORMS_LAST_DATA_TABLE, v, where, null);	
				Log.e("updated Last entry or not?", st+";;");
				
			}
			
			
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
		return lastid;
		
	}
	
	
	
	public String getLastSentAcitivity(String fId){
		
		
		String where = COLUMN_FORM_ID+" = "+fId;
		String[] columns=new String[]{COLUMN_FORM_ID,COLUMN_NAME_FORMDATA};
		Cursor cursor = database.query(FORMS_LAST_DATA_TABLE, columns, where, null, null, null, null);
		
		if(cursor.getCount()==0){

			return "";
		}
		while(cursor.moveToNext()){


			String activity = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_FORMDATA));
			return activity;
		}
		cursor.close();
		return "";
		
		

	}
	
	public String recreateTables(){
		
		

   	 	
   	 	
//   	 	database.execSQL(DataBaseHelper.DROP_TEMPTIME_TABLE);
   	 	database.execSQL(DataBaseHelper.DROP_BOOTTS_TABLE);
   	 	database.execSQL(DataBaseHelper.DROP_TRACKING_TABLE);
   	 	database.execSQL(DataBaseHelper.DROP_GeoFence_Table);

		
//		database.execSQL(DataBaseHelper.CREATE_TEMP_TABLE);
		database.execSQL(DataBaseHelper.BootTS);
		database.execSQL(DataBaseHelper.CREATE_TRACKING_TABLE);
		database.execSQL(DataBaseHelper.GeoFence);
		return "";
		
		

	}
	
	
	//ID=1 has boot time stamps
	//ID=2 has sntp time stamps
	//ID=3 has system Elasped Timestamps
	
	public boolean SetBootTS(){
		
		String where = "id = 1";
		String[] columns=new String[]{"id","ts"};
		Cursor cursor = database.query("bootts", columns, where, null, null, null, null);

		if(cursor.getCount()==0){
			
			long currentTS = new Date().getTime();
			
			
			ContentValues values = new ContentValues();
			values.put("id", 1);
			values.put("ts", currentTS);
			values.put("devicets", currentTS);

			long row_id = database.insert("bootts", null, values);	
			Log.e("Inserted TS", row_id+";;");
			
			return true;
		}
		else{
			ContentValues values = new ContentValues();
			long currentTS = new Date().getTime();
			values.put("ts", currentTS);
			int st=database.update("bootts", values, where, null);	
			Log.e("updated bootts or not?", st+";;");
			return true;
		}
		
		

	}
	
	

	public boolean SetGeoFence(String p){
		
		String where = "id = 1";
		String[] columns=new String[]{"id","polygon"};
		Cursor cursor = database.query("geoFence", columns, where, null, null, null, null);

		if(cursor.getCount()==0){
			
			
			ContentValues values = new ContentValues();
			values.put("id", 1);
			values.put("polygon", p);
			
			long row_id = database.insert("geoFence", null, values);	
			Log.e("Inserted GeoFence", row_id+";;");
			
			return true;
		}
		else{
			ContentValues values = new ContentValues();
			
			values.put("polygon", p);
			int st=database.update("geoFence", values, where, null);	
			Log.e("updated GeoFence", st+";;");
			return true;
		}
		
		

	}
	
	
	public boolean SetSystemElapsedTS(){
		
		String where = "id = 3";
		String[] columns=new String[]{"id","ts"};
		Cursor cursor = database.query("bootts", columns, where, null, null, null, null);

		if(cursor.getCount()==0){
			
			long elaspedTS = SystemClock.elapsedRealtime();
			long currentTS = new Date().getTime();
			
			
			ContentValues values = new ContentValues();
			values.put("id", 3);
			values.put("ts", elaspedTS);
			values.put("devicets", currentTS);

			long row_id = database.insert("bootts", null, values);	
			Log.e("Inserted elapsed TS", row_id+";;");
			
			return true;
		}
		else{
			ContentValues values = new ContentValues();
			long elaspedTS = SystemClock.elapsedRealtime();
			long currentTS = new Date().getTime();
			values.put("ts", elaspedTS);
			values.put("devicets", currentTS);
			int st=database.update("bootts", values, where, null);	
			Log.e("updated system elasped TS or not?", st+";;");
			return true;
		}
		
		

	}
	

	public boolean SetSNTPTS(long time){
		
		String where = "id = 2";
		String[] columns=new String[]{"id","ts","devicets"};
		Cursor cursor = database.query("bootts", columns, where, null, null, null, null);

		
		if(cursor.getCount()==0){
		
			
			//no data found so insert first row.
			
//			long currentTS = SystemClock.elapsedRealtime();
			long currentTS=new Date().getTime();
			
			
			ContentValues values = new ContentValues();
			values.put("id", 2);
			values.put("ts", time);
			values.put("devicets", currentTS);

			long row_id = database.insert("bootts", null, values);	
			Log.e("Inserted TS", row_id+";;");
			
			return true;
		}
		else{
			ContentValues values = new ContentValues();
//			long currentTS = SystemClock.elapsedRealtime();
			long currentTS=new Date().getTime();
			values.put("ts", time);
			values.put("devicets", currentTS);
			int st=database.update("bootts", values, where, null);	
			Log.e("updated bootts or not?", st+";;");
			return true;
		}
		
		

	}
	
	

	public boolean SetSNTPTSLocalOnly(){
		
		String where = "id = 2";
		String[] columns=new String[]{"id","ts","devicets"};
		Cursor cursor = database.query("bootts", columns, where, null, null, null, null);

		
		if(cursor.getCount()==0){
		
			
			//no data found so insert first row.
			
//			long currentTS = SystemClock.elapsedRealtime();
			long currentTS=new Date().getTime();
			
			
			ContentValues values = new ContentValues();
			values.put("id", 2);
			values.put("ts", 0);
			values.put("devicets", currentTS);

			long row_id = database.insert("bootts", null, values);	
			Log.e("Inserted TS", row_id+";;");
			
			return true;
		}
		else{
			ContentValues values = new ContentValues();
			long currentTS=new Date().getTime();
			values.put("devicets", currentTS);
			int st=database.update("bootts", values, where, null);	
			Log.e("updated bootts or not?", st+";;");
			return true;
		}
		
		

	}
	

	public long getBootTS(){
		
		
		long ts = 0;
		String where = "id = 1";
		String[] columns=new String[]{"id","ts"};
		Cursor cursor = database.query("bootts", columns, where, null, null, null, null);

		if(cursor.getCount()==0){

			return 0;
		}
		while(cursor.moveToNext()){


			ts = cursor.getLong(cursor.getColumnIndexOrThrow("ts"));
			return ts;
		}
		cursor.close();
		return ts;
		
		

	}
	
	
	public String getGeoFence(){
		
		
		String polygon="";
		String where = "id = 1";
		String[] columns=new String[]{"id","polygon"};
		Cursor cursor = database.query("geoFence", columns, where, null, null, null, null);

		if(cursor.getCount()==0){

			return "";
		}
		while(cursor.moveToNext()){


			polygon = cursor.getString(cursor.getColumnIndexOrThrow("polygon"));
			return polygon;
		}
		cursor.close();
		return polygon;
		
		

	}


	public long getElaspedTS(){
		
		
		long ts = 0;
		String where = "id = 3";
		String[] columns=new String[]{"id","ts"};
		Cursor cursor = database.query("bootts", columns, where, null, null, null, null);

		if(cursor.getCount()==0){

			return 0;
		}
		while(cursor.moveToNext()){


			ts = cursor.getLong(cursor.getColumnIndexOrThrow("ts"));
			return ts;
		}
		cursor.close();
		return ts;
		
		

	}

	public long getElaspedDeviceTS(){
		long ts = 0;
		String where = "id = 3";
		String[] columns=new String[]{"id","ts","devicets"};
		Cursor cursor = database.query("bootts", columns, where, null, null, null, null);
		if(cursor.getCount()==0){
			return 0;
		}
		while(cursor.moveToNext()){
			ts = cursor.getLong(cursor.getColumnIndexOrThrow("devicets"));
			return ts;
		}
		cursor.close();
		return ts;
		
	}


	public long getSntpTS(){
		
		
		long ts = 0;
		String where = "id = 2";
		String[] columns=new String[]{"id","ts","devicets"};
		Cursor cursor = database.query("bootts", columns, where, null, null, null, null);

		if(cursor.getCount()==0){

			return 0;
		}
		while(cursor.moveToNext()){


			ts = cursor.getLong(cursor.getColumnIndexOrThrow("ts"));
			return ts;
		}
		cursor.close();
		return ts;
		
		

	}
	
	
	public long getSntpDeviceTS(){
		
		
		long ts = 0;
		String where = "id = 2";
		String[] columns=new String[]{"id","ts","devicets"};
		Cursor cursor = database.query("bootts", columns, where, null, null, null, null);

		if(cursor.getCount()==0){
			
			return 0;
		}
		while(cursor.moveToNext()){


			ts = cursor.getLong(cursor.getColumnIndexOrThrow("devicets"));
			return ts;
		}
		cursor.close();
		return ts;
		
		

	}
	/**
	 * Used to update data in database
	 * @param formsData
	 * @param id
	 * @param autoSave
	 */
	public void updateFormsData(String formData, int autoSave, int id) {
		ContentValues values = new ContentValues();
		values.put(COLUMN_NAME_FORMDATA, formData);
		values.put(COLUMN_NAME_AUTO_SAVE, autoSave);
		int st=database.update(FORMS_DATA_TABLE, values, COLUMN_NAME_ID+"="+id, null);	
		Log.e("updated or not?", st+";;");
	}

	/**
	 * Used to read form data from database.
	 * @return
	 */
	public ArrayList<FormsDataInfo> readFormsData(){

		ArrayList<FormsDataInfo> dataList = new ArrayList<FormsDataInfo>();
		FormsDataInfo dataInfo = null;
		
		Cursor cursor = database.query(FORMS_DATA_TABLE, FORMSDATA_PROJECTION, null, null, null, null, null);

		if(cursor == null){
			return null;
		}
		
		if(cursor.getCount()>0)
		{
			
			 
		while(cursor.moveToNext()){

			dataInfo = new FormsDataInfo();
			dataInfo.id = cursor.getInt(cursor.getColumnIndexOrThrow(COLUMN_NAME_ID));
			dataInfo.formData = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_FORMDATA));
			dataInfo.dateTime = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_DATETIME));
			dataInfo.location = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_LOCATION));
			dataInfo.imageArray = cursor.getBlob(cursor.getColumnIndexOrThrow(COLUMN_NAME_IMAGEARRAY));
			dataInfo.imagePaths = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_IMAGEARRAY));
			dataInfo.locationSource = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_LOCATION_SOURCE));
			dataInfo.timeSource = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_TIME_SOURCE));
			dataInfo.autoSave = cursor.getInt(cursor.getColumnIndexOrThrow(COLUMN_NAME_AUTO_SAVE));
			dataInfo.formIconName = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_FORM_ICON_NAME));
			dataList.add(dataInfo);
		}
		cursor.close();
		return dataList;
		}
		else{
			return null;
		}
	}
	
	public ArrayList<FormsDataInfo> readFormsUnsentData(){

		ArrayList<FormsDataInfo> dataList = new ArrayList<FormsDataInfo>();
		FormsDataInfo dataInfo = null;
		String where = COLUMN_NAME_AUTO_SAVE + " = " + "1";
		Cursor cursor = database.query(FORMS_DATA_TABLE, FORMSDATA_PROJECTION, where, null, null, null, null);

		if(cursor == null){
			return null;
		}
		
		if(cursor.getCount()>0)
		{
			
			 
		while(cursor.moveToNext()){

			dataInfo = new FormsDataInfo();
			dataInfo.id = cursor.getInt(cursor.getColumnIndexOrThrow(COLUMN_NAME_ID));
			dataInfo.formData = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_FORMDATA));
			dataInfo.dateTime = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_DATETIME));
			dataInfo.location = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_LOCATION));
			dataInfo.imageArray = cursor.getBlob(cursor.getColumnIndexOrThrow(COLUMN_NAME_IMAGEARRAY));
			dataInfo.imagePaths = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_IMAGEARRAY));
			dataInfo.locationSource = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_LOCATION_SOURCE));
			dataInfo.timeSource = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_TIME_SOURCE));
			dataInfo.autoSave = cursor.getInt(cursor.getColumnIndexOrThrow(COLUMN_NAME_AUTO_SAVE));
			dataInfo.formIconName = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_FORM_ICON_NAME));
			dataList.add(dataInfo);
		}
		cursor.close();
		return dataList;
		}
		else{
			return null;
		}
	}
	
	
	public ArrayList<FormsDataInfo> readFormsEditData(){

		ArrayList<FormsDataInfo> dataList = new ArrayList<FormsDataInfo>();
		FormsDataInfo dataInfo = null;
		String where = COLUMN_NAME_AUTO_SAVE + " <> " + "1";
		Cursor cursor = database.query(FORMS_DATA_TABLE, FORMSDATA_PROJECTION, where, null, null, null, null);

		if(cursor == null){
			return null;
		}
		
		if(cursor.getCount()>0)
		{
			
			 
		while(cursor.moveToNext()){

			dataInfo = new FormsDataInfo();
			dataInfo.id = cursor.getInt(cursor.getColumnIndexOrThrow(COLUMN_NAME_ID));
			dataInfo.formData = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_FORMDATA));
			dataInfo.dateTime = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_DATETIME));
			dataInfo.location = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_LOCATION));
			dataInfo.imageArray = cursor.getBlob(cursor.getColumnIndexOrThrow(COLUMN_NAME_IMAGEARRAY));
			dataInfo.imagePaths = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_IMAGEARRAY));
			dataInfo.locationSource = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_LOCATION_SOURCE));
			dataInfo.timeSource = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_TIME_SOURCE));
			dataInfo.autoSave = cursor.getInt(cursor.getColumnIndexOrThrow(COLUMN_NAME_AUTO_SAVE));
			dataInfo.formIconName = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_FORM_ICON_NAME));
			dataList.add(dataInfo);
		}
		cursor.close();
		return dataList;
		}
		else{
			return null;
		}
	}
	
	public ArrayList<trackingPoint> readTrackingData(){

		try{
			
		
		ArrayList<trackingPoint> dataList = new ArrayList<trackingPoint>();
		
		Cursor lastCursor = database.query("tracking_table", TRACKING_PROJECTION,null, null, null, null, null);
		lastCursor.moveToLast();
		String lastEnteredTS=lastCursor.getString(lastCursor.getColumnIndexOrThrow("deviceTS"));
		
		
//		Cursor cursor = database.query("tracking_table", TRACKING_PROJECTION, null, null, null, null, null);
		
		
		
		
		
		// now first read the last entry and then read 20000 max for that date and same onwards
		
		// 40000 limit to avoid memeory issues
		String[] lastEnteredTSChunks = lastEnteredTS.split(" ");
		lastEnteredTS=lastEnteredTSChunks[0];
		Cursor cursor = database.query("tracking_table", TRACKING_PROJECTION, "deviceTS LIKE '%" + lastEnteredTS + "%'", null, null, null, null,"40000");
		
		if(cursor == null){
			return null;
		}
		
		if(cursor.getCount()>0)
		{
			
			 
		while(cursor.moveToNext()){

			
			trackingPoint tp=new trackingPoint();
			tp.id=cursor.getInt(cursor.getColumnIndexOrThrow("_id"));
			
			tp.location=cursor.getString(cursor.getColumnIndexOrThrow("location"));
			tp.lat=cursor.getString(cursor.getColumnIndexOrThrow("lat"));
			tp.lng=cursor.getString(cursor.getColumnIndexOrThrow("lng"));
			tp.accuracy=cursor.getString(cursor.getColumnIndexOrThrow("accuracy"));
			tp.altitude=cursor.getString(cursor.getColumnIndexOrThrow("altitude"));
			tp.speed=cursor.getString(cursor.getColumnIndexOrThrow("speed"));
			tp.gpsTime=cursor.getString(cursor.getColumnIndexOrThrow("gpsTime"));
			tp.deviceTS=cursor.getString(cursor.getColumnIndexOrThrow("deviceTS"));
			tp.imei_no=cursor.getString(cursor.getColumnIndexOrThrow("imei_no"));
			tp.appId=cursor.getString(cursor.getColumnIndexOrThrow("appId"));
			tp.routeId=cursor.getString(cursor.getColumnIndexOrThrow("routeId"));
			tp.distance=cursor.getString(cursor.getColumnIndexOrThrow("distance"));
			tp.inGeoFence=cursor.getString(cursor.getColumnIndexOrThrow("inGeoFence"));
			tp.distanceGeo=cursor.getString(cursor.getColumnIndexOrThrow("distanceGeo"));
			
			
			dataList.add(tp);
		}
		cursor.close();
		return dataList;
		}
		else{
			return null;
		}
		}
		catch(Exception e){
			
			e.printStackTrace();
			return null;
		}
	}

	/**
	 * Used to read pictures for a specific record.
	 * @param recordId
	 */
	public void readPicturesForSpecificRecord(int recordId){

		FormsDataInfo dataInfo = null;
		String where = COLUMN_NAME_ID + " = " + recordId;
		Cursor cursor = database.query(FORMS_DATA_TABLE, FORMSDATA_PROJECTION, where, null, null, null, null);

		if(cursor != null){

			cursor.moveToFirst();
			dataInfo = new FormsDataInfo();
			dataInfo.id = cursor.getInt(cursor.getColumnIndexOrThrow(COLUMN_NAME_ID));
			dataInfo.formData = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_FORMDATA));
			dataInfo.dateTime = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_DATETIME));
			dataInfo.location = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_LOCATION));
			dataInfo.imageArray = cursor.getBlob(cursor.getColumnIndexOrThrow(COLUMN_NAME_IMAGEARRAY));
			dataInfo.locationSource = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_LOCATION_SOURCE));
			dataInfo.timeSource = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_TIME_SOURCE));
			dataInfo.autoSave = cursor.getInt(cursor.getColumnIndexOrThrow(COLUMN_NAME_AUTO_SAVE));
			dataInfo.formIconName = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_FORM_ICON_NAME));
			Log.i("ARRAY SAVED", "<> "+dataInfo.imageArray.length);
		}
		cursor.close();
	}

	/**
	 * Used to delete particular item from saved data.
	 * @param id
	 */
	public void deleteFormsDataItem(int id){

		String where = COLUMN_NAME_ID + " = " + id;
		database.delete(FORMS_DATA_TABLE, where, null);
	}
	
 
	/**
	 * Used to delete particular item from saved data.
	 * @param id
	 */
	public void deleteTrackingPointItem(int id){

		String where = "_id" + " = " + id;
		database.delete("tracking_table", where, null);
	}
	
	
	
	
	public String getTimeFromTempTable(String id){
		
		String where = COLUMN_NAME_TIME_ID+ " = " +DatabaseUtils.sqlEscapeString(id);
		Cursor cursor = database.query(TEMP_TIME_TABLE, TEMPTIME_PROJECTION, where, null, null, null, null);
		if(cursor!=null && cursor.getCount()>0){
			cursor.moveToFirst();
			String timeValue = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_DATETIME_VALUE));
			String statusValue = cursor.getString(cursor.getColumnIndexOrThrow(COLUMN_NAME_STATUS_VALUE));
			return timeValue+"#"+statusValue;
		}else{
			return "";
		}
	}
	
	public long insertTempTime(String id, String dateTime, String status){
		
		ContentValues values = new ContentValues();
		values.put(COLUMN_NAME_TIME_ID, id);
		values.put(COLUMN_NAME_DATETIME_VALUE, dateTime);
		values.put(COLUMN_NAME_STATUS_VALUE, status);
		long tempId = database.insert(TEMP_TIME_TABLE, null, values);
		return tempId;
	}
	
	
	public void deleteAllTempTimeData(String status){

		
		if(status!=null && status.length()>0){
			String where = COLUMN_NAME_STATUS_VALUE+ " = " +DatabaseUtils.sqlEscapeString(status);
			database.delete(TEMP_TIME_TABLE, where, null);
		}else{
			database.delete(TEMP_TIME_TABLE, null, null);
		}
		
	}
	
}