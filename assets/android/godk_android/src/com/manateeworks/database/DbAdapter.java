package com.manateeworks.database;

import java.util.ArrayList;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.SQLException;
import android.database.sqlite.SQLiteDatabase;
import android.util.Log;

import com.manateeworks.CNICScanner.ClassHouseVehicle;
import com.manateeworks.CNICScanner.ClassUser;

public class DbAdapter
{	
	private Context mContext;
	private SQLiteDatabase mDb;
	private DbHelper mDbHelper;

	public final String[] PROJECTIONALL_Users;
	public final String[] PROJECTION_Date;
	public final String[] PROJECTION_Registered_Users;
	public final String[] PROJECTION_User_Registered;
	public DbAdapter(Context c)
	{
		mContext = c;
		mDbHelper = new DbHelper(mContext);
		PROJECTIONALL_Users = new String[]{mDbHelper.activity_id, mDbHelper.Name, mDbHelper.Father_Name,mDbHelper.Family_No,mDbHelper.Address,mDbHelper.Date_Of_Birth,mDbHelper.District,mDbHelper.City,mDbHelper.IDno,mDbHelper.House_No,mDbHelper.Vehicle_No,mDbHelper.Vehicle_Color,mDbHelper.Vehicle_Brand,mDbHelper.User_Type};
		PROJECTION_Date = new String[]{mDbHelper.activity_id,mDbHelper.date};
		PROJECTION_Registered_Users = new String[]{mDbHelper.activity_id, mDbHelper.json_data};
		PROJECTION_User_Registered = new String[]{mDbHelper.activity_id, mDbHelper.date,mDbHelper.Is_User_Registered};
	}
	public DbAdapter open() throws SQLException{
		mDb = mDbHelper.getWritableDatabase();
		return this;
	}
	public void close(){
		try{
			mDbHelper.close();
		}
		catch(Exception e){
			Log.e("Database", e.getMessage());
		}
	}
	public void resetUserData(){
		this.open();
		mDb.execSQL("delete from " + mDbHelper.tableName_Users);
		this.close();
	}
	
	public boolean isApplicationInitialized(){
		try{
			this.open();
			Cursor queryCursor = mDb.query(mDbHelper.tableName_Users, PROJECTIONALL_Users, null, null, null, null, null, null);
			boolean isInitialized = (queryCursor == null || !queryCursor.moveToNext())? false: true;
			queryCursor.close();
			this.close();
			return isInitialized;
		}
		catch(Exception e){
			Log.e("Database", e.getMessage());
			return false;
		}
	}
	public long insertUser(ClassUser object){
		try{
			this.open();
			long id = mDb.insert(mDbHelper.tableName_Users, null, createContentValuesUser(object));
			this.close();
			return id;
		}
		catch(Exception e){
			Log.e("Database", e.getMessage());
			return -1;
		}
	}
	public long insertHouseVehicle(ClassHouseVehicle object){
		try{
			this.open();
			long id = mDb.insert(mDbHelper.TABLE_Sync_HouseNo_VehicleNo, null, createContentValuesHouseVehicle(object));
			this.close();
			return id;
		}
		catch(Exception e){
			Log.e("Database", e.getMessage());
			return -1;
		}
	}
	public long UpdateUser(ClassUser object){
		try{
			this.open();
			String format = "%s=?";
			String whereClause = String.format(format, mDbHelper.IDno);
			//long id = mDb.updat(mDbHelper.tableName_Users, null, createContentValuesUser(object));
			long id = mDb.update(mDbHelper.tableName_Users, createContentValuesUser(object), whereClause, new String[]{object.IDno+""});
			
			this.close();
			return id;
		}
		catch(Exception e){
			Log.e("Database", e.getMessage());
			return -1;
		}
	}
	public boolean IsUserExists(ClassUser object){
		try{
			this.open();
			String format = "%s=?";
			String whereClause = String.format(format, mDbHelper.IDno);
			
			Cursor queryCursor = mDb.query(mDbHelper.tableName_Users, PROJECTIONALL_Users, whereClause,new String[]{object.IDno+""}, null, null, null, null);
			//boolean isUserExists = (queryCursor == null || !queryCursor.moveToNext())? false: true;
			boolean isUserExists = (queryCursor == null || !queryCursor.moveToFirst())? false: true;
			this.close();
			return isUserExists;
		}
		catch(Exception e){
			Log.e("Database", e.getMessage());
			return false;
		}
	}
	public ContentValues createContentValuesUser(ClassUser object){
		try{
			ContentValues cv = new ContentValues();
			//cv.put(mDbHelper.activity_id, object.activity_id);
			cv.put(mDbHelper.Name, object.Name);
			cv.put(mDbHelper.Father_Name, object.Father_Name);
			cv.put(mDbHelper.Family_No, object.Family_No);
			cv.put(mDbHelper.Address, object.Address);
			cv.put(mDbHelper.Date_Of_Birth, object.Date_Of_Birth);
			cv.put(mDbHelper.District, object.District);
			cv.put(mDbHelper.City, object.City);
			cv.put(mDbHelper.IDno, object.IDno);
			cv.put(mDbHelper.House_No, object. House_No);
			cv.put(mDbHelper.Vehicle_No, object.Vehicle_No);
			cv.put(mDbHelper.Vehicle_Color, object.Vehicle_Color);
			cv.put(mDbHelper.Vehicle_Brand, object.Vehicle_Brand);
			cv.put(mDbHelper.User_Type, object.User_Type);
			
			return cv;
		}
		catch(Exception e){
			Log.e("Database", e.getMessage());
			return null;
		}
	}
	
	public ContentValues createContentValuesHouseVehicle(ClassHouseVehicle object){
		try{
			ContentValues cv = new ContentValues();
			//cv.put(mDbHelper.activity_id, object.activity_id);
			cv.put(mDbHelper.House_No, object. House_No);
			cv.put(mDbHelper.Vehicle_No, object.Vehicle_No);			
			return cv;
		}
		catch(Exception e){
			Log.e("Database", e.getMessage());
			return null;
		}
	}
	public long insertSyncDate(String date)
	{
		try
		{
			this.open();
			long id = mDb.insert(mDbHelper.TABLE_Sync_Date, null, createContentValuesDate(date));
			this.close();
			return id;
		}
		catch(Exception e)
		{
			Log.e("Database", e.getMessage());
			return -1;
		}
	}
	private ContentValues createContentValuesDate(String date)
	{
		try
		{
			ContentValues cv = new ContentValues();
	
			cv.put(mDbHelper.date, date);		
			return cv;
		}
		catch(Exception e)
		{
			Log.e("Database", e.getMessage());
			return null;
		}
	}
	private ContentValues createContentValuesIsRegistered(String date,String IsRegistered)
	{
		try
		{
			ContentValues cv = new ContentValues();
	
			cv.put(mDbHelper.date, date);		
			cv.put(mDbHelper.Is_User_Registered, IsRegistered);
			return cv;
		}
		catch(Exception e)
		{
			Log.e("Database", e.getMessage());
			return null;
		}
	}
	public long insertRegisteredUser(String jsonData)
	{
		try
		{
			this.open();
			long id = mDb.insert(mDbHelper.TABLE_Registered_Users, null, createContentValuesRegisteredUser(jsonData));
			this.close();
			return id;
		}
		catch(Exception e)
		{
			Log.e("Database", e.getMessage());
			return -1;
		}
	}
	private ContentValues createContentValuesRegisteredUser(String jsonData)
	{
		try
		{
			ContentValues cv = new ContentValues();	
			cv.put(mDbHelper.json_data, jsonData);
			return cv;
		}
		catch(Exception e)
		{
			Log.e("Database", e.getMessage());
			return null;
		}
	}
	public long insertIsRegistered(String date,String IsRegistered)
	{
		try
		{
			this.open();
			long id = mDb.insert(mDbHelper.TABLE_User_Registered, null, createContentValuesIsRegistered(date,IsRegistered));
			this.close();
			return id;
		}
		catch(Exception e)
		{
			Log.e("Database", e.getMessage());
			return -1;
		}
	}
	public void deleteUser(int id){
		try{
			this.open();
			String where = mDbHelper.activity_id + " = " + id;
			mDb.delete(mDbHelper.tableName_Users, where, null);
			this.close();
		}
		catch(Exception e)
		{
			Log.e("Database", e.getMessage());
		}
	}
	public void DeleteDate()
	{
		this.open();
		mDb.delete(mDbHelper.TABLE_Sync_Date, null, null);
		this.close();
	}
	public void DeleteIsRegistered()
	{
		this.open();
		mDb.delete(mDbHelper.TABLE_User_Registered, null, null);
		this.close();
	}
	public void deleteRegisteredUser(int id){
		try{
			this.open();
			String where = mDbHelper.activity_id + " = " + id;
			mDb.delete(mDbHelper.TABLE_Registered_Users, where, null);
			this.close();
		}
		catch(Exception e)
		{
			Log.e("Database", e.getMessage());
		}
	}
	public ArrayList<ClassUser> selectRegisteredUser(){
		try{
			this.open();
			ArrayList<ClassUser> listActivities = new ArrayList<ClassUser>();
			Cursor queryCursor = mDb.query(mDbHelper.TABLE_Registered_Users, PROJECTION_Registered_Users, null, null, null, null, null, null);
			if(queryCursor == null){
				this.close();
				return null;
			}
			while(queryCursor.moveToNext()){
				ClassUser object = new ClassUser();
				object.activity_id = queryCursor.getInt(queryCursor.getColumnIndexOrThrow(mDbHelper.activity_id));
				object.Name = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.json_data));
				listActivities.add(object);
			}

			queryCursor.close();
			this.close();

			return listActivities;
		}
		catch(Exception e){
			Log.e("Database", e.getMessage());
			return null;
		}
	}
	public ArrayList<ClassUser> selectUsers(String vehicle_no ,String house_no){
		try{
			this.open();
			ArrayList<ClassUser> listActivities = new ArrayList<ClassUser>();
			String format = "%s=='%s' and %s=='%s'";
			String whereClause = String.format(format,mDbHelper.Vehicle_No,vehicle_no,mDbHelper.House_No,house_no);
//			String whereClause = category.trim().length() > 0 ? String.format("%s='%s'", mDbHelper.House_No, category) : null;
			Cursor queryCursor = mDb.query(mDbHelper.tableName_Users, PROJECTIONALL_Users,whereClause, null, null, null, null, null);
			//Cursor queryCursor = mDb.query(mDbHelper.tableName_Users, PROJECTIONALL_Users, null, null, null, null, null, null);
			if(queryCursor == null){
				this.close();
				return null;
			}
			while(queryCursor.moveToNext()){
				ClassUser object = new ClassUser();
				object.activity_id = queryCursor.getInt(queryCursor.getColumnIndexOrThrow(mDbHelper.activity_id));
				object.Name = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Name));
				object.Father_Name = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Father_Name));
				object.Family_No = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Family_No));
				object.Date_Of_Birth = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Date_Of_Birth));
				object.Address = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Address));
				object.District = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.District));
				object.City = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.City));
				object.IDno = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.IDno));
				object.House_No = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.House_No));
				object.Vehicle_No = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Vehicle_No));
				object.Vehicle_Color = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Vehicle_Color));
				object.Vehicle_Brand = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Vehicle_Brand));
				object.User_Type = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.User_Type));
				
				listActivities.add(object);
			}

			queryCursor.close();
			this.close();

			return listActivities;
		}
		catch(Exception e){
			Log.e("Database", e.getMessage());
			return null;
		}
	}
	public ArrayList<ClassUser> selectUsers(String vehicle_no ,String house_no, String category){
		try{
			this.open();
			ArrayList<ClassUser> listActivities = new ArrayList<ClassUser>();
			String format = "%s=='%s' and %s=='%s' and %s=='%s'";
			String whereClause = String.format(format,mDbHelper.Vehicle_No,vehicle_no,mDbHelper.House_No,house_no,mDbHelper.User_Type,category);
//			String whereClause = category.trim().length() > 0 ? String.format("%s='%s'", mDbHelper.House_No, category) : null;
			Cursor queryCursor = mDb.query(mDbHelper.tableName_Users, PROJECTIONALL_Users,whereClause, null, null, null, null, null);
			//Cursor queryCursor = mDb.query(mDbHelper.tableName_Users, PROJECTIONALL_Users, null, null, null, null, null, null);
			if(queryCursor == null){
				this.close();
				return null;
			}
			while(queryCursor.moveToNext()){
				ClassUser object = new ClassUser();
				object.activity_id = queryCursor.getInt(queryCursor.getColumnIndexOrThrow(mDbHelper.activity_id));
				object.Name = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Name));
				object.Father_Name = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Father_Name));
				object.Family_No = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Family_No));
				object.Date_Of_Birth = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Date_Of_Birth));
				object.Address = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Address));
				object.District = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.District));
				object.City = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.City));
				object.IDno = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.IDno));
				object.House_No = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.House_No));
				object.Vehicle_No = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Vehicle_No));
				object.Vehicle_Color = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Vehicle_Color));
				object.Vehicle_Brand = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Vehicle_Brand));
				object.User_Type = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.User_Type));
				
				listActivities.add(object);
			}

			queryCursor.close();
			this.close();

			return listActivities;
		}
		catch(Exception e){
			Log.e("Database", e.getMessage());
			return null;
		}
	}
	public String SelectDate()
	{
		try
		{
			this.open();
			String date="";
			Cursor queryCursor = mDb.query(mDbHelper.TABLE_Sync_Date, PROJECTION_Date, null, null, null, null, null, null);
			
			if(queryCursor == null)
			{
				this.close();
				return null;
			}
			while(queryCursor.moveToNext())
			{
					//object.ID = queryCursor.getInt(queryCursor.getColumnIndexOrThrow(mDbHelper.activity_id));
					date = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.date));
					
			}
			queryCursor.close();
			this.close();
			return date;
		}
		catch(Exception e)
		{
			Log.e("Database", e.getMessage());
			return "";
		}
	}
	
	public ArrayList<String> selectVehicleNumbers(){
		return selectWhere(true, new String[]{mDbHelper.Vehicle_No}, mDbHelper.TABLE_Sync_HouseNo_VehicleNo, null);
	}
	public ArrayList<String> selectHouseNumbers(){
		return selectWhere(true, new String[]{mDbHelper.House_No}, mDbHelper.TABLE_Sync_HouseNo_VehicleNo, null);
	}
	public ArrayList<String> selectWhere(boolean distinct, String[] projection, String tableName, String whereClause){
		try{
			this.open();
			ArrayList<String> ListValues = new ArrayList<String>();
			Cursor queryCursor = mDb.query(distinct, tableName, projection, whereClause, null, null, null, null, null);
			if(queryCursor == null){
				this.close();
				return null;
			}
			while(queryCursor.moveToNext()){
				String str = queryCursor.getString(0).trim();
				if(str.length()>0)
					ListValues.add(str);
			}
			queryCursor.close();
			this.close();
			return ListValues;
		}
		catch(Exception e){
			Log.e("Database", e.getMessage());
			return null;
		}
	}
	public String IsRegistered()
	{
		try
		{
			this.open();
			String UserRegistered="";
			Cursor queryCursor = mDb.query(mDbHelper.TABLE_User_Registered, PROJECTION_User_Registered, null, null, null, null, null, null);
			
			if(queryCursor == null)
			{
				this.close();
				return null;
			}
			while(queryCursor.moveToNext())
			{
					//object.ID = queryCursor.getInt(queryCursor.getColumnIndexOrThrow(mDbHelper.activity_id));
				UserRegistered = queryCursor.getString(queryCursor.getColumnIndexOrThrow(mDbHelper.Is_User_Registered));
					
			}
			queryCursor.close();
			this.close();
			return UserRegistered;
		}
		catch(Exception e)
		{
			Log.e("Database", e.getMessage());
			return "";
		}
	}
	
	public void resetUserHouseVehicle(){
		this.open();
		mDb.execSQL("delete from " + mDbHelper.TABLE_Sync_HouseNo_VehicleNo);
		this.close();
	}
	

	
}