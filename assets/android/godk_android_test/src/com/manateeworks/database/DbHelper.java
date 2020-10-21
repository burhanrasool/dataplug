package com.manateeworks.database;

import android.content.Context;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

public class DbHelper extends SQLiteOpenHelper
{
	public static final String DB_NAME = "vvip_security_system.db";
	public static final int DB_VERSION = 1;
	public final String tableName_Users = "Users";
	public final String activity_id = "_id";
	public final String Name="Name";
	public final String Father_Name="Father_Name";
	public final String Family_No="Family_No";
	public final String Date_Of_Birth="Date_Of_Birth";	
	public final String Address="Address";
	public final String District="District";
	public final String City="City";
	public final String IDno="IDno";
	public final String House_No="House_No";
	public final String Vehicle_No="Vehicle_No";
	public final String Vehicle_Color="Vehicle_Color";
	public final String Vehicle_Brand="Vehicle_Brand";
	public final String User_Type="User_Type";
	public final String User_Selected="User_Selected";
	private final String sqlCreateTable_User =
			"CREATE TABLE "+ tableName_Users +" (" +
					activity_id + " INTEGER PRIMARY KEY AUTOINCREMENT, " +
					Name + " VARCHAR, "	+
					Father_Name + " VARCHAR, "	+
					Family_No + " VARCHAR, "	+
					Date_Of_Birth + " VARCHAR, "	+
					Address + " VARCHAR, "	+
					District + " VARCHAR, "	+
					City + " VARCHAR, "	+
					IDno + " VARCHAR, "	+
					House_No+ " VARCHAR, "	+
					Vehicle_No + " VARCHAR, "	+
					Vehicle_Color+ " VARCHAR, "	+
					Vehicle_Brand + " VARCHAR, "	+
					//User_Selected+ " VARCHAR, "	+
					User_Type + " VARCHAR"	+ ");";
	
	
	public final String TABLE_Sync_HouseNo_VehicleNo = "sync_houseno_vehicleno";
	
	private final String SQL_CREATE_HouseNo_VehicleNo =
			"CREATE TABLE "+ TABLE_Sync_HouseNo_VehicleNo +" (" +
					activity_id + " INTEGER PRIMARY KEY AUTOINCREMENT, " +
					House_No+ " VARCHAR, "	+
					Vehicle_No	+ " VARCHAR"	+ ");";
	
	
	public final String TABLE_Sync_Date = "sync_date";
	public final String date="date";
	private final String SQL_CREATE_Date =
			"CREATE TABLE "+ TABLE_Sync_Date +" (" +
					activity_id + " INTEGER PRIMARY KEY AUTOINCREMENT, " +
					
					date	+ " VARCHAR"	+ ");";
	
	public final String TABLE_Registered_Users = "Registered_Users";
	public final String json_data ="json_data";
	private final String SQL_CREATE_Registered_Users =
			"CREATE TABLE "+ TABLE_Registered_Users +" (" +
					activity_id + " INTEGER PRIMARY KEY AUTOINCREMENT, " +
					json_data	+ " VARCHAR"	+ ");";
	
	public final String TABLE_User_Registered = "user_registered";
	public final String Is_User_Registered="is_user_registered";
	private final String SQL_CREATE_Registered =
			"CREATE TABLE "+ TABLE_User_Registered +" (" +
					activity_id + " INTEGER PRIMARY KEY AUTOINCREMENT, " +
					Is_User_Registered + " VARCHAR, "	+
					date	+ " VARCHAR"	+ ");";
	
	
	
	private final String sqlDropTable_ActivityCTD = "DROP TABLE IF EXISTS " + tableName_Users + ";";
    private final String SQL_DROP_Sync_Date = "DROP TABLE IF EXISTS " + TABLE_Sync_Date + ";";
    private final String SQL_DROP_Sync_HouseNo_VehicleNo = "DROP TABLE IF EXISTS " + TABLE_Sync_HouseNo_VehicleNo + ";"; 
    private final String SQL_DROP_Registered_Users = "DROP TABLE IF EXISTS " + TABLE_Registered_Users + ";";
    private final String SQL_DROP_Is_User_Registered = "DROP TABLE IF EXISTS " + TABLE_User_Registered + ";";

	public DbHelper(Context c){
		super(c, DB_NAME, null, DB_VERSION);
	}

	@Override
	public void onCreate(SQLiteDatabase db){
		db.execSQL(sqlCreateTable_User);
		db.execSQL(SQL_CREATE_Date);
		db.execSQL(SQL_CREATE_HouseNo_VehicleNo);
		db.execSQL(SQL_CREATE_Registered_Users);
		db.execSQL(SQL_CREATE_Registered);
	}
	@Override
	public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion){
		db.execSQL(sqlDropTable_ActivityCTD);
		db.execSQL(SQL_DROP_Sync_Date);
		db.execSQL(SQL_DROP_Sync_HouseNo_VehicleNo);
		db.execSQL(SQL_DROP_Registered_Users);
		db.execSQL(SQL_DROP_Is_User_Registered);
		onCreate(db);   
	}
}