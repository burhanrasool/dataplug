package com.manateeworks.CNICScanner;

import android.widget.CheckBox;

public class ClassUser {
	public int activity_id;
	public String Name;
	public String Father_Name;
	public String Family_No;
	public String Date_Of_Birth;
	public String Address;
	public String District;
	public String City;
	public String IDno;
	public String House_No;
	public String Vehicle_No;
	public String Vehicle_Color;
	public String Vehicle_Brand;
	public String User_Type;
	public CheckBox isPresent = null;
	public boolean selected;
	public String User_Selected;
	public ClassUser(){
		reset();
	}
	public void reset(){
		activity_id = -1;
		Name ="";
		Father_Name ="";
		Family_No ="";
		Date_Of_Birth ="";
		Address ="";
		District ="";
		City ="";
		IDno ="";
		House_No ="";
		Vehicle_No ="";
		Vehicle_Color ="";
		Vehicle_Brand ="";
		User_Type ="";
		isPresent = null;
		selected=false;
		User_Selected="false";
	}

}