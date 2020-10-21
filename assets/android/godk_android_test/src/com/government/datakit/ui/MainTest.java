package com.government.datakit.ui;


public class MainTest {

	
//	<<<<<<<<<<<<<CURRENT RUNNING SOLUTION>>>>>>>>>>
//	/**
//	 * Used to create directory and copy html files 
//	 * from assets to internal memory
//	 * @param assetPath
//	 * @param destinationPath
//	 */
//	private void copyFileOrDir(String assetPath, String destinationDirectory) {
//	    
//		AssetManager assetManager = this.getAssets();
//	    String assets[] = null;
//	    try {
//	    	
//	    	assets = assetManager.list(assetPath);
//	        String fullPath = this.getFilesDir().getPath() + "/" + destinationDirectory;
//	        File dir = new File(fullPath);
//	        if (!dir.exists()){
//	        	dir.mkdir();
//	        }    
//	        for (int i = 0; i < assets.length; i++) {
//	        	copyFile(assetPath + "/" + assets[i], assets[i]);
//	        }
//	    } catch (IOException ex) {
//	        Log.e("tag", "I/O Exception", ex);
//	    }
//	}
//
//	/**
//	 * Used to write files in internal memory
//	 * @param filename
//	 * @param destinationFile
//	 */
//	private void copyFile(String filename, String destinationFileName) {
//		
//	    AssetManager assetManager = this.getAssets();
//	    InputStream in = null;
//	    OutputStream out = null;
//	    try {
//	        in = assetManager.open(filename);
//	        String newFileName = this.getFilesDir().getPath() + "/" +this.getString(R.string.app_name)+ "/" + destinationFileName;
//	        
//	        Log.e("FULL NEW FILE", "<> "+newFileName);
//	        out = new FileOutputStream(newFileName);
//
//	        byte[] buffer = new byte[1024];
//	        int read;
//	        while ((read = in.read(buffer)) != -1) {
//	            out.write(buffer, 0, read);
//	        }
//	        in.close();
//	        in = null;
//	        out.flush();
//	        out.close();
//	        out = null;
//	    } catch (Exception e) {
//	        Log.e("COPY FILE", e.getMessage());
//	        e.printStackTrace();
//	    }
//	}
	
	
	
	
	
	
	
	
//	WORKING SOLUTION
	
	/*private void copyFileOrDir(String path) {
	    AssetManager assetManager = this.getAssets();
	    String assets[] = null;
	    try {
	        assets = assetManager.list(path);
	        if (assets.length == 0) {
	        	Log.i("Before Copy File","PATH>>> "+path);
	            copyFile(path);
	        } else {
//	            String fullPath = "/data/data/" + this.getPackageName() + "/" + path;//Original
	        	String fullPath = this.getFilesDir().getPath() + "/" + path;//Testing
	            Log.i("FULL PATH DIR", "<> "+fullPath);
	            File dir = new File(fullPath);
	            if (!dir.exists()){
	            	Log.i("DIR NAME", "<>"+dir.getName());
	                dir.mkdir();
	            }
	            
	            for (int i = 0; i < assets.length; ++i) {
	            	Log.i("------------", "----------");
	                copyFileOrDir(path + "/" + assets[i]);
	            }
	        }
	    } catch (IOException ex) {
	        Log.e("tag", "I/O Exception", ex);
	    }
	}

	private void copyFile(String filename) {
	    AssetManager assetManager = this.getAssets();

	    InputStream in = null;
	    OutputStream out = null;
	    try {
	        in = assetManager.open(filename);
//	        String newFileName = "/data/data/" + this.getPackageName() + "/" + filename;//Original
	        String newFileName = this.getFilesDir().getPath() + "/" + filename;//Test
	        
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
	    } catch (Exception e) {
	        Log.e("tag", e.getMessage());
	    }
	}*/
	
	
	
	
	
	
	
	
	
	
	
	
	// CODE TO MOVE FILES FROM ASSETS TO INTERNAL MEMORY
	
	/*private void copyAssets() {
	    
		AssetManager assetManager = getAssets();
	    String[] files = null;
	    try {
	        files = assetManager.list("");
	    } catch (IOException e) {
	        Log.e("tag", "Failed to get asset file list.", e);
	    }
	    for(String filename : files) {
	    	Log.i("FILE NAME", filename);
	        InputStream in = null;
	        OutputStream out = null;
	        try {
	          in = assetManager.open(filename);
	          File outFile = new File(getExternalFilesDir(null), filename);
	          out = new FileOutputStream(outFile);
	          copyFile(in, out);
	          in.close();
	          in = null;
	          out.flush();
	          out.close();
	          out = null;
	        } catch(IOException e) {
	            Log.e("tag", "Failed to copy asset file: " + filename, e);
	        }       
	    }
	}
	
	private void copyFile(InputStream in, OutputStream out) throws IOException {
	    byte[] buffer = new byte[1024];
	    int read;
	    while((read = in.read(buffer)) != -1){
	      out.write(buffer, 0, read);
	    }
	}*/
	
	
	
	//************************HTML Format*********************//
	
//	String form = "<!DOCTYPE html>"+ 
//	"<html>"+
//		"<head>"+
//			"<meta charset=\"UTF-8\">"+
//			"<meta name=\"viewport\" content=\"width=device-width; user-scalable=0;\" />"+
//			"<title>My HTML</title>"+
//		"</head>"+
//		"<body>"+
//			"<h1>MyHTML</h1>"+
//			"<p id=\"mytext\">Hello!</p>"+
//			
//			"<input type=\"button\" value=\"Say Hello\" onClick=\"showAndroidToast('Hello Android! This is from html.')\" />"+
//			"<input type=\"button\" value=\"Open Dialog\" onClick=\"openAndroidDialog()\" />"+
//			"<input type=\"button\" value=\"Take Photo\" onClick=\"takePicture()\" />"+
//				
//			"<script language=\"javascript\">"+
//				
//				"function showAndroidToast(toast) {"+
//					"AndroidFunction.showToast(toast);"+
//				"}"+
//						
//				"function openAndroidDialog() {"+
//					"AndroidFunction.openAndroidDialog();"+
//				"}"+
//						
//				"function takePicture() {"+
//					"AndroidFunction.takePicture();"+
//				"}"+
//    
//    			"function callFromActivity(msg){"+
//    				"document.getElementById(\"mytext\").innerHTML = msg;"+
//    			"}"+
//    		"</script>"+
//    	"</body>"+
//    "</html>";
}
