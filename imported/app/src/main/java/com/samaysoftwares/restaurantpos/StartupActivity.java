package com.samaysoftwares.restaurantpos;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;
import java.util.StringTokenizer;
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
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;


import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Environment;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

public class StartupActivity extends Activity {

	EditText ipEdit, deviceEdit;
	File f;
	File fdir;
	Info info = new Info();
	SharedPreferences pref;
	Dialog infoItemDialog;
	LinearLayout loginll;
	EditText usernameT;
	EditText passwordT;
	ProgressDialog pDialog;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);

		pref = getSharedPreferences("login", 0);
		pref.edit().clear().commit();
		pref = getSharedPreferences("com.samay.tabdesign",Context.MODE_PRIVATE);
		pref.edit().clear().commit();
		fdir = StartupActivity.this.getDir("Data", Context.MODE_PRIVATE);
		f = new File(fdir, "info.txt");
		if (f.exists()) {

			Scanner scanner = null;
			try {
				scanner = new Scanner(f);
			} catch (FileNotFoundException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			String ip = scanner.nextLine();
			String devicename = scanner.nextLine();
			info.setIP(ip);
			info.setDevice(devicename);
			new SendRequest().execute();
		}

		else {
			this.setContentView(R.layout.startup);
			ipEdit = (EditText) findViewById(R.id.editText1);
			deviceEdit = (EditText) findViewById(R.id.editText2);
			Button submit = (Button) findViewById(R.id.button1);
			submit.setOnClickListener(new OnClickListener() {

				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					BufferedWriter bw;
					try {
						bw = new BufferedWriter(new FileWriter(f, false));
						bw.write(ipEdit.getText().toString());
						bw.newLine();
						bw.write(deviceEdit.getText().toString());
						bw.close();
						info.setDevice(deviceEdit.getText().toString());
						info.setIP(ipEdit.getText().toString());
						new SendRequest().execute();
						
					} catch (Exception e) {
						
					}
				}
			});

		}

	}

	class SendRequest extends AsyncTask<String, String, String> {
		File fileApk;
		String fileName="";
		String responseString="";
		String output="";
		/**
		 * Before starting background thread Show Progress Dialog
		 * */
		@Override
		protected void onPreExecute() {
			super.onPreExecute();

			pDialog = new ProgressDialog(StartupActivity.this);
			pDialog.setMessage("Connecting to server....!");
			pDialog.setIndeterminate(false);
			pDialog.setCancelable(false);
			pDialog.show();
		}

		/**
		 * getting All products from url
		 * */
		
		String getFileName(){
			return fileName;
		}
		
		protected String doInBackground(String... args) {
			
					// TODO Auto-generated method stub
					HttpClient httpclient = new DefaultHttpClient();
					
					List<NameValuePair> params = new ArrayList<NameValuePair>();
					NameValuePair pair = new BasicNameValuePair("androidid",
							info.getDevice());
					params.add(pair);
					String paramString = URLEncodedUtils.format(params, "utf-8");
					String url = "http://" + info.getIP()
							+ "/android_deviceassignment.php";
					url += "?" + paramString;

					HttpGet httpget = new HttpGet(url);

					try {
						HttpResponse httpresponse = httpclient.execute(httpget);
						HttpEntity httpEntity = httpresponse.getEntity();
						InputStream is = httpEntity.getContent();
						BufferedReader reader = new BufferedReader(new InputStreamReader(
								is, "iso-8859-1"), 8);
						StringBuilder sb = new StringBuilder();
						String line = null;
						while ((line = reader.readLine()) != null) {
							sb.append(line + "");
						}

						StringTokenizer st = new StringTokenizer(sb.toString(), "#");
						Log.d("************************************",sb.toString());
						String tableid = (String) st.nextElement();
						String waiterid = (String) st.nextElement();

						if (!tableid.equals("-1")) {
							Intent myIntent = new Intent(StartupActivity.this,
									MenuRequest.class);
							myIntent.putExtra("ActivityToBeStarted", 0);
							StartupActivity.this.startActivity(myIntent);
							StartupActivity.this.finish();
						} else {

							Intent myIntent = new Intent(StartupActivity.this,
									MenuRequest.class);
							myIntent.putExtra("ActivityToBeStarted", 1);
							myIntent.putExtra("Waiterid", waiterid);
							StartupActivity.this.startActivity(myIntent);
							StartupActivity.this.finish();
						}

						is.close();
					} catch (Exception e) {
						// TODO Auto-generated catch block
						Log.e("***********************88",e.getMessage(),e);
						final String msg=e.getMessage();
						//final String cause=e.getCause().toString();
						runOnUiThread(new Runnable() {
							
							@Override
							public void run() {
								// TODO Auto-generated method stub
								Toast.makeText(StartupActivity.this, msg,Toast.LENGTH_LONG).show();
							
						
						WindowManager.LayoutParams wlp=new WindowManager.LayoutParams();
						infoItemDialog=new Dialog(StartupActivity.this,R.style.mydialogstyle);
						//infoItemDialog.setTitle("Event Details");
						infoItemDialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
						infoItemDialog.setContentView(R.layout.noconnection);
						
						loginll=(LinearLayout)infoItemDialog.findViewById(R.id.loginll);
						Button close=(Button)infoItemDialog.findViewById(R.id.close);
						Button configure=(Button)infoItemDialog.findViewById(R.id.configure);
						Button loginip=(Button)infoItemDialog.findViewById(R.id.loginip);
						usernameT=(EditText)infoItemDialog.findViewById(R.id.editText1);
						passwordT=(EditText)infoItemDialog.findViewById(R.id.editText2);
						
						
						loginip.setOnClickListener(new OnClickListener() {
							
							@Override
							public void onClick(View v) {
								// TODO Auto-generated method stub
								String id = usernameT.getText().toString();
								String pass = passwordT.getText().toString();
								
										if (id.equals("posadmin") && pass.equals("posadmin")){
											
											infoItemDialog.dismiss();
											StartupActivity.this.setContentView(R.layout.startup);
											ipEdit = (EditText) findViewById(R.id.editText1);
											deviceEdit = (EditText) findViewById(R.id.editText2);
											Button submit = (Button) findViewById(R.id.button1);
											submit.setOnClickListener(new OnClickListener() {

												@Override
												public void onClick(View v) {
													// TODO Auto-generated method stub
													BufferedWriter bw;
													try {
														
														Info info=new Info();
														
														bw = new BufferedWriter(new FileWriter(f, false));
														bw.write(ipEdit.getText().toString());
														bw.newLine();
														bw.write(deviceEdit.getText().toString());
														bw.close();
														info.setDevice(deviceEdit.getText().toString());
														info.setIP(ipEdit.getText().toString());
														new SendRequest().execute();
														
													} catch (Exception e) {
														
													}
												}
											});
										}
										else
										{
											runOnUiThread(new Runnable() {
												
												@Override
												public void run() {
													// TODO Auto-generated method stub
													Toast t = Toast.makeText(StartupActivity.this, "Invalid loginid/password",Toast.LENGTH_LONG);
													t.setGravity(Gravity.CENTER, 0, 0);
													t.show();
												}
											});
											
											
										}
							}
						});
						
						
						close.setOnClickListener(new OnClickListener() {
							
							@Override
							public void onClick(View v) {
								// TODO Auto-generated method stub
								StartupActivity.this.finish();
								
							}
						});
						
						configure.setOnClickListener(new OnClickListener() {
							
							@Override
							public void onClick(View v) {
								// TODO Auto-generated method stub
								//infoItemDialog.dismiss();
								
								loginll.setVisibility(View.VISIBLE);
								
							}
						});
						
						wlp.copyFrom(infoItemDialog.getWindow().getAttributes());
						wlp.width=WindowManager.LayoutParams.MATCH_PARENT;
						
						
						wlp.height=WindowManager.LayoutParams.WRAP_CONTENT;
						wlp.gravity=Gravity.CENTER;
						infoItemDialog.show();
						infoItemDialog.getWindow().setAttributes(wlp);
							}
						});
					}
					
			
		
			
			
				            
			return responseString; 
		}

		/**
		 * After completing background task Dismiss the progress dialog
		 * **/
		protected void onPostExecute(String file_url) {
				pDialog.dismiss();
			
		}
		
		}
	
	
	/*void sendRequest() {
		HttpClient httpclient = new DefaultHttpClient();
		
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		NameValuePair pair = new BasicNameValuePair("androidid",
				info.getDevice());
		params.add(pair);
		String paramString = URLEncodedUtils.format(params, "utf-8");
		String url = "http://" + info.getIP()
				+ "/android_deviceassignment.php";
		url += "?" + paramString;

		HttpGet httpget = new HttpGet(url);

		try {
			HttpResponse httpresponse = httpclient.execute(httpget);
			HttpEntity httpEntity = httpresponse.getEntity();
			InputStream is = httpEntity.getContent();
			BufferedReader reader = new BufferedReader(new InputStreamReader(
					is, "iso-8859-1"), 8);
			StringBuilder sb = new StringBuilder();
			String line = null;
			while ((line = reader.readLine()) != null) {
				sb.append(line + "\n");
			}

			StringTokenizer st = new StringTokenizer(sb.toString(), "#");
			String tableid = (String) st.nextElement();
			String waiterid = (String) st.nextElement();

			if (!tableid.equals("-1")) {
				Intent myIntent = new Intent(StartupActivity.this,
						MenuRequest.class);
				myIntent.putExtra("ActivityToBeStarted", 0);
				StartupActivity.this.startActivity(myIntent);
				StartupActivity.this.finish();
			} else {

				Intent myIntent = new Intent(StartupActivity.this,
						MenuRequest.class);
				myIntent.putExtra("ActivityToBeStarted", 1);
				myIntent.putExtra("Waiterid", waiterid);
				StartupActivity.this.startActivity(myIntent);
				StartupActivity.this.finish();
			}

			is.close();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			Log.e("***********************88",e.getMessage(),e);
			Toast.makeText(StartupActivity.this, e.getMessage()+ "  "+e.getCause(),Toast.LENGTH_LONG).show();
			
			WindowManager.LayoutParams wlp=new WindowManager.LayoutParams();
			infoItemDialog=new Dialog(StartupActivity.this,R.style.mydialogstyle);
			//infoItemDialog.setTitle("Event Details");
			infoItemDialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
			infoItemDialog.setContentView(R.layout.noconnection);
			
			loginll=(LinearLayout)infoItemDialog.findViewById(R.id.loginll);
			Button close=(Button)infoItemDialog.findViewById(R.id.close);
			Button configure=(Button)infoItemDialog.findViewById(R.id.configure);
			Button loginip=(Button)infoItemDialog.findViewById(R.id.loginip);
			usernameT=(EditText)infoItemDialog.findViewById(R.id.editText1);
			passwordT=(EditText)infoItemDialog.findViewById(R.id.editText2);
			
			
			loginip.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					String id = usernameT.getText().toString();
					String pass = passwordT.getText().toString();
					
							if (id.equals("posadmin") && pass.equals("posadmin")){
								
								infoItemDialog.dismiss();
								StartupActivity.this.setContentView(R.layout.startup);
								ipEdit = (EditText) findViewById(R.id.editText1);
								deviceEdit = (EditText) findViewById(R.id.editText2);
								Button submit = (Button) findViewById(R.id.button1);
								submit.setOnClickListener(new OnClickListener() {

									@Override
									public void onClick(View v) {
										// TODO Auto-generated method stub
										BufferedWriter bw;
										try {
											
											Info info=new Info();
											
											bw = new BufferedWriter(new FileWriter(f, false));
											bw.write(ipEdit.getText().toString());
											bw.newLine();
											bw.write(deviceEdit.getText().toString());
											bw.close();
											info.setDevice(deviceEdit.getText().toString());
											info.setIP(ipEdit.getText().toString());
											sendRequest();
											
										} catch (Exception e) {
											
										}
									}
								});
							}
							else
							{
								
								Toast t = Toast.makeText(StartupActivity.this, "Invalid loginid/password",Toast.LENGTH_LONG);
								t.setGravity(Gravity.CENTER, 0, 0);
								t.show();
								
							}
				}
			});
			
			
			close.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					StartupActivity.this.finish();
					
				}
			});
			
			configure.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					//infoItemDialog.dismiss();
					
					loginll.setVisibility(View.VISIBLE);
					
				}
			});
			
			wlp.copyFrom(infoItemDialog.getWindow().getAttributes());
			wlp.width=WindowManager.LayoutParams.MATCH_PARENT;
			
			
			wlp.height=WindowManager.LayoutParams.WRAP_CONTENT;
			wlp.gravity=Gravity.CENTER;
			infoItemDialog.show();
			infoItemDialog.getWindow().setAttributes(wlp);
		}
	}*/

}
