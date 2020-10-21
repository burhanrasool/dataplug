/*
 * Copyright (C) 2008 ZXing authors
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * A Derivative Work, changed by Manatee Works, Inc.
 *
 */

package com.manateeworks.CNICScanner;

import java.io.IOException;

import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Configuration;
import android.graphics.Rect;
import android.media.MediaPlayer;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.text.ClipboardManager;
import android.util.Log;
import android.view.Gravity;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.SurfaceHolder;
import android.view.SurfaceView;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;

import com.government.datakit.ui.R;
import com.manateeworks.BarcodeScanner;
import com.manateeworks.camera.CameraManager;


/**
 * The barcode reader activity itself. This is loosely based on the
 * CameraPreview example included in the Android SDK.
 */
@SuppressWarnings("deprecation")
public final class ActivityCapture extends Activity implements SurfaceHolder.Callback
{
	
	public static final boolean PDF_OPTIMIZED = true;

	// !!! Rects are in format: x, y, width, height !!!
	public static final Rect RECT_LANDSCAPE_1D = new Rect(0, 20, 100, 60);
	public static final Rect RECT_LANDSCAPE_2D = new Rect(20, 5, 60, 90);
	public static final Rect RECT_PORTRAIT_1D = new Rect(20, 0, 60, 100);
	public static final Rect RECT_PORTRAIT_2D = new Rect(20, 5, 60, 90);
	public static final Rect RECT_FULL_1D = new Rect(0, 0, 100, 100);
	public static final Rect RECT_FULL_2D = new Rect(20, 5, 60, 90);

	private static final int IP_ID = Menu.FIRST;
	@SuppressWarnings("unused")
	private static final int MAX_RESULT_IMAGE_SIZE = 150;
	@SuppressWarnings("unused")
	private static final float BEEP_VOLUME = 0.10f;
	@SuppressWarnings("unused")
	private static final long VIBRATE_DURATION = 200L;

	@SuppressWarnings("unused")
	private static final String PACKAGE_NAME = "com.manateeworks.cameraDemo";
	private ActivityCaptureHandler handler;

	public static String SERVER_IP_ADDRESS = "10.50.49.211";
	private View statusView;
	private View resultView;
	@SuppressWarnings("unused")
	private static MediaPlayer mediaPlayer;
	private byte[] lastResult;
	private boolean hasSurface;
	private InactivityTimer inactivityTimer;
	@SuppressWarnings("unused")
	private static boolean playBeep;
	@SuppressWarnings("unused")
	private static boolean vibrate;
	private boolean copyToClipboard;
	@SuppressWarnings("unused")
	private String versionName;
	@SuppressWarnings("unused")
	private ImageView debugImage;
	public static String lastStringResult;
	public String temp="";

	@SuppressWarnings("unused")
	private final DialogInterface.OnClickListener aboutListener = new DialogInterface.OnClickListener()
	{
		public void onClick(DialogInterface dialogInterface, int i)
		{
			Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(getString(R.string.license_url)));
			intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_WHEN_TASK_RESET);
			startActivity(intent);
			finish();
		}
	};

	@SuppressWarnings("unused")
	private final DialogInterface.OnClickListener moreListener = new DialogInterface.OnClickListener()
	{
		public void onClick(DialogInterface dialogInterface, int i)
		{
			Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(getString(R.string.mobi_url)));
			intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_WHEN_TASK_RESET);
			startActivity(intent);
			finish();
		}
	};



	public Handler getHandler()
	{
		return handler;
	}

	@Override
	public void onCreate(Bundle icicle)
	{
		super.onCreate(icicle);

		Window window = getWindow();
		window.addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
		setContentView(R.layout.capture);

		// register your copy of the mobiScan SDK with the given user name / key
		BarcodeScanner.MWBregisterCode(BarcodeScanner.MWB_CODE_MASK_25,     "username", "key");
		BarcodeScanner.MWBregisterCode(BarcodeScanner.MWB_CODE_MASK_39,     "username", "key");
		BarcodeScanner.MWBregisterCode(BarcodeScanner.MWB_CODE_MASK_93,     "username", "key");
		BarcodeScanner.MWBregisterCode(BarcodeScanner.MWB_CODE_MASK_128,    "username", "key");
		BarcodeScanner.MWBregisterCode(BarcodeScanner.MWB_CODE_MASK_AZTEC,  "username", "key");
		BarcodeScanner.MWBregisterCode(BarcodeScanner.MWB_CODE_MASK_DM,     "username", "key");
		BarcodeScanner.MWBregisterCode(BarcodeScanner.MWB_CODE_MASK_EANUPC, "username", "key");
		BarcodeScanner.MWBregisterCode(BarcodeScanner.MWB_CODE_MASK_PDF,    "BarcodeScanner", "0B86884C502D71C818074425486D4BC8");
		BarcodeScanner.MWBregisterCode(BarcodeScanner.MWB_CODE_MASK_QR,     "BarcodeScanner", "3712B4808B982B7E67DAABF31C1C0150");
		BarcodeScanner.MWBregisterCode(BarcodeScanner.MWB_CODE_MASK_RSS,    "username", "key");
		BarcodeScanner.MWBregisterCode(BarcodeScanner.MWB_CODE_MASK_CODABAR,"username", "key");


		// choose code type or types you want to search for



		if (PDF_OPTIMIZED) {
			BarcodeScanner.MWBsetDirection(BarcodeScanner.MWB_SCANDIRECTION_HORIZONTAL);
			BarcodeScanner.MWBsetActiveCodes(BarcodeScanner.MWB_CODE_MASK_PDF | BarcodeScanner.MWB_CODE_MASK_QR);
			BarcodeScanner.MWBsetScanningRect(BarcodeScanner.MWB_CODE_MASK_PDF,    RECT_LANDSCAPE_1D);
			BarcodeScanner.MWBsetScanningRect(BarcodeScanner.MWB_CODE_MASK_QR,     RECT_FULL_2D);
		} else {

			// Our sample app is configured by default to search both directions...
			BarcodeScanner.MWBsetDirection(BarcodeScanner.MWB_SCANDIRECTION_HORIZONTAL | BarcodeScanner.MWB_SCANDIRECTION_VERTICAL);
			// Our sample app is configured by default to search all supported barcodes...	
			BarcodeScanner.MWBsetActiveCodes(BarcodeScanner.MWB_CODE_MASK_25     | 
					BarcodeScanner.MWB_CODE_MASK_39     |
					BarcodeScanner.MWB_CODE_MASK_93     | 
					BarcodeScanner.MWB_CODE_MASK_128    | 
					BarcodeScanner.MWB_CODE_MASK_AZTEC  | 
					BarcodeScanner.MWB_CODE_MASK_DM     |
					BarcodeScanner.MWB_CODE_MASK_EANUPC | 
					BarcodeScanner.MWB_CODE_MASK_PDF    | 
					BarcodeScanner.MWB_CODE_MASK_QR     |
					BarcodeScanner.MWB_CODE_MASK_CODABAR| 
					BarcodeScanner.MWB_CODE_MASK_RSS);	

			// set the scanning rectangle based on scan direction(format in pct: x, y, width, height)
			BarcodeScanner.MWBsetScanningRect(BarcodeScanner.MWB_CODE_MASK_25,     RECT_FULL_1D);     
			BarcodeScanner.MWBsetScanningRect(BarcodeScanner.MWB_CODE_MASK_39,     RECT_FULL_1D);
			BarcodeScanner.MWBsetScanningRect(BarcodeScanner.MWB_CODE_MASK_93,     RECT_FULL_1D); 
			BarcodeScanner.MWBsetScanningRect(BarcodeScanner.MWB_CODE_MASK_128,    RECT_FULL_1D);
			BarcodeScanner.MWBsetScanningRect(BarcodeScanner.MWB_CODE_MASK_AZTEC,  RECT_FULL_2D);    
			BarcodeScanner.MWBsetScanningRect(BarcodeScanner.MWB_CODE_MASK_DM,     RECT_FULL_2D);    
			BarcodeScanner.MWBsetScanningRect(BarcodeScanner.MWB_CODE_MASK_EANUPC, RECT_FULL_1D);     
			BarcodeScanner.MWBsetScanningRect(BarcodeScanner.MWB_CODE_MASK_PDF,    RECT_FULL_1D);
			BarcodeScanner.MWBsetScanningRect(BarcodeScanner.MWB_CODE_MASK_QR,     RECT_FULL_2D);     
			BarcodeScanner.MWBsetScanningRect(BarcodeScanner.MWB_CODE_MASK_RSS,    RECT_FULL_1D);  
			BarcodeScanner.MWBsetScanningRect(BarcodeScanner.MWB_CODE_MASK_CODABAR,RECT_FULL_1D);

		}



		BarcodeScanner.MWBsetLevel(2);


		CameraManager.init(getApplication());
		resultView = findViewById(R.id.result_view);
		statusView = findViewById(R.id.status_view);

		handler = null;
		lastResult = null;
		hasSurface = false;
		inactivityTimer = new InactivityTimer(this);
	}

	@SuppressWarnings("unused")
	@Override
	protected void onResume()
	{
		super.onResume();
		resetStatusView();

		SurfaceView surfaceView = (SurfaceView) findViewById(R.id.preview_view);
		SurfaceHolder surfaceHolder = surfaceView.getHolder();
		if (hasSurface)
		{
			// The activity was paused but not stopped, so the surface still
			// exists. Therefore
			// surfaceCreated() won't be called, so init the camera here.
			initCamera(surfaceHolder);
		}
		else
		{
			// Install the callback and wait for surfaceCreated() to init the
			// camera.
			surfaceHolder.addCallback(this);
			surfaceHolder.setType(SurfaceHolder.SURFACE_TYPE_PUSH_BUFFERS);
		}


		int ver = BarcodeScanner.MWBgetLibVersion();
		int v1 = (ver >> 16);
		int v2 = (ver >> 8) & 0xff;
		int v3 = (ver & 0xff);
		String libVersion = "Lib version: " + String.valueOf(v1)+"."+String.valueOf(v2)+"."+String.valueOf(v3);
		//Toast.makeText(this, libVersion, Toast.LENGTH_LONG).show();

	}

	@Override
	protected void onPause()
	{
		super.onPause();
		if (handler != null)
		{
			handler.quitSynchronously();
			handler = null;
		}
		CameraManager.get().closeDriver();

	}

	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event)
	{
		if (keyCode == KeyEvent.KEYCODE_BACK || keyCode == KeyEvent.KEYCODE_HOME)
		{
			if (lastResult != null)
			{
				resetStatusView();
				if (handler != null)
				{
					handler.sendEmptyMessage(R.id.restart_preview);
				}
				return true;
			}
		}
		else
			if (keyCode == KeyEvent.KEYCODE_FOCUS || keyCode == KeyEvent.KEYCODE_CAMERA)
			{
				// Handle these events so they don't launch the Camera app
				return true;
			}
		return super.onKeyDown(keyCode, event);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu)
	{
		super.onCreateOptionsMenu(menu);
		menu.add(0, IP_ID, 0,R.string.menu_ip).setIcon(android.R.drawable.ic_menu_info_details);
		return true;
	}

	public boolean onOptionsItemSelected(MenuItem item)
	{
		switch (item.getItemId())
		{
		case IP_ID:
			//            try
			//            {
			//                PackageInfo info = getPackageManager().getPackageInfo(PACKAGE_NAME, 0);
			//                // Since we're paying to talk to the PackageManager anyway, it
			//                // makes sense to cache the app
			//                // version name here for display in the about box later.
			//                this.versionName = info.versionName;
			//            }
			//            catch (PackageManager.NameNotFoundException e)
			//            {
			//            }
			//
			//            AlertDialog.Builder builder = new AlertDialog.Builder(this);
			//            builder.setTitle(getString(R.string.title_about));
			//            builder.setMessage(getString(R.string.msg_about));
			//            builder.setIcon(R.drawable.launcher_icon);
			//            builder.setPositiveButton(R.string.button_open_license, aboutListener);
			//            builder.setNeutralButton(R.string.button_open_mobi, moreListener);
			//            builder.setNegativeButton(R.string.button_cancel, null);
			//            builder.show();
			showIPAlert();
			break;
		}
		return super.onOptionsItemSelected(item);
	}

	private void showIPAlert(){

		// get prompts.xml view
		LayoutInflater li = LayoutInflater.from(this);
		View promptsView = li.inflate(R.layout.ipalert_prompts, null);
		AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(this);
		// set prompts.xml to alertdialog builder
		alertDialogBuilder.setView(promptsView);
		final EditText userInput = (EditText) promptsView.findViewById(R.id.editTextDialogUserInput);
		// set dialog message
		alertDialogBuilder
		.setCancelable(false)
		.setPositiveButton("OK",
				new DialogInterface.OnClickListener() {
			public void onClick(DialogInterface dialog,int id) {
				// get user input and set it to result
				// edit text
				SERVER_IP_ADDRESS = userInput.getText().toString();
			}
		})
		.setNegativeButton("Cancel",
				new DialogInterface.OnClickListener() {
			public void onClick(DialogInterface dialog,int id) {
				dialog.cancel();
			}
		});
		// create alert dialog
		AlertDialog alertDialog = alertDialogBuilder.create();
		// show it
		alertDialog.show();
	}

	@Override
	public void onConfigurationChanged(Configuration config)
	{
		// Do nothing, this is to prevent the activity from being restarted when
		// the keyboard opens.
		super.onConfigurationChanged(config);
	}

	public void surfaceCreated(SurfaceHolder holder)
	{
		if (!hasSurface)
		{
			hasSurface = true;
			initCamera(holder);
		}
	}

	public void surfaceDestroyed(SurfaceHolder holder)
	{
		hasSurface = false;
	}

	public void surfaceChanged(SurfaceHolder holder, int format, int width, int height)
	{

	}

	/**
	 * A valid barcode has been found, so give an indication of success and show
	 * the results.
	 * 
	 * @param rawResult
	 *            The contents of the barcode.
	 * @param barcode
	 *            A greyscale bitmap of the camera data which was decoded.
	 */

	static CNIC cnic = null;
	static CNIC qr = null;
	static int CODE_ACTIVITY = 0;
	static final int CODE_QR = 1;
	static final int CODE_CNIC = 2;
	static final int CODE_REGISTER = 3;
	public void handleDecode(byte[] rawResult)
	{
		inactivityTimer.onActivity();
		lastResult = rawResult;
		statusView.setVisibility(View.GONE);
		resultView.setVisibility(View.VISIBLE);



		TextView formatTextView = (TextView) findViewById(R.id.format_text_view);
		formatTextView.setVisibility(View.VISIBLE);
		formatTextView.setText(getString(R.string.msg_default_format));

		TextView contentsTextView = (TextView) findViewById(R.id.contents_text_view);
		String s = "";
		cnic = new CNIC();


		int bcType = BarcodeScanner.MWBgetLastType();
		String typeName="";
		//Intent intent = null;
		switch (bcType) {

		case BarcodeScanner.FOUND_PDF:
			typeName = "PDF417";
			//if(CODE_ACTIVITY == CODE_CNIC){
				cnic = new CNIC(rawResult);
				
				JSONObject jObjectData = new JSONObject();


			    // Create Json Object using Facebook Data
			try {
				Log.i("IDno", "Data : "+cnic.getsIdNo());
				Log.i("Name", "Data : "+cnic.getsName());
				Log.i("Father_Name", "Data : "+cnic.getFatherName());
				Log.i("Family_No", "Data : "+cnic.getsFamilyNo());
				Log.i("Date_Of_Birth", "Data : "+cnic.getsDateOfBirth());
				Log.i("Address", "Data : "+cnic.getFullAddress());
				Log.i("District", "Data : "+cnic.getDistrict());
				Log.i("City", "Data : "+cnic.getCity());
				
				jObjectData.put("IDno", cnic.getsIdNo());
				jObjectData.put("Name",cnic.getsName());
				jObjectData.put("Father_Name",cnic.getFatherName());
				jObjectData.put("Family_No",cnic.getsFamilyNo());
				jObjectData.put("Date_Of_Birth",cnic.getsDateOfBirth());
				jObjectData.put("Address",cnic.getFullAddress());
				jObjectData.put("District",cnic.getDistrict());
				jObjectData.put("City",cnic.getCity());
			} catch (JSONException e) {
				e.printStackTrace();
			}
//			    jObjectData.put("first_name", first_name);
//			    jObjectData.put("last_name", last_name);
//			    jObjectData.put("email", email);
//			    jObjectData.put("username", username);
//			    jObjectData.put("birthday", birthday);
//			    jObjectData.put("gender", gender);
//			    jObjectData.put("location", place);
//			    jObjectData.put("display_photo", display_photo_url);
//				
				s = jObjectData.toString();
				Intent intent = new Intent();
				intent.putExtra("Result", s);
				setResult(RESULT_OK, intent);
				finish();
		case BarcodeScanner.FOUND_QR:
			typeName = "QR";
			
				qr = new CNIC(rawResult,true);
				//cnic.getsIdNo();
				
		}
	    /*new AlertDialog.Builder(ActivityCapture.this).setMessage(s)
	    .setPositiveButton("Proceed", new OnClickListener() {
			@Override
			public void onClick(DialogInterface arg0, int arg1) {
				Intent intent = new Intent(ActivityCapture.this, ActivitySubmit.class);
				startActivity(intent);
			}
		}).show();*/
		        contentsTextView.setText(s);
		if (bcType >= 0)
			formatTextView.setText("Format: "+typeName);


		if (copyToClipboard)
		{
			ClipboardManager clipboard = (ClipboardManager) getSystemService(CLIPBOARD_SERVICE);
			clipboard.setText(s);
		}
	}


	private void initCamera(SurfaceHolder surfaceHolder)
	{
		try
		{
			// Select desired camera resoloution. Not all devices supports all resolutions, closest available will be chosen
			// If not selected, closest match to screen resolution will be chosen
			// High resolutions will slow down scanning proccess on slower devices

			if (PDF_OPTIMIZED){
				CameraManager.setDesiredPreviewSize(1280, 720);
			} else {
				CameraManager.setDesiredPreviewSize(800, 480);	
			}


			if(CODE_ACTIVITY == CODE_QR)				
				CameraManager.get().openDriver(surfaceHolder, false,false);
			else
				CameraManager.get().openDriver(surfaceHolder, false,true);
		}
		catch (IOException ioe)
		{
			displayFrameworkBugMessageAndExit();
			return;
		}
		catch (RuntimeException e)
		{
			// Barcode Scanner has seen crashes in the wild of this variety:
			// java.?lang.?RuntimeException: Fail to connect to camera service
			displayFrameworkBugMessageAndExit();
			return;
		}
		if (handler == null)
		{
			handler = new ActivityCaptureHandler(this);
		}
	}

	private void displayFrameworkBugMessageAndExit()
	{
		AlertDialog.Builder builder = new AlertDialog.Builder(this);
		builder.setTitle(getString(R.string.app_name));
		builder.setMessage(getString(R.string.msg_camera_framework_bug));
		builder.setPositiveButton(R.string.button_ok, new DialogInterface.OnClickListener()
		{
			public void onClick(DialogInterface dialogInterface, int i)
			{
				finish();
			}
		});
		builder.show();
	}

	private void resetStatusView()
	{
		resultView.setVisibility(View.GONE);
		statusView.setVisibility(View.VISIBLE);
		statusView.setBackgroundColor(getResources().getColor(R.color.status_view));

		TextView textView = (TextView) findViewById(R.id.status_text_view);
		textView.setGravity(Gravity.LEFT | Gravity.CENTER_VERTICAL);
		textView.setTextSize(14.0f);
		textView.setText(R.string.msg_default_status);
		lastResult = null;
	}

}
