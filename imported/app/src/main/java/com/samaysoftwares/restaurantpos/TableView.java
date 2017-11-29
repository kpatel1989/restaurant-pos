package com.samaysoftwares.restaurantpos;

import java.io.BufferedReader;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.List;
import java.util.StringTokenizer;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.xpath.XPath;
import javax.xml.xpath.XPathConstants;
import javax.xml.xpath.XPathExpression;
import javax.xml.xpath.XPathFactory;

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
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

import com.samaysoftwares.restaurantpos.Login.LoginTask;
import com.samaysoftwares.restaurantpos.WaiterView.DeleteOrder;

import android.media.MediaPlayer;
import android.media.MediaPlayer.OnCompletionListener;
import android.os.AsyncTask;
import android.os.Bundle;
import android.preference.Preference;
import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Typeface;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.ColorDrawable;
import android.graphics.drawable.Drawable;
import android.util.TypedValue;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.view.animation.Animation.AnimationListener;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.FrameLayout.LayoutParams;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.BaseAdapter;
import android.widget.Gallery;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TabHost;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.Toast;
import android.widget.TabHost.OnTabChangeListener;
import android.widget.TabHost.TabSpec;
import android.widget.TextView;

public class TableView extends Activity implements OnTabChangeListener {
	TabHost tabhost;
	LinearLayout tab,main_back;
	FrameLayout itemDetail;
	TableLayout subCatLayout, itemLayout, orderItemLayout, orderedLayout;
	ViewGroup.LayoutParams tabLayout;
	int currentSelectedSubCat = 0;
	FrameLayout.LayoutParams itemLayParam;
	TextView totalPrice;
	int total = 0;
	String price;
	Button orderButton;
	TextView askbill,callwaiter,internetButton,orderedItemTV;
	SharedPreferences prefs;
	Info info = new Info();
	ScrollView paneitems;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		this.requestWindowFeature(Window.FEATURE_NO_TITLE);
		prefs = getSharedPreferences("com.samay.tabdesign",Context.MODE_PRIVATE);
		prefs.edit().putString("orderid", "-1").commit();
		setContentView(R.layout.activity_main);
		
		main_back=(LinearLayout)findViewById(R.id.main_back);
		main_back.setBackgroundDrawable(getResources().getDrawable(Theme.layoutbackground));
		
		subCatLayout = (TableLayout) findViewById(R.id.tablesubcategory);
		orderItemLayout = (TableLayout) findViewById(R.id.tableright);
		itemLayout = (TableLayout) findViewById(R.id.tl5);
		itemDetail = (FrameLayout) findViewById(R.id.item_detail_container1);
		orderedLayout = (TableLayout) findViewById(R.id.OrderedItems);
		// itemDetail.setBackgroundColor(0x88cbced0);
		tabhost = (TabHost) findViewById(R.id.tabhost);
		totalPrice = (TextView) findViewById(R.id.total);
		orderedItemTV = (TextView) findViewById(R.id.textView2);
		orderedItemTV.setTextColor(Theme.ordertext);
		
		paneitems=(ScrollView)findViewById(R.id.paneitems);
		
		paneitems.setBackgroundColor(Theme.itemsbackground);
		totalPrice.setCompoundDrawablesWithIntrinsicBounds(0, 0,R.drawable.ruppewhite, 0);
		totalPrice.setTextColor(Theme.totaltext);
		
		tabhost.setup();
		tabhost.setOnTabChangedListener(this);
		
		orderButton = (Button) findViewById(R.id.orderButton);
		orderButton.setOnClickListener(new OrderClick());
		callwaiter = (TextView)findViewById(R.id.callwaiter);
		askbill = (TextView)findViewById(R.id.askbill);
		internetButton = (TextView) findViewById(R.id.internetActivity);
		
		
		/* FONTS SET */
		
		
		Typeface tf1=Typeface.createFromAsset(getAssets(),"Ubuntu-L.ttf");
		totalPrice.setTypeface(tf1);
		orderedItemTV.setTypeface(tf1);
		
		callwaiter.setTypeface(tf1);
		askbill.setTypeface(tf1);
		internetButton.setTypeface(tf1);
		
		/* -----------------------------*/
		internetButton.setOnClickListener(new OnClickListener() {
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				SharedPreferences pref = getSharedPreferences("login", 0);
				if (pref.contains("username"))
				{
					Intent i = new Intent(TableView.this, WebAccess.class);
					i.setFlags(Intent.FLAG_ACTIVITY_REORDER_TO_FRONT);
					TableView.this.startActivity(i);
					
				}
				else
				{
					Intent i = new Intent(TableView.this, Login.class);
					startActivity(i);
					
				}
				
			}
		});
		
		
		askbill.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				String orderidSP = prefs.getString("orderid","-2").trim();
				if (Integer.parseInt(orderidSP)>0)
					new CheckOut().execute();
				else
					Toast.makeText(TableView.this,"You cannot checkout without placing an order...!", Toast.LENGTH_LONG).show();
					
					
				
				
			}
		});
		
		
		callwaiter.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				Toast resultToast = Toast.makeText(TableView.this,"Please wait. The waiter is coming.", Toast.LENGTH_LONG);
				resultToast.show();
				new CallWaiter().execute();
			}
		});
		addCategories();
		addSubCategories();
		
		
		new GetTableOrder().execute();
		
	}
	class CheckOut extends AsyncTask<String, String, String>{
		StringBuilder sb;
		@Override
		protected String doInBackground(String... params) {
			HttpClient httpclient = new DefaultHttpClient();
			HttpResponse response;
			try {
				String orderidSP = prefs.getString("orderid", "-2").trim();
				response = httpclient.execute(new HttpGet("http://"+ info.getIP()+ "/android_checkout.php?checkout=01&orderid="+ orderidSP.trim()));
				StatusLine statusLine = response.getStatusLine();
				if (statusLine.getStatusCode() == HttpStatus.SC_OK) {
					SharedPreferences pref = getSharedPreferences("login", 0);
					pref.edit().clear().commit();
					
					
				
					//*********************************************Dialog ends
					HttpClient httpclient1 = new DefaultHttpClient();
				    
				    List<NameValuePair> params1 = new ArrayList<NameValuePair>();
				    NameValuePair pair = new BasicNameValuePair("deviceid",info.getDevice());
				    params1.add(pair);
					String paramString = URLEncodedUtils.format(params1, "utf-8");
					String url = "http://"+info.getIP()+"/android_getbill.php";
					url += "?" + paramString;
					HttpGet httpget1 = new HttpGet(url);
					
					try {
						HttpResponse httpresponse1 = httpclient1.execute(httpget1);
						HttpEntity httpEntity1 = httpresponse1.getEntity();
						InputStream is = httpEntity1.getContent();
						BufferedReader reader = new BufferedReader(new InputStreamReader(is, "iso-8859-1"), 8);
						 sb = new StringBuilder();
						String line = null;
						while ((line = reader.readLine()) != null) {
							sb.append(line);
						}
						runOnUiThread(new Runnable() {
							
							@Override
							public void run() {
								// TODO Auto-generated method stub
								TableView.this.showbill(sb);
							}
						});
						
					}catch(Exception ee){
						
					}
					
					
					
				}
			} catch (ClientProtocolException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			return null;
			
			
		}
	}
	
	void showbill(StringBuilder sb){
		WindowManager.LayoutParams wlp = new WindowManager.LayoutParams();

		Dialog infoItemDialog = new Dialog(TableView.this);
		Typeface papyType=Typeface.createFromAsset(getAssets(),"papyrus.ttf");
		Typeface ubntuType=Typeface.createFromAsset(getAssets(),"Ubuntu-L.ttf");
		Typeface cuprumType=Typeface.createFromAsset(getAssets(),"Cuprum.ttf");
		
		infoItemDialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
		
			infoItemDialog.setContentView(Theme.billdialog);
		
		TableLayout billtable=(TableLayout)infoItemDialog.findViewById(R.id.billtablelayout);
		TextView totalPrice=(TextView)infoItemDialog.findViewById(R.id.textView2);
		TextView title=(TextView)infoItemDialog.findViewById(R.id.titleVT);
		TextView totalText=(TextView)infoItemDialog.findViewById(R.id.textView1);
		TextView itemnametext=(TextView)infoItemDialog.findViewById(R.id.itemnametext);
		TextView quantitytext=(TextView)infoItemDialog.findViewById(R.id.quantitytext);
		TextView pricetext=(TextView)infoItemDialog.findViewById(R.id.pricetext);
		Button checkoutButton=(Button)infoItemDialog.findViewById(R.id.checkoutButton);
		
		checkoutButton.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				if (WebAccess.wa!=null)
					WebAccess.wa.finish();
				Intent restartActivity=new Intent(TableView.this, TableView.class);
				
				TableView.this.startActivity(restartActivity);
				TableView.this.finish();
				
			}
		});
		
		title.setTypeface(papyType);
		totalText.setTypeface(ubntuType);
		quantitytext.setTypeface(ubntuType);
		itemnametext.setTypeface(ubntuType);
		pricetext.setTypeface(ubntuType);
		
		TableRow.LayoutParams billLParams;
		TableLayout.LayoutParams billTLParams;
		double total=0;
		
		StringTokenizer st = new StringTokenizer(sb.toString(),"+");
		orderedLayout.removeAllViews();
		while(st.hasMoreTokens()){
			String temp1 = st.nextToken();
			StringTokenizer st1 = new StringTokenizer(temp1, ",");
			//System.out.println("Ordered items:::"+st1.nextToken()+" "+st1.nextToken()+" "+st1.nextToken()+" "+st1.nextToken());
			System.out.println(st1.nextToken());
			
			totalPrice.setTypeface(ubntuType);
			
			TableRow tr=new TableRow(TableView.this);
			TextView billitemname=new TextView(TableView.this);
			billitemname.setText(st1.nextToken());
			billitemname.setTextColor(0xFFAAAAAA);
			billitemname.setTypeface(cuprumType);
			billitemname.setTextSize(18);
			//billitemname.setGravity(Gravity.CENTER);
			
			
			TextView billitemqty=new TextView(TableView.this);
			billitemqty.setText(st1.nextToken());
			billitemqty.setGravity(Gravity.CENTER);
			billitemqty.setTextColor(0xFFAAAAAA);
			billitemqty.setTypeface(cuprumType);
			billitemqty.setTextSize(18);
			
			double tempprice=Double.parseDouble(st1.nextToken());
			total=total+tempprice;
			TextView billitemprice=new TextView(TableView.this);
			billitemprice.setText(tempprice+"");
			billitemprice.setGravity(Gravity.CENTER);
			billitemprice.setTextColor(0xFFAAAAAA);
			billitemprice.setTypeface(cuprumType);
			billitemprice.setTextSize(18);
			
			tr.addView(billitemname);
			tr.addView(billitemqty);
			tr.addView(billitemprice);
			//tr.setBackgroundColor(0xFFFFFFFF);
			billtable.addView(tr);
			//tr.setBackgroundColor(0xFF554444);
			
			billTLParams=(TableLayout.LayoutParams)tr.getLayoutParams();
			billTLParams.weight=6;
			tr.setLayoutParams(billTLParams);
			
			billLParams=(TableRow.LayoutParams)billitemname.getLayoutParams();
			billLParams.weight=4;
			billLParams.gravity=Gravity.CENTER;
			
			billLParams.width=LayoutParams.MATCH_PARENT;
			//billitemname.setBackgroundColor(0xFF55DDDD);
			billitemname.setLayoutParams(billLParams);
			
			TableRow.LayoutParams billLParams1=(TableRow.LayoutParams)billitemqty.getLayoutParams();
			billLParams1.weight=1;
			billLParams1.gravity=Gravity.CENTER;
			billLParams1.width=LayoutParams.MATCH_PARENT;
			//billitemqty.setBackgroundColor(0xFF55BBBB);
			billitemqty.setLayoutParams(billLParams1);
			
			TableRow.LayoutParams billLParams2=(TableRow.LayoutParams)billitemprice.getLayoutParams();
			billLParams2.weight=1;
			billLParams2.gravity=Gravity.CENTER;
			billLParams2.width=LayoutParams.MATCH_PARENT;
			//billitemprice.setBackgroundColor(0xFF55CCCC);
			billitemprice.setLayoutParams(billLParams2);
		
			
			
			}
		totalPrice.setText(total+"");
		
		
		wlp.copyFrom(infoItemDialog.getWindow().getAttributes());
		wlp.width = LayoutParams.FILL_PARENT;

		wlp.height = LayoutParams.MATCH_PARENT;
		wlp.gravity = Gravity.CENTER_HORIZONTAL;
		infoItemDialog.show();
		
		infoItemDialog.getWindow().setAttributes(wlp);
		Toast resultToast = Toast.makeText(TableView.this,"Please wait while waiter brings you your bill. Thank you!", Toast.LENGTH_LONG);
		resultToast.show();
	}
	
	
	class CallWaiter extends AsyncTask<String, String, String> {
		StringBuilder sb;
		
		@Override
		protected void onPreExecute() {
			
		}

		protected String doInBackground(String... args) {
			Info info = new Info();
				
			HttpClient httpclient = new DefaultHttpClient();
			HttpResponse response;
			try {
				String devicename = info.getDevice();
				String url = "http://"+ info.getIP()+ "/android_waiterrequests.php?waiterrequests=1&devicename="+ devicename;
				System.out.println(url);
				response = httpclient.execute(new HttpGet("http://"+ info.getIP()+ "/android_waiterrequests.php?waiterrequests=1&devicename="+ devicename));
				StatusLine statusLine = response.getStatusLine();
				if (statusLine.getStatusCode() == HttpStatus.SC_OK) {
					 
					BufferedReader reader = new BufferedReader(new InputStreamReader(response.getEntity().getContent(), "iso-8859-1"), 8);
					System.out.println("response :" + reader.readLine());
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
	
	@Override
	public void onBackPressed() {
		// TODO Auto-generated method stub
		//super.onBackPressed();
	}
	
	class GetTableOrder extends AsyncTask<String, String, String> {
		StringBuilder sb;

		@Override
		protected void onPreExecute() {
			
		}

		protected String doInBackground(String... args) {
			
			HttpClient httpclient = new DefaultHttpClient();
		    
		    List<NameValuePair> params = new ArrayList<NameValuePair>();
		    NameValuePair pair = new BasicNameValuePair("devicename",info.getDevice());
		    params.add(pair);
			String paramString = URLEncodedUtils.format(params, "utf-8");
			String url = "http://"+info.getIP()+"/android_gettableorders.php";
			url += "?" + paramString;
			HttpGet httpget = new HttpGet(url);
			
			
				HttpResponse httpresponse;
				try {
					httpresponse = httpclient.execute(httpget);
					HttpEntity httpEntity = httpresponse.getEntity();
					InputStream is = httpEntity.getContent();
					BufferedReader reader = new BufferedReader(new InputStreamReader(is, "iso-8859-1"), 8);
					 sb = new StringBuilder();
					String line = null;
					while ((line = reader.readLine()) != null) {
						sb.append(line);
					}
					is.close();
				runOnUiThread(new Runnable() {
					
					@Override
					public void run() {
						// TODO Auto-generated method stub
						TableView.this.getTableOrder(sb);
					}
				});
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
	void getTableOrder(StringBuilder sb){
		
		
		orderItemLayout.removeAllViews();
			
		
		prefs.edit().putString("orderid","-1").commit();
		
			StringTokenizer st = new StringTokenizer(sb.toString(),"+");
			orderedLayout.removeAllViews();
			float total =0;
			while(st.hasMoreTokens()){
				String temp1 = st.nextToken();
				StringTokenizer st1 = new StringTokenizer(temp1, ",");
				TableRow orderedRow = new TableRow(TableView.this);
				 
				prefs.edit().putString("orderid",st1.nextToken()).commit();
				orderedRow.setLayoutParams(new TableLayout.LayoutParams(LayoutParams.MATCH_PARENT,LayoutParams.MATCH_PARENT));
				orderedRow.setWeightSum(5);
				
				TextView orderedItemName = new TextView(TableView.this);
				orderedItemName.setTextColor(Theme.ordereditemtext);
				orderedItemName.setText(st1.nextToken());
				orderedItemName.setPadding(5, 0, 0, 0);
				
				TextView orderedItemQty = new TextView(TableView.this);
				orderedItemQty.setText(st1.nextToken());
				orderedItemQty.setTextColor(Theme.ordereditemtext);
				
				total += (Integer.parseInt(orderedItemQty.getText().toString())*Float.parseFloat(st1.nextToken())); 
				totalPrice.setText("Total:  " + total);
				ImageView deleteOrder = new ImageView(TableView.this);
				deleteOrder.setOnClickListener(new DeleteOrder());
				deleteOrder.setBackgroundResource(R.drawable.ok);
				
				orderedRow.addView(orderedItemName);
				orderedRow.addView(orderedItemQty);
				orderedRow.addView(deleteOrder);
				orderedRow.setWeightSum(5);
				
				LinearLayout.LayoutParams insideViewLayout = (LinearLayout.LayoutParams) orderedItemName.getLayoutParams();
				insideViewLayout.weight = 4f;
				insideViewLayout.width = 0;
				orderedItemName.setLayoutParams(insideViewLayout);
				insideViewLayout = (LinearLayout.LayoutParams) orderedItemQty.getLayoutParams();
				insideViewLayout.weight = 0.5f;
				insideViewLayout.width = 0;
				orderedItemQty.setLayoutParams(insideViewLayout);
				insideViewLayout = (LinearLayout.LayoutParams) deleteOrder.getLayoutParams();
				insideViewLayout.weight = 0.5f;
				insideViewLayout.width = 0;
				deleteOrder.setLayoutParams(insideViewLayout);
				orderedLayout.addView(orderedRow);
                
			 }
			
			
			
		
	}
	class OrderClick implements View.OnClickListener {

		@Override
		public void onClick(View v) {
			// TODO Auto-generated method stub
			new OrderItems().execute();
			new CheckStatus().execute();
			
		}

	}
	
	
	class CheckStatus extends AsyncTask<String, String, String>{
		
		@Override
		protected void onPostExecute(String result) {
			// TODO Auto-generated method stub
			if(result.equals("9")){
				/*runOnUiThread(new Runnable() {
					
					@Override
					public void run() {
						// TODO Auto-generated method stub
						
					}
				});*/
				Toast.makeText(TableView.this,"There was something wrong with last order. Please order again ! Sorry for inconvenience.", Toast.LENGTH_LONG).show();
				Intent restartActivity=new Intent(TableView.this, TableView.class);
				TableView.this.startActivity(restartActivity);
				TableView.this.finish();
				
			}
			
			
		}

		String responseString="0";
		String output="";
		
		@Override
		protected String doInBackground(String... params) {
			// TODO Auto-generated method stub
			try {


				HttpClient httpclient = new DefaultHttpClient();
				HttpResponse response;
			
				while(!responseString.equals("9") && !responseString.equals("2")){
					try {
						Thread.sleep(10000);
					} catch (InterruptedException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					}
					System.out.println("http://"+ info.getIP()+ "/android_checkstatus.php?devicename="+ info.getDevice());
					response = httpclient.execute(new HttpGet("http://"+ info.getIP()+ "/android_checkstatus.php?devicename="+ info.getDevice()));
					//System.out.println("http://" + info.getIP()+ "/android_order.php?devicename="+ info.getDevice() + "&" + orderQty + "&"+ orderidSP.trim() + "&" + userid);
					StatusLine statusLine = response.getStatusLine();
					if (statusLine.getStatusCode() == HttpStatus.SC_OK) {
						ByteArrayOutputStream out = new ByteArrayOutputStream();
						response.getEntity().writeTo(out);
						out.close();
						responseString = out.toString();
						System.out.println("res" + responseString);
				
					} else {
	
						response.getEntity().getContent().close();
						throw new IOException(statusLine.getReasonPhrase());
					}
					
				}
			} catch (ClientProtocolException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			return responseString;
		}
		
	}

	class OrderItems extends AsyncTask<String, String, String> {

		@Override
		protected void onPostExecute(String result) {
			// TODO Auto-generated method stub
			final String result1 = result;
			System.out.println("Result:"+result);
			TableView.this.runOnUiThread(new Runnable() {

				@Override
				public void run() {
					// TODO Auto-generated method stub

					if (result1.equals("Error")) {

					} else {

						String orderidSP = prefs.getString("orderid", "-2");
						if (orderidSP.equals("-1") || orderidSP.equals("-2")) {
							prefs.edit().putString("orderid", result1.trim())
									.commit();
						}

						for (int count = 0; count < orderItemLayout.getChildCount(); count++) {
							TableRow tempRow = (TableRow) orderItemLayout.getChildAt(count);
							tempRow.removeViewAt(2);
							TableRow newRow = new TableRow(TableView.this);

							ImageView done = new ImageView(TableView.this);
							done.setBackgroundResource(R.drawable.ok);

							TextView itemName = new TextView(TableView.this);
							itemName.setText(((TextView) tempRow.getChildAt(0))
									.getText());
							itemName.setPadding(5, 0, 0, 0);

							TextView itemQty = new TextView(TableView.this);
							itemQty.setText(((TextView) tempRow.getChildAt(1)).getText());

							newRow.addView(itemName);
							newRow.addView(itemQty);
							newRow.addView(done);

							itemName.setTextColor(Theme.ordereditemtext);
							itemQty.setTextColor(Theme.ordereditemtext);
							
							newRow.setWeightSum(5);
							orderedLayout.addView(newRow);

							TableRow.LayoutParams insideViewLayout = (TableRow.LayoutParams) itemName
									.getLayoutParams();
							insideViewLayout.weight = 4.4f;
							insideViewLayout.width =0;
							itemName.setLayoutParams(insideViewLayout);

							insideViewLayout = (TableRow.LayoutParams) itemQty
									.getLayoutParams();
							insideViewLayout.weight = 0.5f;
							insideViewLayout.width = 0;
							itemQty.setLayoutParams(insideViewLayout);

							insideViewLayout = (TableRow.LayoutParams) done
									.getLayoutParams();
							insideViewLayout.weight = 0.1f;
							insideViewLayout.width = 0;
							insideViewLayout.width = LayoutParams.WRAP_CONTENT;
							done.setLayoutParams(insideViewLayout);

						}
						orderItemLayout.removeAllViews();
					}

				}
			});
		}

		@Override
		protected String doInBackground(String... params) {
			// TODO Auto-generated method stub
			String responseString;
			String output = "";
			String orderQty = "order=";
			int orderCount = orderItemLayout.getChildCount();

			if (orderCount <= 0) {
				TableView.this.runOnUiThread(new Runnable() {

					@Override
					public void run() {
						// TODO Auto-generated method stub
						Toast resultToast = Toast.makeText(TableView.this,"No New Order Found !!", Toast.LENGTH_LONG);
						resultToast.show();

					}
				});
			}

			else {
				try {

					for (int k = 0; k < orderCount; k++) {
						TableRow temp = (TableRow) orderItemLayout
								.getChildAt(k);
						int id = temp.getId();
						TextView qtyView = (TextView) temp.getChildAt(1);
						
						int qty = Integer
								.parseInt(qtyView.getText().toString());
						orderQty = orderQty + id + "-" + qty + "*";
					}

					HttpClient httpclient = new DefaultHttpClient();
					HttpResponse response;
 

					String orderidSP = "orderid="+ prefs.getString("orderid", "-2").trim();
					SharedPreferences pref = getSharedPreferences("login", 0);
					String userid = "userid="+pref.getString("username","0");
					response = httpclient.execute(new HttpGet("http://"+ info.getIP()+ "/android_order.php?devicename="+ info.getDevice() + "&" + orderQty + "&"+ orderidSP.trim() + "&" + userid));
					System.out.println("http://" + info.getIP()+ "/android_order.php?devicename="+ info.getDevice() + "&" + orderQty + "&"+ orderidSP.trim() + "&" + userid);
					StatusLine statusLine = response.getStatusLine();
					if (statusLine.getStatusCode() == HttpStatus.SC_OK) {
						ByteArrayOutputStream out = new ByteArrayOutputStream();
						response.getEntity().writeTo(out);
						out.close();
						responseString = out.toString();
						output = output + responseString + "\n";
						
						return responseString;

					} else {

						response.getEntity().getContent().close();
						throw new IOException(statusLine.getReasonPhrase());
					}
				} catch (ClientProtocolException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
			return "Error";
		}
	}

	void addCategories() {
		try {

			File fdir = TableView.this.getDir("data", Context.MODE_PRIVATE);
			File f = new File(fdir, "menuitem.xml");
			DocumentBuilderFactory dbFactory = DocumentBuilderFactory
					.newInstance();
			DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
			Document doc = dBuilder.parse(f);

			doc.getDocumentElement().normalize();


			NodeList nList = doc.getElementsByTagName("rootcat");


			for (int temp = 0; temp < nList.getLength(); temp++) {

				Node nNode = nList.item(temp);


				if (nNode.getNodeType() == Node.ELEMENT_NODE) {

					Element eElement = (Element) nNode;

					String rootCatID = eElement.getAttribute("id");
					String rootCatName = eElement.getAttribute("name");

					TabSpec ts = TableView.this.tabhost.newTabSpec(rootCatID);

					ts.setIndicator(getViewForName(rootCatName));
					
					ts.setContent(R.id.tab1);
					tabhost.addTab(ts);
				}
			}
			tabhost.setCurrentTab(1);
			tabhost.setCurrentTab(0);

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	void addSubCategories() {

	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_main, menu);
		return true;
	}

	View getViewForName(String name) {
		View view = LayoutInflater.from(this).inflate(Theme.tabbackground, null);
//**********************************************
		Typeface tf=Typeface.createFromAsset(getAssets(),"Cuprum.ttf");
		TextView tv = (TextView) view.findViewById(R.id.tabsText);
		
		tv.setText(name);
		tv.setTypeface(tf);
		tv.setTextSize(TypedValue.COMPLEX_UNIT_SP, 18);
		tv.setGravity(Gravity.CENTER);

		return view;
	}

	class DeleteOrder implements View.OnClickListener {

		@Override
		public void onClick(View v) {
			// TODO Auto-generated method stub
			try {
				TableRow existedRow = (TableRow) orderItemLayout.findViewById(((TableRow) (v.getParent())).getId());

				TextView existedRowQty = (TextView) (existedRow.getChildAt(1));
				int qty = Integer.parseInt(existedRowQty.getText().toString());
				qty = qty - 1;
				if (qty <= 0) {
					qty = 0;
					orderItemLayout.removeView(existedRow);
				}
				existedRowQty.setText(qty + "");
				File fdir = TableView.this.getDir("data",Context.MODE_PRIVATE);
				File f = new File(fdir, "menuitem.xml");
				DocumentBuilderFactory dbFactory = DocumentBuilderFactory
						.newInstance();
				DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
				Document doc = dBuilder.parse(f);

				doc.getDocumentElement().normalize();

				XPathFactory xPathfactory = XPathFactory.newInstance();
				XPath xpath = xPathfactory.newXPath();
				XPathExpression expr1 = xpath
						.compile("//Menu/rootcat/subcat/item[@id="
								+ existedRow.getId() + "]/price");
				Double tempINt = (Double) expr1.evaluate(doc,
						XPathConstants.NUMBER);
				total = total - tempINt.intValue();
				if (total <= 0) {
					total = 0;
				}
				totalPrice.setText("Total:  " + total);
			} catch (Exception e) {
				e.printStackTrace();
			}
		}

	}

	class SubCatListener implements View.OnClickListener {
		
		@Override
		public void onClick(View v) {
			// TODO Auto-generated method stub
			final String subCatSelId = v.getId() + "";
			// int count=0;

			if (currentSelectedSubCat != 0)
				findViewById(currentSelectedSubCat).setBackgroundColor(0x00000000);
			currentSelectedSubCat = v.getId();
			findViewById(currentSelectedSubCat).setBackgroundColor(Theme.subcatbackground);
			
			
			
			//****************************************************************88

			try {

				File fdir = TableView.this.getDir("data",
						Context.MODE_PRIVATE);
				File f = new File(fdir, "menuitem.xml");
				DocumentBuilderFactory dbFactory = DocumentBuilderFactory
						.newInstance();
				DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
				Document doc = dBuilder.parse(f);

				itemLayout.setVisibility(View.INVISIBLE);
				itemLayout.removeAllViews();

				doc.getDocumentElement().normalize();

				NodeList nList = doc.getElementsByTagName("item");
				for (int temp = 0; temp < nList.getLength(); temp++) {

					Node nNode = nList.item(temp);


					if (nNode.getNodeType() == Node.ELEMENT_NODE) {

						Element eElement = (Element) nNode;

						int itemID = Integer.parseInt(eElement
								.getAttribute("id"));
						String itemParentID = eElement
								.getAttribute("categoryid");
						String itemName = eElement.getAttribute("name");
						String fullImagesPath = eElement
								.getElementsByTagName("images").item(0)
								.getTextContent();
						String itemDesc = eElement
								.getElementsByTagName("description").item(0)
								.getTextContent();
						price = eElement.getElementsByTagName("price").item(0)
								.getTextContent();

						if (subCatSelId.equals(itemParentID)) {

							TableRow subCatRow = new TableRow(TableView.this);
							itemLayout.addView(subCatRow);
							
							TableLayout.LayoutParams subcatlp = (TableLayout.LayoutParams)subCatRow.getLayoutParams();
							subcatlp.width = TableLayout.LayoutParams.MATCH_PARENT;
							subcatlp.height = TableLayout.LayoutParams.MATCH_PARENT;
							subCatRow.setLayoutParams(subcatlp);
							subCatRow.setWeightSum(10);
							
							
							ImageView itemImage = new ImageView(TableView.this);
							subCatRow.addView(itemImage);
							LinearLayout itemFrame = new LinearLayout(TableView.this);
							subCatRow.addView(itemFrame);
							LinearLayout btnFrame = new LinearLayout(TableView.this);
							subCatRow.addView(btnFrame);

							TableRow.LayoutParams imageparams = (TableRow.LayoutParams) itemImage.getLayoutParams();
							imageparams.weight = 2;
							imageparams.width =0;
							itemImage.setLayoutParams(imageparams);
							
							TableRow.LayoutParams frameparams = (TableRow.LayoutParams) itemFrame.getLayoutParams();
							frameparams.weight = 6.5f;
							frameparams.width = 0;
							frameparams.height = 150;
							itemFrame.setLayoutParams(frameparams);
							itemFrame.setOrientation(LinearLayout.VERTICAL);
							
							TableRow.LayoutParams btnparams = (TableRow.LayoutParams) btnFrame.getLayoutParams();
							btnparams.weight = 1.5f;
							btnparams.width = 0;
							btnparams.height = 150;
							btnparams.setMargins(0,5,0,0);
							btnFrame.setLayoutParams(btnparams);
							btnFrame.setOrientation(LinearLayout.VERTICAL);
							
							
							TextView itemDescText = new TextView(TableView.this);
							TextView itemNameText = new TextView(TableView.this);
							
							itemFrame.addView(itemNameText);
							LinearLayout.LayoutParams frameLLparams = (LinearLayout.LayoutParams) itemNameText.getLayoutParams();
							//frameLLparams.width = LinearLayout.LayoutParams.WRAP_CONTENT;
							frameLLparams.setMargins(0,8,0,0);
//                            frameLLparams.height = 100;
							itemNameText.setLayoutParams(frameLLparams);
							
							itemFrame.addView(itemDescText);
							LinearLayout.LayoutParams desclp = (LinearLayout.LayoutParams) itemDescText.getLayoutParams();
							
							desclp.setMargins(0,8,0,0);
							itemDescText.setLayoutParams(desclp);
							
							Button addImage = new Button(TableView.this);
							TextView priceText = new TextView(TableView.this);
							
							btnFrame.addView(priceText);
							btnFrame.addView(addImage);
							
							addImage.setLayoutParams(new TableRow.LayoutParams(25, 25));
							
							subCatRow.setPadding(0, 0, 0, 10);
							subCatRow.setId(itemID);
							
							subCatRow.setOnClickListener(new ItemClk());
							if (fullImagesPath.equals("")) {
								Bitmap btmp = BitmapFactory.decodeResource(getResources(), R.drawable.food2);
								Bitmap resizedbitmap = Bitmap.createScaledBitmap(btmp, 60, 60, true);
								itemImage.setImageBitmap(resizedbitmap);
							} else {
								StringTokenizer imageTokens = new StringTokenizer(fullImagesPath, ",");

								String firstImage = imageTokens.nextToken();
								String itemImgPath = firstImage.substring(firstImage.lastIndexOf("/") + 1);

								File imagedir = TableView.this.getDir("data", Context.MODE_PRIVATE);
								File image = new File(imagedir, itemImgPath);

								if (image.exists()) {
									if (image.isFile()) {
										Bitmap bmp = new BitmapDrawable(image.getAbsolutePath()).getBitmap();
										if (bmp == null) {
											itemImage.setBackgroundResource(R.drawable.food2);
										} else {
											Bitmap resizedbitmap = Bitmap.createScaledBitmap(bmp,60, 60, true);
											itemImage.setImageBitmap(resizedbitmap);
										}
									} else
										itemImage.setBackgroundResource(R.drawable.food2);
								} else {
									itemImage.setBackgroundResource(R.drawable.food2);
								}
							}
							// itemImage.setBackgroundResource(R.layout.imageback);
							itemImage.setPadding(10, 10, 10, 0);
							//itemImage.setBackgroundDrawable(getResources().getDrawable(R.drawable.image_border));
							// Log.d("Subcat row", subCatRow.toString());
								
							
							Typeface tf=Typeface.createFromAsset(getAssets(),"Cuprum.ttf");
							itemDescText.setTypeface(tf);
							itemNameText.setTypeface(tf);
							itemNameText.setText(itemName);
							itemNameText.setTextColor(Theme.itemnametext);
							itemNameText.setTextSize(20);
							itemNameText.setPadding(10, 0, 0, 0);
							
							String tempdesctext=itemDesc;
							if(tempdesctext.length()>30)
								tempdesctext=itemDesc.substring(0,30)+"...";
							
							itemDescText.setText(tempdesctext);
							itemDescText.setTextColor(Theme.itemdesctext);
							itemDescText.setPadding(10, 0, 0, 0);
							itemDescText.setTextSize(16);
							
							
							
							// itemNameText.setLayoutParams(new
							// LinearLayout.LayoutParams(android.widget.LinearLayout.LayoutParams.FILL_PARENT,
							// android.widget.LinearLayout.LayoutParams.WRAP_CONTENT));
							// itemDescText.setLayoutParams(new
							// LinearLayout.LayoutParams(android.widget.LinearLayout.LayoutParams.FILL_PARENT,
							// android.widget.LinearLayout.LayoutParams.WRAP_CONTENT));

							
							Typeface tf1=Typeface.createFromAsset(getAssets(),"Corbel.ttf");
							
							priceText.setTextColor(Theme.itempricetext);
							priceText.setCompoundDrawablesWithIntrinsicBounds(
									R.drawable.rupeeswhite, 0, 0, 0);
							priceText.setTextSize(16);
							priceText.setTypeface(tf1, Typeface.BOLD);
							priceText.setText(price);
							priceText.setPadding(0, 5, 0, 15);

							
							// btnFrame.setBackgroundColor(0xFF4444FF);

							

							addImage.setBackgroundResource(R.drawable.add_button_selector);
							addImage.setOnClickListener(new OnClickListener() {

								@Override
								public void onClick(View v) {

									MediaPlayer mp = MediaPlayer.create(
											TableView.this, R.raw.click);
									mp.setVolume(0.1f, 0.1f);
									mp.setOnCompletionListener(new OnCompletionListener() {

										@Override
										public void onCompletion(MediaPlayer mp) {
											// TODO Auto-generated method stub
											mp.release();
										}

									});
									// mp.start();

									int existFlag = 0;
									LinearLayout priceLay, nameLay;
									priceLay = (LinearLayout) v.getParent();
									TableRow itemIdRow = (TableRow) priceLay.getParent();
									int selectedItemID = itemIdRow.getId();
									nameLay = (LinearLayout) (itemIdRow)
											.getChildAt(1);
									String nameForClicked = ((TextView) nameLay
											.getChildAt(0)).getText()
											.toString();

									for (int k = 0; k < orderItemLayout
											.getChildCount(); k++) {
										TableRow tempRow = (TableRow) orderItemLayout
												.getChildAt(k);
										int tempID = tempRow.getId();
										if (selectedItemID == tempID)
											existFlag = 1;
									}

									if (existFlag == 0) {
										TableRow orderedRow = new TableRow(
												TableView.this);
										// LinearLayout rowLayout=new
										// LinearLayout(MainActivity.this);
										// TableLayout.LayoutParams rowparam=
										orderedRow
												.setLayoutParams(new TableLayout.LayoutParams(
														LayoutParams.MATCH_PARENT,
														LayoutParams.MATCH_PARENT));
										
										// orderedRow.addView(rowLayout);

										orderedRow.setId(selectedItemID);
										TextView orderedItemName = new TextView(TableView.this);
										orderedItemName.setText(nameForClicked);
										orderedItemName.setTextColor(0xFFCCCCCC);
										orderedItemName.setPadding(5, 0, 0, 0);
										TextView orderedItemQty = new TextView(TableView.this);
										orderedItemQty.setTextColor(0xFFCCCCCC);
										orderedItemQty.setText("1");
										ImageView deleteOrder = new ImageView(TableView.this);
										deleteOrder.setOnClickListener(new DeleteOrder());

										deleteOrder.setBackgroundResource(R.drawable.delete2);
										orderedRow.addView(orderedItemName);
										orderedRow.addView(orderedItemQty);
										orderedRow.addView(deleteOrder);
										orderedRow.setWeightSum(5);
										/*
										 * TableRow.LayoutParams
										 * rowLayParam=(TableRow
										 * .LayoutParams)rowLayout
										 * .getLayoutParams();
										 * rowLayParam.weight=5;
										 * rowLayout.setLayoutParams
										 * (rowLayParam);
										 */

										LinearLayout.LayoutParams insideViewLayout = (LinearLayout.LayoutParams) orderedItemName
												.getLayoutParams();
										insideViewLayout.weight = 4f;
										insideViewLayout.width =0 ;
										orderedItemName
												.setLayoutParams(insideViewLayout);

										insideViewLayout = (LinearLayout.LayoutParams) orderedItemQty
												.getLayoutParams();
										insideViewLayout.weight = 0.5f;
										insideViewLayout.width = 0;
										orderedItemQty
												.setLayoutParams(insideViewLayout);

										insideViewLayout = (LinearLayout.LayoutParams) deleteOrder
												.getLayoutParams();
										insideViewLayout.weight = 0.5f;
										insideViewLayout.width = 0;
										deleteOrder
												.setLayoutParams(insideViewLayout);

										orderItemLayout.addView(orderedRow);
										try {
											File fdir = TableView.this
													.getDir("data",
															Context.MODE_PRIVATE);
											File f = new File(fdir,
													"menuitem.xml");
											DocumentBuilderFactory dbFactory = DocumentBuilderFactory
													.newInstance();
											DocumentBuilder dBuilder = dbFactory
													.newDocumentBuilder();
											Document doc = dBuilder.parse(f);

											doc.getDocumentElement()
													.normalize();

											XPathFactory xPathfactory = XPathFactory
													.newInstance();
											XPath xpath = xPathfactory
													.newXPath();
											XPathExpression expr1 = xpath
													.compile("//Menu/rootcat/subcat/item[@id="
															+ selectedItemID
															+ "]/price");
											Double tempINt = (Double) expr1
													.evaluate(
															doc,
															XPathConstants.NUMBER);
											total = total + tempINt.intValue();
											totalPrice.setText("Total:  "
													+ total);
										} catch (Exception e) {
											e.printStackTrace();
										}
									} else {
										TableRow existedRow = (TableRow) orderItemLayout
												.findViewById(selectedItemID);
										TextView existedRowQty = (TextView) (existedRow
												.getChildAt(1));
										int qty = Integer
												.parseInt(existedRowQty
														.getText().toString());
										qty = qty + 1;
										existedRowQty.setText(qty + "");
										try {
											File fdir = TableView.this
													.getDir("data",
															Context.MODE_PRIVATE);
											File f = new File(fdir,
													"menuitem.xml");
											DocumentBuilderFactory dbFactory = DocumentBuilderFactory
													.newInstance();
											DocumentBuilder dBuilder = dbFactory
													.newDocumentBuilder();
											Document doc = dBuilder.parse(f);

											doc.getDocumentElement()
													.normalize();

											XPathFactory xPathfactory = XPathFactory
													.newInstance();
											XPath xpath = xPathfactory
													.newXPath();
											XPathExpression expr1 = xpath
													.compile("//Menu/rootcat/subcat/item[@id="
															+ selectedItemID
															+ "]/price");
											Double tempINt = (Double) expr1
													.evaluate(
															doc,
															XPathConstants.NUMBER);
											total = total + tempINt.intValue();
											totalPrice.setText("Total:  "
													+ total);
										} catch (Exception e) {
											e.printStackTrace();
										}
									}
								}

							});
							

						}
					}
				}
				itemLayout.setVisibility(View.VISIBLE);
				Animation slideUp = AnimationUtils
						.makeInChildBottomAnimation(TableView.this);
				slideUp.setDuration(400);
				slideUp.setAnimationListener(new AnimationListener() {

					@Override
					public void onAnimationStart(Animation animation) {
						// TODO Auto-generated method stub

					}

					@Override
					public void onAnimationRepeat(Animation animation) {
						// TODO Auto-generated method stub

					}

					@Override
					public void onAnimationEnd(Animation animation) {
						// TODO Auto-generated method stub

					}
				});
				itemLayout.startAnimation(slideUp);

			} catch (Exception e) {
				e.printStackTrace();
			}

		}

	}

	class ImageAdapter extends BaseAdapter {
		String[] imagePaths;
		Context C;

		public ImageAdapter(Context c, String[] imagPaths) {
			// TODO Auto-generated constructor stub
			
			imagePaths = imagPaths;
		}

		@Override
		public int getCount() {
			// TODO Auto-generated method stub
			return imagePaths.length;
		}

		@Override
		public Object getItem(int position) {
			// TODO Auto-generated method stub
			return null;
		}

		@Override
		public long getItemId(int position) {
			// TODO Auto-generated method stub
			return 0;
		}

		@Override
		public View getView(int position, View convertView, ViewGroup parent) {
			// TODO Auto-generated method stub
			File imagedir = TableView.this.getDir("data",
					Context.MODE_PRIVATE);
			File image = new File(imagedir, imagePaths[position]);

			ImageView itemImage = new ImageView(TableView.this);
			if (image.exists()) {
				if (image.isFile()) {

					Bitmap bmp = new BitmapDrawable(image.getAbsolutePath())
							.getBitmap();
					if (bmp == null) {
						itemImage.setBackgroundResource(R.drawable.food2);

					} else {
						Bitmap resizedbitmap = Bitmap.createScaledBitmap(bmp,
								250, 100, true);
						itemImage.setImageBitmap(resizedbitmap);
					}
				} else
					itemImage.setBackgroundResource(R.drawable.food2);
			} else {
				itemImage.setBackgroundResource(R.drawable.food2);
			}
			itemImage.setPadding(20, 0, 20, 0);

			return itemImage;
		}

	}

	class ItemClk implements View.OnClickListener {

		@Override
		public void onClick(View v) {
			String itemDetID = "", itemDetName = "", itemDetDesc = "", itemDetImg = "", itemDetPrice = "", itemDetPrep = "", itemDetIngdr = "";

			int clickedID = v.getId();
			try {

				File fdir = TableView.this.getDir("data",
						Context.MODE_PRIVATE);
				File f = new File(fdir, "menuitem.xml");
				DocumentBuilderFactory dbFactory = DocumentBuilderFactory
						.newInstance();
				DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
				Document doc = dBuilder.parse(f);

				doc.getDocumentElement().normalize();

				XPathFactory xPathfactory = XPathFactory.newInstance();
				XPath xpath = xPathfactory.newXPath();
				XPathExpression expr = xpath
						.compile("//Menu/rootcat/subcat/item[@id=" + clickedID
								+ "]");

				

				NodeList nl = (NodeList) expr.evaluate(doc,
						XPathConstants.NODESET);
				for (int i = 0; i < nl.getLength(); i++) {
					Node currentItem = nl.item(i);
					
					if (currentItem.getNodeType() == Node.ELEMENT_NODE) {

						Element eElement = (Element) currentItem;
						itemDetID = eElement.getAttribute("id");
						itemDetName = eElement.getAttribute("name");
						itemDetImg = eElement.getElementsByTagName("images").item(0).getTextContent();
						itemDetDesc = eElement.getElementsByTagName("description").item(0)
								.getTextContent();
						itemDetIngdr = eElement
								.getElementsByTagName("ingredients").item(0)
								.getTextContent();
						itemDetPrep = eElement
								.getElementsByTagName("preperation").item(0)
								.getTextContent();
					}
				}
			} catch (Exception e) {
				e.printStackTrace();
			}

			WindowManager.LayoutParams wlp = new WindowManager.LayoutParams();

			Dialog infoItemDialog = new Dialog(TableView.this);
			Typeface papyType=Typeface.createFromAsset(getAssets(),"papyrus.ttf");
			infoItemDialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
			infoItemDialog.setContentView(Theme.dialogbg);
			
			TextView nameText = (TextView) infoItemDialog
					.findViewById(R.id.titleV);
			nameText.setTypeface(papyType);
			nameText.setText(itemDetName);
			
			
			Typeface tf = Typeface.createFromAsset(TableView.this.getAssets(),
					"Ubuntu-L.ttf");
			TextView descNameText = (TextView) infoItemDialog
					.findViewById(R.id.descText);
			descNameText.setTypeface(tf);
			TextView prepartionText = (TextView) infoItemDialog
					.findViewById(R.id.prepText);
			prepartionText.setTypeface(tf);
			TextView ingrdntText = (TextView) infoItemDialog
					.findViewById(R.id.ingrdntext);
			ingrdntText.setTypeface(tf);
			
			Typeface tf1 = Typeface.createFromAsset(TableView.this.getAssets(),
					"Cuprum.ttf");
			
			TextView descText = (TextView) infoItemDialog
					.findViewById(R.id.itemDescValue);
			descText.setText(itemDetDesc);
			descText.setTypeface(tf1);
			descText.setTextSize(16);
			// GridView
			// itemImgGrid=(GridView)infoItemDialog.findViewById(R.id.imageGrid);
			Gallery itemImagegallery = (Gallery) infoItemDialog
					.findViewById(R.id.itemimageGallery);
			int countI = 0;
			StringTokenizer imageTokens = new StringTokenizer(itemDetImg, ",");
			String imgPths[] = new String[imageTokens.countTokens()];
			while (imageTokens.hasMoreTokens()) {
				String img = imageTokens.nextToken();
				imgPths[countI] = img.substring(img.lastIndexOf("/") + 1);
				countI++;
			}
			itemImagegallery.setAdapter(new ImageAdapter(TableView.this,
					imgPths));
			TextView ingrdntTextV = (TextView) infoItemDialog
					.findViewById(R.id.ingrdntValue);
			ingrdntTextV.setText(itemDetIngdr);
			ingrdntTextV.setTypeface(tf1);
			ingrdntTextV.setTextSize(16);
			TextView prepText = (TextView) infoItemDialog
					.findViewById(R.id.prepValue);
			prepText.setText(itemDetPrep);
			prepText.setTypeface(tf1);
			prepText.setTextSize(16);
			wlp.copyFrom(infoItemDialog.getWindow().getAttributes());
			wlp.width = LayoutParams.MATCH_PARENT;

			wlp.height = LayoutParams.MATCH_PARENT;
			wlp.gravity = Gravity.CENTER_HORIZONTAL;
			infoItemDialog.show();
			infoItemDialog.getWindow().setAttributes(wlp);

		}

	}

	@Override
	public void onTabChanged(String tabId) {
		// TODO Auto-generated method stub
		int firstcount=0;
		int firstid=-1;
		try {

			File fdir = TableView.this.getDir("data", Context.MODE_PRIVATE);
			File f = new File(fdir, "menuitem.xml");
			DocumentBuilderFactory dbFactory = DocumentBuilderFactory
					.newInstance();
			DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
			Document doc = dBuilder.parse(f);
			currentSelectedSubCat = 0;
			subCatLayout.removeAllViews();
			itemLayout.removeAllViews();

			// optional, but recommended
			// read this -
			// http://stackoverflow.com/questions/13786607/normalization-in-dom-parsing-with-java-how-does-it-work
			doc.getDocumentElement().normalize();



			NodeList nList = doc.getElementsByTagName("subcat");


			for (int temp = 0; temp < nList.getLength(); temp++) {

				Node nNode = nList.item(temp);

				if (nNode.getNodeType() == Node.ELEMENT_NODE) {

					Element eElement = (Element) nNode;

					int subCatID = Integer.parseInt(eElement.getAttribute("id"));
					String subParentID = eElement.getAttribute("parent_id");
					String subCatName = eElement.getAttribute("name");
					String fullImagePath = eElement.getElementsByTagName("images").item(0).getTextContent();
					String subCatImgPath = fullImagePath.substring(fullImagePath.lastIndexOf("/") + 1);


					if (tabId.equals(subParentID)) {
						System.out.println("++++++++++++++++++++++++++++++++++++++++"+subCatID);
						TableRow subCatRow = new TableRow(this);
						subCatRow.setId(subCatID);
						if(firstcount==0){
							System.out.println("------------------------------------------"+subCatID);
							firstid=subCatID;
						}
						File imagedir = TableView.this.getDir("data",
								Context.MODE_PRIVATE);
						File image = new File(fdir, subCatImgPath);

						ImageView subCatImage = new ImageView(this);
						if (image.exists()) {
							if (image.isFile()) {

								Bitmap bmp = new BitmapDrawable(
										image.getAbsolutePath()).getBitmap();
								if (bmp == null) {
									subCatImage
											.setBackgroundResource(R.drawable.food2);
								} else {
									Bitmap resizedbitmap = Bitmap
											.createScaledBitmap(bmp, 60, 60,
													true);
									subCatImage.setImageBitmap(resizedbitmap);
								}
							} else {
								Bitmap btmp = BitmapFactory.decodeResource(
										getResources(), R.drawable.food2);
								Bitmap resizedbitmap = Bitmap
										.createScaledBitmap(btmp, 60, 60, true);
								subCatImage.setImageBitmap(resizedbitmap);

							}
						} else {
							Bitmap btmp = BitmapFactory.decodeResource(
									getResources(), R.drawable.food2);
							Bitmap resizedbitmap = Bitmap.createScaledBitmap(
									btmp, 60, 60, true);
							subCatImage.setImageBitmap(resizedbitmap);
						}
						subCatImage.setHorizontalFadingEdgeEnabled(true);
						subCatImage.setVerticalFadingEdgeEnabled(true);
						//subCatImage.setBackgroundDrawable(getResources().getDrawable(R.drawable.image_border));
						subCatImage.setFadingEdgeLength(5);
						subCatImage.setPadding(10, 10, 10, 0);
						subCatRow.addView(subCatImage);
						Typeface tf=Typeface.createFromAsset(getAssets(),"Cuprum.ttf");
						TextView subCatText = new TextView(this);
						subCatText.setText(subCatName);
						subCatText.setTextColor(Theme.subcattext);
						subCatText.setTextSize(20);
						subCatText.setTypeface(tf);
						subCatText.setPadding(10, 0, 0, 0);
						subCatText.setGravity(Gravity.CENTER);
						// subCatText.setShadowLayer(0.3f,
						// 0.5f,0.5f,0xff000000);
						subCatRow.addView(subCatText);
						TableRow.LayoutParams trlp=(TableRow.LayoutParams)subCatText.getLayoutParams();
						trlp.height = 80;
						trlp.gravity=Gravity.CENTER_VERTICAL;
						subCatText.setLayoutParams(trlp);
						subCatRow.setBackgroundColor(0x00000000);
						subCatRow.setPadding(0, 0, 0, 50);
						subCatRow.setOnClickListener(new SubCatListener());
						subCatLayout.addView(subCatRow);
						TableLayout.LayoutParams tllp=(TableLayout.LayoutParams)subCatRow.getLayoutParams();
						tllp.setMargins(0,0,0,50);
						tllp.height = 80;
						subCatRow.setLayoutParams(tllp);
						firstcount++;
					}
				}
			}

		} catch (Exception e) {
			e.printStackTrace();
		}
		if(firstid!=-1)
			findViewById(firstid).performClick();
		firstid=-1;
	}

}
