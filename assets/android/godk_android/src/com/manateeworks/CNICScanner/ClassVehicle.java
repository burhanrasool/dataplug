package com.manateeworks.CNICScanner;

public class ClassVehicle {
	public int activity_id;

	public String Name;
	public String House_No;
	public String Vehicle_No;
	public String Vehicle_Brand;
	public String Vehicle_Colour;
	
	public ClassVehicle(){
		reset();
	}
	public void reset(){
		activity_id = -1;
		Name ="";
		House_No="";
		Vehicle_No="";
		Vehicle_Brand="N/A";
		Vehicle_Colour="";
	}
	public ClassVehicle(String qr){
		
		String [] Data = qr.split(",");
		Name=Data[1].trim();
		House_No=Data[0].trim();
		Vehicle_No=Data[2].trim();
		Vehicle_Brand=Data[4].trim();
		Vehicle_Colour=Data[3].trim();
	}
	public ClassVehicle(String name,String vehicle_no,String house_no){

		Name=name;
		House_No=house_no;
		Vehicle_No= vehicle_no;
		Vehicle_Brand="N/A";
	}
}