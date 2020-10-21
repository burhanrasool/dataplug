package com.government.datakit.utils;

import java.util.Hashtable;

/**
 * 
 * @author gulfamhassan
 *
 */

public class Resources {

	private static Resources resources;
	public static Resources getResources(){
		if(resources == null){
			resources = new Resources();
		}return resources;
	}
	
	
	private Hashtable<String, byte[]> multiplePictureData = null;
	private Hashtable<String, String> multiplePicturePathData = null;
	
	public Hashtable<String, byte[]> getMultiplePictureData() {
		return multiplePictureData;
	}
	

	public Hashtable<String, String> getMultiplePicturePathData() {
		return multiplePicturePathData;
	}

	public void setMultiplePictureData(Hashtable<String, byte[]> multiplePictureData) {
		this.multiplePictureData = multiplePictureData;
	}
	public void setMultiplePicturePathData(Hashtable<String, String> multiplePictureData) {
		this.multiplePicturePathData = multiplePictureData;
	}
}
