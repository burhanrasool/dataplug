package com.government.datakit.bo;

/**
 * 
 * @author gulfamhassan
 *
 */

public class FormsDataInfo {
	
	public int id;
	public String formData; 
	public String dateTime;
	public String location;
	public byte[] imageArray;
	public String imagePaths;
	public String locationSource;
	public String timeSource;
	public String formIconName;
	/**
	 * 
	 * 1 if it's save on internet unavailability
	 *
	 */
	public int autoSave; 
	
//	public byte[] imageArrayBefore;
//	public byte[] imageArrayAfter;

}
