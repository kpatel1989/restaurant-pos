package com.samaysoftwares.restaurantpos;

public class Info {
	static String ip="Default";
	static String devicename="Default";
	
	public void setIP(String i){
		ip=i;
	}
	
	public void setDevice(String device){
		devicename=device;
	}
	
	public String getIP(){
		return ip;
	}
	
	public String getDevice(){
		return devicename;
	}
}
