package com.manateeworks.CNICScanner;

public class ClassHouseVehicle {
	public int activity_id;
	
	public String House_No;
	public String Vehicle_No;

	public ClassHouseVehicle(){
		reset();
	}
	public void reset(){
		activity_id = -1;
		House_No ="";
		Vehicle_No ="";
	}

}