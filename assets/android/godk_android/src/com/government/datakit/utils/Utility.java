package com.government.datakit.utils;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.TimeZone;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.graphics.Bitmap;
import android.graphics.Bitmap.CompressFormat;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.Paint.Style;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Build;
import android.os.Environment;
import android.provider.Settings.Secure;
import android.telephony.TelephonyManager;

import com.government.datakit.ui.R;

/**
 * 
 * @author gulfamhassan
 *
 */

public class Utility {



	/*public static byte[] getBytes(Bitmap bitmap){
    	Bitmap resizedBitmap = Bitmap.createScaledBitmap(bitmap, bitmap.getWidth() * 2, bitmap.getHeight() * 2, true);//createBitmap(bitmap, 0, 0, 500, 700, matrix, true);

    	SimpleDateFormat sdf = new SimpleDateFormat("dd-MM-yy  HH:mm");
        String dateTime = sdf.format(Calendar.getInstance().getTime()); // reading local time in the system

        Canvas cs = new Canvas(resizedBitmap);
        Paint tPaint = new Paint();
        tPaint.setTextSize(27);
        tPaint.setColor(Color.RED);
        tPaint.setStyle(Style.FILL);
        cs.drawText(dateTime, resizedBitmap.getWidth() - 200 ,resizedBitmap.getHeight() - 10, tPaint);

        ByteArrayOutputStream stream = new ByteArrayOutputStream();
        resizedBitmap.compress(CompressFormat.JPEG, 100, stream);
        return stream.toByteArray();
    }*/

	public static byte[] getBytes(Bitmap resizedBitmap ,String picturePath){
		File file = new File(picturePath);
		//    	bitmap=BitmapFactory.decodeFile(picturePath);    	
		//    	Bitmap resizedBitmap = Bitmap.createScaledBitmap(bitmap, bitmap.getWidth(), bitmap.getHeight(), true);//createBitmap(bitmap, 0, 0, 500, 700, matrix, true);

		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		// String dateTime = sdf.format(Calendar.getInstance().getTime()); // reading local time in the system
		String dateTime=sdf.format(file.lastModified());
		Canvas cs = new Canvas(resizedBitmap);
		Paint tPaint = new Paint();
		tPaint.setTextSize(27);
		tPaint.setColor(Color.RED);
		tPaint.setStyle(Style.FILL);
		cs.drawText(dateTime, resizedBitmap.getWidth() - 200 ,resizedBitmap.getHeight() - 10, tPaint);

		ByteArrayOutputStream stream = new ByteArrayOutputStream();
		resizedBitmap.compress(CompressFormat.JPEG, 100, stream);
		return stream.toByteArray();
	}

	/**
	 * Used to convert Bitmap to byte array
	 * @param bitmap
	 * @return
	 */
	public static byte[] getSimpleBytes(Bitmap bitmap){

		ByteArrayOutputStream stream = new ByteArrayOutputStream();
		bitmap.compress(CompressFormat.JPEG, 100, stream);
		return stream.toByteArray();
	}

	/**
	 * Used to convert byte array to Bitmap
	 * @param image
	 * @return
	 */
	public static Bitmap getImage(byte[] image){

		return BitmapFactory.decodeByteArray(image, 0, image.length);
	}

	/**
	 * Used to check Internet availability
	 * @param context
	 * @return
	 */
	public static boolean isInternetAvailable(Context context) {

		ConnectivityManager cm = (ConnectivityManager)context.getSystemService(Context.CONNECTIVITY_SERVICE);
		NetworkInfo netInfo = cm.getActiveNetworkInfo();
		if (netInfo != null && netInfo.isConnectedOrConnecting()) {
			return true;
		}
		return false;
	}

	/**
	 * Used to fromat current date
	 * @param date
	 * @return
	 */
	public static String getCurrentDate(Date date) {

		String dateTime = null;
		if(date==null)date=new Date();
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		dateTime = (sdf.format(date));
		return dateTime;
	}

	/**
	 * Used to show error in a dialog and exit App once user press OK button.
	 * 
	 */
	public static void showErrorDialog(final Context context, String message){
		AlertDialog.Builder builder = new AlertDialog.Builder(context);
		builder.setTitle(context.getResources().getString(R.string.app_name));
		builder.setMessage(message);
		builder.setCancelable(false);
		builder.setNeutralButton("OK", new DialogInterface.OnClickListener() {
			public void onClick(DialogInterface dialog, int id) {
				dialog.dismiss();
				((Activity) context).finish();				
			}
		});
		builder.create();
		builder.show();
	}

	/**
	 * Used to show message in a dialog and exit dialog once user press OK button.
	 * 
	 */
	public static void showInfoDialog(final Context context, String message){
		AlertDialog.Builder builder = new AlertDialog.Builder(context);
		builder.setTitle(context.getResources().getString(R.string.app_name));
		builder.setMessage(message);
		builder.setCancelable(false);
		builder.setNeutralButton("OK", null);
		builder.create();
		builder.show();
	}
	public static String getFormattedTime(long timeInMillis){
		 DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		 Calendar calendar=Calendar.getInstance();
		 calendar.setTimeZone(TimeZone.getTimeZone("Asia/Karachi"));
		 calendar.setTimeInMillis(timeInMillis);
		 return df.format(calendar.getTime());
	}
	
	public static void writeFileToSdCard(String content) {
        FileOutputStream fileOutputStream = null;
        try {
            File sdCard = Environment.getExternalStorageDirectory();
            File dir = new File(sdCard.getAbsolutePath());
            dir.mkdirs();
            File file = new File(dir, "dataPlugLogs.txt");

            fileOutputStream = new FileOutputStream(file);
            fileOutputStream.write(content.getBytes());
        } catch (Exception exception) {

        } finally {
            if (fileOutputStream != null) {
                try {
                    fileOutputStream.close();
                } catch (Exception e) {

                }
            }
        }

    }
	
	public static String getDeviceUniqueId(Context context) {
		String imei="";
		
		try {
			TelephonyManager telephonyManager = (TelephonyManager)context.getSystemService(Context.TELEPHONY_SERVICE);
			imei = telephonyManager.getDeviceId();
		}
		catch(Exception e) {
			System.out.print(e);
		}
		
		if(imei==null) {
			imei="";
			try {
				imei=Secure.getString(context.getContentResolver(),Secure.ANDROID_ID);
			}
			catch(Exception e) {
				imei="";
			}

		}
		
		return imei;
	}

}
