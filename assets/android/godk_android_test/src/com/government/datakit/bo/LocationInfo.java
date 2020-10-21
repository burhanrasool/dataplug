package com.government.datakit.bo;

import android.location.Location;



public class LocationInfo {

	private double latitude;
	private double longitude;
	private float accuracy;
	private long locationTime;
	private String provider;
	private String location="";
	private String locationSource="";
	private String timeSource="";
	private Location locationObj;
	
	
	public String getLocationProvider() {
		return provider;
	}
	public void setLocationProvider(String locationSource) {
		this.provider = locationSource;
		this.timeSource=locationSource;
		this.locationSource=locationSource;
	}
	
	public double getLatitude() {
		return latitude;
	}
	public void setLatitude(double latitude) {
		this.latitude = latitude;
	}
	public double getLongitude() {
		return longitude;
	}
	public void setLongitude(double longitude) {
		this.longitude = longitude;
	}
	public float getAccuracy() {
		return accuracy;
	}
	public void setAccuracy(float accuracy) {
		this.accuracy = accuracy;
	}
	public long getLocationTime() {
		return locationTime;
	}
	public void setLocationTime(long locationTime) {
		this.locationTime = locationTime;
		this.location=this.getLatitude()+","+this.getLongitude();
	}
	
	
	public String getLocationSource() {
		return locationSource;
	}
	public void setLocationSource(String locationSource) {
		this.locationSource = locationSource;
	}
	public String getLocation() {
		return location;
	}
	public void setLocation(String location) {
		this.location = location;
	}
	public String getTimeSource() {
		return timeSource;
	}
	public void setTimeSource(String timeSource) {
		this.timeSource = timeSource;
	}
	public Location getLocationObj() {
		return locationObj;
	}
	public void setLocationObj(Location locationObj) {
		this.locationObj = locationObj;
	} 
	public void setLocationTime(Long locationTime) {
		this.locationTime = locationTime;
	}
	
	
	
}
