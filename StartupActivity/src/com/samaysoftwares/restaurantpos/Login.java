package com.samaysoftwares.restaurantpos;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.HttpStatus;
import org.apache.http.NameValuePair;
import org.apache.http.StatusLine;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.utils.URLEncodedUtils;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;

import com.samaysoftwares.restaurantpos.TableView.GetTableOrder;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

public class Login extends Activity {

	EditText loginid,password;
	Button login;
	SharedPreferences pref;
	String id="",pass="";
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.login);
		 pref = getSharedPreferences("login", 0);
		if (pref.contains("username"))
		{
			Intent i = new Intent(Login.this, WebAccess.class);
			startActivity(i);
			Login.this.finish();
		}
		
		
		loginid  =(EditText)findViewById(R.id.loginid);
		password = (EditText)findViewById(R.id.password);
		
		login = (Button)findViewById(R.id.login);
		login.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				
				Login.this.id = loginid.getText().toString();
				Login.this.pass = password.getText().toString();
				new LoginTask().execute();
				
				
				
				
			}
		});
		
	}
	class LoginTask extends AsyncTask<String, String, String> {
		StringBuilder sb;
		
		@Override
		protected void onPreExecute() {
			
		}

		protected String doInBackground(String... args) {
			Info info = new Info();
			HttpClient httpclient = new DefaultHttpClient();
			HttpResponse response;
			try {
				Log.d("url" , "http://"+ info.getIP()+ "/testTreeView1/android_custlogin.php?login="+id+"&password="+pass);
				response = httpclient.execute(new HttpGet("http://"+ info.getIP()+ "/testTreeView1/android_custlogin.php?login="+id+"&password="+pass));
				StatusLine statusLine = response.getStatusLine();
				if (statusLine.getStatusCode() == HttpStatus.SC_OK) {
					BufferedReader reader = new BufferedReader(new InputStreamReader(response.getEntity().getContent(), "iso-8859-1"), 8);
					StringBuilder sb = new StringBuilder();
					String line = null;
					while ((line = reader.readLine()) != null) {
						sb.append(line);
					}
					System.out.println("response :" + sb.toString());
					if (sb.toString().equals("invalid")){
						runOnUiThread(new Runnable() {
							public void run() {
								Toast t = Toast.makeText(Login.this, "Invalid loginid/password",5000);
								t.setGravity(Gravity.CENTER, 0, 0);
								t.show();
							}
						});
						
					}
					else
					{
						pref.edit().putString("username", sb.toString()).commit();
						
						Intent i = new Intent(Login.this, WebAccess.class);
						startActivity(i);
						
						Login.this.finish();
					}
				}
			} catch (ClientProtocolException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
				
			
			return "";
			
		}

		protected void onPostExecute(String file_url) {
			
		}
	}
}
