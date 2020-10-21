package com.government.datakit.utils;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.res.AssetManager;
import android.os.AsyncTask;
import android.util.Log;

import com.government.datakit.interfaces.GDKInterface;
import com.government.datakit.prefrences.GDKPreferences;
import com.government.datakit.ui.R;

/**
 * 
 * @author gulfamhassan
 *
 */

public class GDKCopyFilesAsyncTask extends AsyncTask<String, Void, Boolean>{

	private ProgressDialog pdialog;
	private Context context;
	private GDKInterface gdkInterface;


	public GDKCopyFilesAsyncTask(){

	}

	public GDKCopyFilesAsyncTask(Context context, GDKInterface gdkInterface){
		this.context = context;
		this.gdkInterface = gdkInterface;
	}

	@Override
	protected Boolean doInBackground(String... params) {

		boolean isSuccessCopied = creatDirMoveFiles(params[0], params[1]);
		if(isSuccessCopied){
			//			 this.gdkInterface.fileSuccessCopied();
			return Boolean.TRUE;
		}
		return Boolean.FALSE;
	}

	@Override
	protected void onPostExecute(Boolean result) {
		super.onPostExecute(result);
		pdialog.dismiss();
		pdialog = null;
		if(result){
			this.gdkInterface.fileSuccessCopied();
		}else
			GDKPreferences.getPreferences().setAppVersionCode(context, 0);
	}

	@Override
	protected void onCancelled(){
		if (GDKPreferences.getPreferences().isAppFirstLaunch(context)) 
			GDKPreferences.getPreferences().setAppVersionCode(context, 0);
	}

	@Override
	protected void onPreExecute() {
		super.onPreExecute();
		pdialog = new ProgressDialog(this.context);
		pdialog.setCancelable(false);
		pdialog.setIcon(R.drawable.info_icon);
		pdialog.setTitle("Copying Files");
		pdialog.setMessage("Please Wait...");
		pdialog.show();
	}


	/**
	 * Used to create directory and copy html files 
	 * from assets to internal memory
	 * @param assetPath
	 * @param destinationPath
	 */
	private boolean creatDirMoveFiles(String assetPath, String destinationDirectory) {

		boolean isSuccessCopied = true;
		AssetManager assetManager = this.context.getAssets();
		String assets[] = null;
		try {

			assets = assetManager.list(assetPath);
			String fullPath = this.context.getFilesDir().getPath() + "/" + destinationDirectory;
			File dir = new File(fullPath);
			if (!dir.exists()){
				dir.mkdir();
			}    
			for (int i = 0; i < assets.length; i++) {
				boolean isCopied = copyFile(assetPath + "/" + assets[i], assets[i], destinationDirectory);
				if(!isCopied){
					isSuccessCopied = false;
					break;
				}
			}
			return isSuccessCopied;
		} catch (IOException ex) {
			Log.e("tag", "I/O Exception", ex);
			isSuccessCopied = false;
			return isSuccessCopied;
		}
	}

	/**
	 * Used to write files in internal memory
	 * @param filename
	 * @param destinationFile
	 */
	private boolean copyFile(String filename, String destinationFileName, String destinationDirectory) {

		AssetManager assetManager = this.context.getAssets();
		InputStream in = null;
		OutputStream out = null;
		try {

			Log.e("File Name> "+filename, "Destination Name> "+destinationFileName);
			in = assetManager.open(filename);
			String newFileName = this.context.getFilesDir().getPath() + "/" +destinationDirectory+ "/" + destinationFileName;

			Log.e("FULL NEW FILE", "<> "+newFileName);
			out = new FileOutputStream(newFileName);

			byte[] buffer = new byte[1024];
			int read;
			while ((read = in.read(buffer)) != -1) {
				out.write(buffer, 0, read);
			}
			in.close();
			in = null;
			out.flush();
			out.close();
			out = null;
			return true;
		} catch (Exception e) {
			Log.e("COPY FILE", e.getMessage());
			e.printStackTrace();
			return false;
		}
	}

}
