package com.samaysoftwares.restaurantpos;

public class Theme {
	static int layoutbackground;
	static int tabbackground;
	static int subcatbackground;
	static int itemsbackground;
	static int itemnametext;
	static int itemdesctext;
	static int itempricetext;
	static int toolbartext;
	static int subcattext;
	static int ordereditemtext;
	static int ordertext;
	static int totaltext;
	static int seltablebackground;
	static int tablebackground;
	static int tableselecticon;
	static int dialogbg;
	static int billdialog;
	static String bill;
	
	public Theme(String themename){
		if(themename.equals("black")){
			layoutbackground=R.drawable.capture;
			tabbackground=R.layout.tabs_bg;
			subcatbackground=0x11ffffff;
			itemsbackground=0x11ffffff;
			itemnametext=0xFFEEEEEE;
			itemdesctext=0xFF909192;
			itempricetext=0xFF909192;
			toolbartext=0xFFEEEEEE;
			subcattext=0xFFEEEEEE;
			ordereditemtext=0xFFEEEEEE;
			ordertext=0xFF4b80c3;
			totaltext=0xFF4b80c3;
			seltablebackground=0x00000000;
			tablebackground=0x11FFFFFF;
			tableselecticon=R.drawable.selectwhite;
			dialogbg=R.layout.iteminfo;
			billdialog=R.layout.bill_dialog_default;
			
		}
		if(themename.equals("orange")){
			layoutbackground=R.drawable.orange;
			tabbackground=R.layout.tabs_orange;
			subcatbackground=0x22000000;
			itemsbackground=0x22000000;
			itemnametext=0xFFEEEEEE;
			itemdesctext=0xFF202020;
			itempricetext=0xFF202020;
			toolbartext=0xFFEEEEEE;
			subcattext=0xFFEEEEEE;
			ordereditemtext=0xFFEEEEEE;
			ordertext=0xFF202020;
			totaltext=0xFF202020;
			seltablebackground=0x00000000;
			tablebackground=0x33000000;
			tableselecticon=R.drawable.selecttable1;
			dialogbg=R.layout.iteminfo_orange;
			billdialog=R.layout.bill_dialog_orange;
			
		}
		
		if(themename.equals("purple")){
			layoutbackground=R.drawable.purple3;
			tabbackground=R.layout.tab_purple;
			subcatbackground=0x22FFFFFF;
			itemsbackground=0x22FFFFFF;
			itemnametext=0xFFEEEEEE;
			itemdesctext=0xFFBBBBBB;
			itempricetext=0xFFBBBBBB;
			toolbartext=0xFFEEEEEE;
			subcattext=0xFFEEEEEE;
			ordereditemtext=0xFFBBBBBB;
			ordertext=0xFFEEEEEE;
			totaltext=0xFFEEEEEE;
			seltablebackground=0x00FFFFFF;
			tablebackground=0x33FFFFFF;
			tableselecticon=R.drawable.selectwhite;
			dialogbg=R.layout.iteminfo_purple;
			
		}
	}
}
