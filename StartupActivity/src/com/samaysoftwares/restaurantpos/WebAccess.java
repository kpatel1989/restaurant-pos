package com.samaysoftwares.restaurantpos;

import android.app.Activity;
import android.content.ComponentName;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.webkit.WebChromeClient;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

public class WebAccess extends Activity {

	public static Activity wa;
	EditText url;
	TextView go,back,signout;
	WebView webview;
	SharedPreferences pref;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.webaccess);
		wa = this;
		url = (EditText)findViewById(R.id.url);
		
		webview = (WebView)findViewById(R.id.webView1);
		webview.getSettings().setJavaScriptEnabled(true);
		webview.setWebViewClient(new WebViewClient());
		webview.requestFocus(View.FOCUS_DOWN);
		webview.loadUrl("http://www.google.com");
		
		go = (TextView)findViewById(R.id.go);
		go.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				String url1 = url.getText().toString();
				if (!url1.startsWith("http://"))
				{
					url1 = "http://" + url1;
				}
				webview.loadUrl(url1);
			}
		});
		
		back = (TextView)findViewById(R.id.back);
		back.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				Intent i = new Intent(com.samaysoftwares.restaurantpos.WebAccess.this, TableView.class);
				i.setFlags(Intent.FLAG_ACTIVITY_REORDER_TO_FRONT);
				startActivity(i);
			}
		});
		
		signout = (TextView)findViewById(R.id.signout);
		signout.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				pref = getSharedPreferences("login", 0);
				pref.edit().clear().commit();
				
				WebAccess.this.finish();
			}
		});
	}
	
	@Override
	public void onBackPressed() {
		// TODO Auto-generated method stub
		
        
        Intent i = new Intent(com.samaysoftwares.restaurantpos.WebAccess.this, TableView.class);
		i.setFlags(Intent.FLAG_ACTIVITY_REORDER_TO_FRONT);
		startActivity(i);
		
	}

	
	

}
