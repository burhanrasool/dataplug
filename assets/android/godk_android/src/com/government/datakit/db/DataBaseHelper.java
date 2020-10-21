package com.government.datakit.db;


import java.util.ArrayList;

import com.government.datakit.bo.FormsDataInfo;

import android.content.Context;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

/**
 * 
 * @author gulfamhassan
 *
 */

public class DataBaseHelper extends SQLiteOpenHelper{
	
    private static final String DATABASE_NAME = "GODK.db";
    private static final int DATABASE_VERSION = 1;
    private static final String COMMA_SEP = ",";
    private static final String TYPE_TEXT = " TEXT";
    private static final String TYPE_VARCHAR = " VARCHAR";
    private static final String TYPE_BLOB = " BLOB";
    private static final String TYPE_INT = " INT";
    private static final String TYPE_LONG = " LONG";
	
    public static final String CREATE_FORMSDATA_TABLE =
			"CREATE TABLE "+DataBaseAdapter.FORMS_DATA_TABLE+" (" +
	        DataBaseAdapter.COLUMN_NAME_ID +" INTEGER PRIMARY KEY AUTOINCREMENT, " +
	        DataBaseAdapter.COLUMN_NAME_FORMDATA+ TYPE_TEXT + COMMA_SEP +
	        DataBaseAdapter.COLUMN_NAME_DATETIME+ TYPE_VARCHAR + COMMA_SEP +
	        DataBaseAdapter.COLUMN_NAME_LOCATION+ TYPE_VARCHAR +COMMA_SEP +
			DataBaseAdapter.COLUMN_NAME_IMAGEARRAY+ TYPE_BLOB + COMMA_SEP +
	        DataBaseAdapter.COLUMN_NAME_LOCATION_SOURCE+ TYPE_VARCHAR +COMMA_SEP +
	        DataBaseAdapter.COLUMN_NAME_TIME_SOURCE+ TYPE_VARCHAR +COMMA_SEP +
	        DataBaseAdapter.COLUMN_NAME_AUTO_SAVE+ TYPE_INT +COMMA_SEP +
	        DataBaseAdapter.COLUMN_FORM_ICON_NAME+ TYPE_VARCHAR +
	        ");"
			;

	public static final String CREATE_LAST_FORMSDATA_TABLE =
			"CREATE TABLE "+DataBaseAdapter.FORMS_LAST_DATA_TABLE+" (" +
	        DataBaseAdapter.COLUMN_NAME_ID +" INTEGER PRIMARY KEY AUTOINCREMENT, " +
	        DataBaseAdapter.COLUMN_FORM_ID+ TYPE_TEXT + COMMA_SEP +
	        DataBaseAdapter.COLUMN_NAME_FORMDATA+ TYPE_TEXT + COMMA_SEP +
	        DataBaseAdapter.COLUMN_NAME_DATETIME+ TYPE_VARCHAR + COMMA_SEP +
	        DataBaseAdapter.COLUMN_NAME_LOCATION+ TYPE_VARCHAR +COMMA_SEP +
			DataBaseAdapter.COLUMN_NAME_IMAGEARRAY+ TYPE_BLOB + COMMA_SEP +
	        DataBaseAdapter.COLUMN_NAME_LOCATION_SOURCE+ TYPE_VARCHAR +COMMA_SEP +
	        DataBaseAdapter.COLUMN_NAME_TIME_SOURCE+ TYPE_VARCHAR +COMMA_SEP +
	        DataBaseAdapter.COLUMN_NAME_AUTO_SAVE+ TYPE_INT +COMMA_SEP +
	        DataBaseAdapter.COLUMN_FORM_ICON_NAME+ TYPE_VARCHAR +
	        ");"
			;
    
	public static final String CREATE_TEMP_TABLE =
			"CREATE TABLE "+DataBaseAdapter.TEMP_TIME_TABLE+" (" +
	        DataBaseAdapter.COLUMN_NAME_ID +" INTEGER PRIMARY KEY AUTOINCREMENT, " +
	        DataBaseAdapter.COLUMN_NAME_TIME_ID+ TYPE_TEXT + COMMA_SEP +
	        DataBaseAdapter.COLUMN_NAME_DATETIME_VALUE+ TYPE_VARCHAR + COMMA_SEP +
	        DataBaseAdapter.COLUMN_NAME_STATUS_VALUE+ TYPE_VARCHAR +
	        ");"
			;
	
    
	public static final String CREATE_TRACKING_TABLE =
			"CREATE TABLE tracking_table (_id INTEGER PRIMARY KEY AUTOINCREMENT, " +
	        "location"+ TYPE_TEXT + COMMA_SEP +
	        "lat"+ TYPE_TEXT + COMMA_SEP +
	        "lng"+ TYPE_TEXT + COMMA_SEP +
	        "accuracy"+ TYPE_TEXT + COMMA_SEP +
	        "altitude"+ TYPE_TEXT + COMMA_SEP +
	        "speed"+ TYPE_TEXT + COMMA_SEP +
	        "gpsTime"+ TYPE_TEXT + COMMA_SEP +
	        "deviceTS"+ TYPE_TEXT + COMMA_SEP +
	        "imei_no"+ TYPE_TEXT + COMMA_SEP +
	        "appId"+ TYPE_TEXT +  COMMA_SEP +
	        "routeId"+ TYPE_TEXT +  COMMA_SEP +
	        "distance"+ TYPE_TEXT + COMMA_SEP +
	        "inGeoFence"+ TYPE_TEXT + COMMA_SEP +
	        "distanceGeo" + TYPE_TEXT +
	        ");";
	
	

	public static final String GeoFence =
			"CREATE TABLE geoFence"+" (" +
	        "id" +" INTEGER PRIMARY KEY AUTOINCREMENT, " +
	        "polygon"+ TYPE_TEXT +
	        ");"
			;
	
 
	public static final String BootTS =
			"CREATE TABLE bootts"+" (" +
	        "rowId" +" INTEGER PRIMARY KEY AUTOINCREMENT, " +
	        "id" + TYPE_INT + COMMA_SEP +
	        "ts"+ TYPE_LONG + COMMA_SEP +
	        "devicets"+ TYPE_LONG +
	        ");"
			;
	
	
    public static final String DROP_DISEASES_TABLE = "DROP TABLE IF EXISTS "+DataBaseAdapter.FORMS_DATA_TABLE+";";
    public static final String DROP_LAST_TABLE = "DROP TABLE IF EXISTS "+DataBaseAdapter.FORMS_LAST_DATA_TABLE+";";
    public static final String DROP_TEMPTIME_TABLE = "DROP TABLE IF EXISTS "+DataBaseAdapter.TEMP_TIME_TABLE+";";
    public static final String DROP_BOOTTS_TABLE = "DROP TABLE IF EXISTS bootts;";
    public static final String DROP_TRACKING_TABLE = "DROP TABLE IF EXISTS tracking_table;";
    public static final String DROP_GeoFence_Table = "DROP TABLE IF EXISTS geoFence;";
    
    public DataBaseHelper(Context c){
        super(c, DATABASE_NAME, null, DATABASE_VERSION);
    }
    
    @Override
    public void onCreate(SQLiteDatabase db){
       
        db.execSQL(DROP_LAST_TABLE);
        db.execSQL(DROP_TEMPTIME_TABLE);
        db.execSQL(DROP_BOOTTS_TABLE);
        db.execSQL(DROP_TRACKING_TABLE);
        db.execSQL(DROP_GeoFence_Table);
        
    	db.execSQL(CREATE_FORMSDATA_TABLE);
    	db.execSQL(CREATE_LAST_FORMSDATA_TABLE);
    	db.execSQL(CREATE_TEMP_TABLE);
    	db.execSQL(BootTS);
    	db.execSQL(CREATE_TRACKING_TABLE);
    	db.execSQL(GeoFence);
    }
    
    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion){

    	
    	 db.execSQL(DROP_DISEASES_TABLE);
         db.execSQL(DROP_LAST_TABLE);
         db.execSQL(DROP_TEMPTIME_TABLE);
         db.execSQL(DROP_BOOTTS_TABLE);
         db.execSQL(DROP_TRACKING_TABLE);
         db.execSQL(DROP_GeoFence_Table);
         
     	db.execSQL(CREATE_FORMSDATA_TABLE);
     	db.execSQL(CREATE_LAST_FORMSDATA_TABLE);
     	db.execSQL(CREATE_TEMP_TABLE);
     	db.execSQL(BootTS);
     	db.execSQL(CREATE_TRACKING_TABLE);
     	db.execSQL(GeoFence);
    }
}
