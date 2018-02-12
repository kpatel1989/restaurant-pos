package com.samaysoftwares.restaurantpos;

import java.io.BufferedReader;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URL;
import java.net.URLEncoder;
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

import com.samaysoftwares.restaurantpos.TableView.DeleteOrder;

import android.media.MediaPlayer;
import android.media.MediaPlayer.OnCompletionListener;
import android.os.AsyncTask;
import android.os.Bundle;
import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;

import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Typeface;
import android.graphics.Bitmap.CompressFormat;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.ColorDrawable;
import android.graphics.drawable.GradientDrawable.Orientation;
import android.support.v4.widget.SimpleCursorAdapter.ViewBinder;
import android.util.Log;
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
import android.view.animation.AlphaAnimation;
import android.view.animation.AnimationUtils;
import android.view.animation.Animation.AnimationListener;
import android.view.animation.BounceInterpolator;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.FrameLayout.LayoutParams;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.ImageView.ScaleType;
import android.widget.BaseAdapter;
import android.widget.EditText;
import android.widget.Gallery;
import android.widget.GridView;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TabHost;
import android.widget.TabWidget;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.Toast;
import android.widget.TabHost.OnTabChangeListener;
import android.widget.TabHost.TabSpec;
import android.widget.TextView;

public class WaiterView extends Activity implements OnTabChangeListener {
	TabHost tabhost;
	ProgressDialog pDialog;
	LinearLayout tab,resTables;
	FrameLayout itemDetail;
	TableLayout subCatLayout,itemLayout,orderItemLayout,orderedLayout;
	ViewGroup.LayoutParams tabLayout;
	int currentSelectedSubCat=0;
	FrameLayout.LayoutParams itemLayParam;
	TextView totalPrice,orderedItemTV,askbill;
	int total=0;
	String price,tablename="";
	Button orderButton;
	
	ImageButton internetButton;
	SharedPreferences prefs;
	Info info=new Info();
	static int selectedTableId=-1;
	LinearLayout main_back;
	ScrollView paneitems;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		this.requestWindowFeature(Window.FEATURE_NO_TITLE);
		prefs=getSharedPreferences("com.samay.tabdesign", Context.MODE_PRIVATE);
		prefs.edit().putString("orderid","-1").commit();
		setContentView(R.layout.waiter_activity);
		pDialog=new ProgressDialog(WaiterView.this);
		main_back=(LinearLayout)findViewById(R.id.main_back);
		main_back.setBackgroundDrawable(getResources().getDrawable(Theme.layoutbackground));
		subCatLayout=(TableLayout)findViewById(R.id.tablesubcategory);
		orderItemLayout=(TableLayout)findViewById(R.id.tableright);
		itemLayout=(TableLayout)findViewById(R.id.tl5);
		itemDetail=(FrameLayout)findViewById(R.id.item_detail_container1);
		orderedLayout=(TableLayout)findViewById(R.id.OrderedItems);
		resTables=(LinearLayout)findViewById(R.id.resTableLayout);
		tabhost  = (TabHost)findViewById(R.id.tabhost);
		Typeface tf2=Typeface.createFromAsset(getAssets(),"Ubuntu-L.ttf");
		
		totalPrice=(TextView)findViewById(R.id.total);
		totalPrice.setCompoundDrawablesWithIntrinsicBounds(0,0,R.drawable.ruppe,0);
		totalPrice.setTextColor(Theme.totaltext);
		totalPrice.setTypeface(tf2,Typeface.BOLD);
		
		orderedItemTV = (TextView) findViewById(R.id.textView2);
		orderedItemTV.setTextColor(Theme.ordertext);
		orderedItemTV.setTypeface(tf2,Typeface.BOLD);
		paneitems=(ScrollView)findViewById(R.id.paneitems);
		paneitems.setBackgroundColor(Theme.itemsbackground);
		
		tabhost.setup();
		tabhost.setOnTabChangedListener(this);
		orderButton=(Button)findViewById(R.id.orderButton);
		
		orderButton.setOnClickListener(new OrderClick());
		
		askbill = (TextView)findViewById(R.id.askbill);
		askbill.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				String orderidSP = prefs.getString("orderid","-2").trim();
				if (Integer.parseInt(orderidSP)>0)
					new CheckOut().execute();
				else
					Toast.makeText(WaiterView.this,"You cannot checkout without placing an order...!", Toast.LENGTH_LONG).show();
					
				
			}
		});
		
		
		addCategories();
		addTables();
		addSubCategories();
		
		
	}
	
	class CheckOut extends AsyncTask<String, String, String>{

		@Override
		protected String doInBackground(String... params) {
			// TODO Auto-generated method stub
			
			HttpClient httpclient = new DefaultHttpClient();
			HttpResponse response;
			try {
				String orderidSP = prefs.getString("orderid", "-2").trim();
				response = httpclient.execute(new HttpGet("http://"+ info.getIP()+ "/testTreeView1/android_checkout.php?checkout=1&orderid="+ orderidSP.trim()));
				StatusLine statusLine = response.getStatusLine();
				if (statusLine.getStatusCode() == HttpStatus.SC_OK) {
					runOnUiThread(new Runnable() {
						
						@Override
						public void run() {
							// TODO Auto-generated method stub
							runOnUiThread( new Runnable() {
								public void run() {
									Toast resultToast = Toast.makeText(WaiterView.this,"Collect the bill from the counter and give it to the customer. Thank you!", Toast.LENGTH_LONG);
									resultToast.show();
								}
							});
							
						}
					});
					
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
	
	class OrderClick implements View.OnClickListener{

		@Override
		public void onClick(View v) {
			// TODO Auto-generated method stub
			new OrderItems().execute();
			
			
		}
		
	}
	
	
	
	
	class OrderItems extends AsyncTask<String, String, String>{

		@Override
		protected void onPostExecute(String result) {
			// TODO Auto-generated method stub
			final String result1=result;
			
			WaiterView.this.runOnUiThread(new Runnable() {
				
				@Override
				public void run() {
					// TODO Auto-generated method stub
					
					
					if(result1.equals("Error")){
						
					}
					else {
						
						String orderidSP=prefs.getString("orderid","-2");
						if(orderidSP.equals("-1") || orderidSP.equals("-2")){
							
							
							prefs.edit().putString("orderid",result1).commit();
							
							
						}
						else{	
						
						}
						for(int count=0;count<orderItemLayout.getChildCount();count++){
							TableRow tempRow=(TableRow)orderItemLayout.getChildAt(count);
							tempRow.removeViewAt(2);
							TableRow newRow=new TableRow(WaiterView.this);
							
							ImageView done=new ImageView(WaiterView.this);
							done.setBackgroundResource(R.drawable.ok);
							
							
							TextView itemName=new TextView(WaiterView.this);
							itemName.setText(((TextView)tempRow.getChildAt(0)).getText());
							itemName.setPadding(5, 0,0,0);
							itemName.setTextColor(Theme.ordereditemtext);
							
							TextView itemQty=new TextView(WaiterView.this);
							itemQty.setText(((TextView)tempRow.getChildAt(1)).getText());
							itemQty.setTextColor(Theme.ordereditemtext);
							
							newRow.addView(itemName);
							newRow.addView(itemQty);
							newRow.addView(done);
							
							newRow.setWeightSum(5);
							orderedLayout.addView(newRow);
							
			                TableRow.LayoutParams insideViewLayout=(TableRow.LayoutParams)itemName.getLayoutParams();
			                insideViewLayout.weight=4;
			                itemName.setLayoutParams(insideViewLayout);
			                
			                insideViewLayout=(TableRow.LayoutParams)itemQty.getLayoutParams();
			                insideViewLayout.weight=0.5f;
			                itemQty.setLayoutParams(insideViewLayout);
			                
			                insideViewLayout=(TableRow.LayoutParams)done.getLayoutParams();
			                insideViewLayout.weight=0.5f;
			                //insideViewLayout.width=LayoutParams.WRAP_CONTENT;
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
			String output="";
			String orderQty="order=";
			int orderCount=orderItemLayout.getChildCount();
			
			if(orderCount<=0){
				WaiterView.this.runOnUiThread(new Runnable() {
					
					@Override
					public void run() {
						// TODO Auto-generated method stub
						Toast resultToast=Toast.makeText(WaiterView.this,"No new order found !!", Toast.LENGTH_LONG);
						resultToast.show();
						
					}
				});
			}
			
			else{
			try {
				
				for(int k=0;k<orderCount;k++){
					TableRow temp=(TableRow)orderItemLayout.getChildAt(k);
					int id=temp.getId();
					//LinearLayout tempL=(LinearLayout)temp.getChildAt(0);
					TextView qtyView=(TextView)temp.getChildAt(1);
					int qty=Integer.parseInt(qtyView.getText().toString());
					orderQty=orderQty+id+"-"+qty+"*";
				}
				
				HttpClient httpclient = new DefaultHttpClient();
				    HttpResponse response;
				    
				    final String orderQty1=orderQty;
				    WaiterView.this.runOnUiThread(new Runnable() {
						
						@Override
						public void run() {
							// TODO Auto-generated method stub
							
						}
					});	
				   
					String orderidSP="orderid="+prefs.getString("orderid","-2").trim();
					System.out.println("\n\n\n http://"+info.getIP()+"/testTreeView1/android_order.php?tableid="+(selectedTableId/100)+"&devicename="+info.getDevice()+"&"+orderQty+"&"+orderidSP.trim());
					response = httpclient.execute(new HttpGet("http://"+info.getIP()+"/testTreeView1/android_order.php?tableid="+(selectedTableId/100)+"&devicename="+info.getDevice()+"&"+orderQty+"&"+orderidSP.trim()));
					//String urlto="http://"+info.getIP()+"/testTreeView1/android_order.php?tableid="+tablename+"&devicename="+info.getDevice()+"&"+orderQty+"&"+orderidSP.trim();
				   StatusLine statusLine = response.getStatusLine();
				    if(statusLine.getStatusCode() == HttpStatus.SC_OK){
				        ByteArrayOutputStream out = new ByteArrayOutputStream();
				        response.getEntity().writeTo(out);
				        out.close();
				        responseString = out.toString();
				        output=output+responseString+"\n";
				        return responseString;
				        
				    } else{
				        //Closes the connection.
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
	
	void addTables(){
		try {
			 
			File fdir=WaiterView.this.getDir("data", Context.MODE_PRIVATE);
			File f=new File(fdir,"menuitem.xml");
			DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
			Document doc = dBuilder.parse(f);
		 
			doc.getDocumentElement().normalize();
		 
			NodeList nList = doc.getElementsByTagName("table");
		 
			for (int temp = 0; temp < nList.getLength(); temp++) {
		 
				Node nNode = nList.item(temp);
		 
		 
				if (nNode.getNodeType() == Node.ELEMENT_NODE) {
					
					Element eElement = (Element) nNode;
					
					String tableID=eElement.getAttribute("id");
					int finaltableID=Integer.parseInt(tableID);
					
					finaltableID=finaltableID*100;
					
					String tableName=eElement.getAttribute("name");
					Button tableButton=new Button(WaiterView.this);
					tableButton.setId(finaltableID);
					tableButton.setText(tableName);
					if(temp==0){
						tableButton.setBackgroundColor(Theme.seltablebackground);
						tableButton.setTextColor(0xFFDDDDDD);
						tableButton.setCompoundDrawablesWithIntrinsicBounds(R.drawable.selecttable1,0,0,0);
					}
					else{
						tableButton.setBackgroundColor(Theme.tablebackground);
						tableButton.setTextColor(0xFFBBBBBB);
						
					}
						
					resTables.addView(tableButton);
					LinearLayout.LayoutParams prms = (LinearLayout.LayoutParams) tableButton.getLayoutParams();
					prms.width =LayoutParams.MATCH_PARENT;
					prms.height = LayoutParams.WRAP_CONTENT;
					tableButton.setLayoutParams(prms);
					tableButton.setGravity(Gravity.CENTER);
					tableButton.setOnClickListener(new TableButtonClick());
					if (temp==0)
					{
						tableButton.performClick();
						//new TableButtonClick().onClick(tableButton);
					}
				}
			}
			
		    } catch (Exception e) {
		    	e.printStackTrace();
		    }
	}
	
	
	class TableButtonClick implements View.OnClickListener{

		@Override
		public void onClick(View v) {
			// TODO Auto-generated method stub
			
			
			orderItemLayout.removeAllViews();
			Button tempbutton=(Button)v;
			Button prevSelected=(Button)findViewById(selectedTableId);
			
			if(selectedTableId!=-1){
				prevSelected.setBackgroundColor(Theme.tablebackground);
				prevSelected.setTextColor(0xFFBBBBBB);
				prevSelected.setCompoundDrawablesWithIntrinsicBounds(0,0,0,0);	
			}
			selectedTableId=v.getId();
			
			tempbutton.setBackgroundColor(Theme.seltablebackground);
			tempbutton.setTextColor(0xFFDDDDDD);
			tempbutton.setCompoundDrawablesWithIntrinsicBounds(Theme.tableselecticon,0,0,0);
			
			prefs.edit().putString("orderid","-1").commit();
			
			new GetTableOrders().execute();
			
			
		}
		
	}
	
	class GetTableOrders extends AsyncTask<String, String, String>{
		StringBuilder sb;

		@Override
		protected void onPreExecute() {
			
		}

		protected String doInBackground(String... args) {
			
			HttpClient httpclient = new DefaultHttpClient();
		    
		    List<NameValuePair> params = new ArrayList<NameValuePair>();
		    NameValuePair pair = new BasicNameValuePair("tableid",Integer.valueOf(selectedTableId/100).toString());
		    params.add(pair);
			String paramString = URLEncodedUtils.format(params, "utf-8");
			String url = "http://"+info.getIP()+"/testtreeview1/android_gettableorders.php";
			url += "?" + paramString;
			HttpGet httpget = new HttpGet(url);
			
			try {
				HttpResponse httpresponse = httpclient.execute(httpget);
				HttpEntity httpEntity = httpresponse.getEntity();
				InputStream is = httpEntity.getContent();
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
						WaiterView.this.getTableOrder(sb);
					}
				});
				is.close();
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
				TableRow orderedRow = new TableRow(WaiterView.this);
				
				prefs.edit().putString("orderid",st1.nextToken()).commit();
				orderedRow.setLayoutParams(new TableLayout.LayoutParams(LayoutParams.MATCH_PARENT,LayoutParams.MATCH_PARENT));
				orderedRow.setWeightSum(5);
				
				TextView orderedItemName = new TextView(WaiterView.this);
				orderedItemName.setTextColor(Theme.ordereditemtext);
				orderedItemName.setText(st1.nextToken());
				orderedItemName.setPadding(5, 0, 0, 0);
				
				TextView orderedItemQty = new TextView(WaiterView.this);
				orderedItemQty.setText(st1.nextToken());
				orderedItemQty.setTextColor(Theme.ordereditemtext);
				
				total += (Integer.parseInt(orderedItemQty.getText().toString())*Float.parseFloat(st1.nextToken())); 
				
				ImageView deleteOrder = new ImageView(WaiterView.this);
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
			
			totalPrice.setText("Total:  " + total);
			
		
	}
	void addCategories(){
		try {
			 
			File fdir=WaiterView.this.getDir("data", Context.MODE_PRIVATE);
			File f=new File(fdir,"menuitem.xml");
			DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
			Document doc = dBuilder.parse(f);
		 
			doc.getDocumentElement().normalize();
		 
		 
			NodeList nList = doc.getElementsByTagName("rootcat");
		 
		 
			for (int temp = 0; temp < nList.getLength(); temp++) {
		 
				Node nNode = nList.item(temp);
		 
		 
				if (nNode.getNodeType() == Node.ELEMENT_NODE) {
		 
					Element eElement = (Element) nNode;
		 
					String rootCatID=eElement.getAttribute("id");
					String rootCatName=eElement.getAttribute("name");
					
					TabSpec ts = WaiterView.this.tabhost.newTabSpec(rootCatID);
					
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
	
	
	void addSubCategories(){
		
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_main, menu);
		return true;
	}
	
	View getViewForName(String name){
		View view =  LayoutInflater.from(this).inflate(Theme.tabbackground, null);
		
		TextView tv = (TextView) view.findViewById(R.id.tabsText);
		tv.setText(name);
		tv.setTextSize(TypedValue.COMPLEX_UNIT_SP, 16);
		Typeface tf=Typeface.createFromAsset(getAssets(),"Cuprum.ttf");
		tv.setTypeface(tf);
		tv.setGravity(Gravity.CENTER);
		
		return view;
	}

	
	class DeleteOrder implements View.OnClickListener{

		@Override
		public void onClick(View v) {
			// TODO Auto-generated method stub
			try{
				TableRow existedRow=(TableRow) orderItemLayout.findViewById(((TableRow)(v.getParent())).getId());
			
	       	 TextView existedRowQty=(TextView) (existedRow.getChildAt(1));
	       	 int qty=Integer.parseInt(existedRowQty.getText().toString());
	       	 qty=qty-1;
	       	 if(qty<=0){
	       		qty=0;
	       		orderItemLayout.removeView(existedRow);
	       	 }
	       	 existedRowQty.setText(qty+"");
		       	File fdir=WaiterView.this.getDir("data", Context.MODE_PRIVATE);
				File f=new File(fdir,"menuitem.xml");
				DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
				DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
				Document doc = dBuilder.parse(f);
			 
				doc.getDocumentElement().normalize();
	
				XPathFactory xPathfactory = XPathFactory.newInstance();
				XPath xpath = xPathfactory.newXPath();
				XPathExpression expr1=xpath.compile("//Menu/rootcat/subcat/item[@id="+existedRow.getId()+"]/price");
				Double tempINt=(Double)expr1.evaluate(doc, XPathConstants.NUMBER);
				total=total-tempINt.intValue();
				if(total<=0){
					total=0;
				}
				totalPrice.setText("Total:  "+total);
			}catch(Exception e){
				e.printStackTrace();
			}
		}
		
	}

	class SubCatListener implements View.OnClickListener{

		@Override
		public void onClick(View v) {
			// TODO Auto-generated method stub
			final String subCatSelId=v.getId()+"";
			//int count=0;
			
			if(currentSelectedSubCat!=0)
				findViewById(currentSelectedSubCat).setBackgroundColor(0x00000000);
			currentSelectedSubCat=v.getId();
			findViewById(currentSelectedSubCat).setBackgroundColor(Theme.subcatbackground);
			
			try {
				 
				File fdir=WaiterView.this.getDir("data", Context.MODE_PRIVATE);
				File f=new File(fdir,"menuitem.xml");
				DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
				DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
				Document doc = dBuilder.parse(f);
				
				
						itemLayout.setVisibility(View.INVISIBLE);
						itemLayout.removeAllViews();
						
						doc.getDocumentElement().normalize();
					 
						NodeList nList = doc.getElementsByTagName("item");
						for(int temp=0;temp<nList.getLength();temp++){
							 
							Node nNode = nList.item(temp);
					 
					 
							if (nNode.getNodeType() == Node.ELEMENT_NODE) {
					 
								Element eElement = (Element) nNode;
					 
								int itemID=Integer.parseInt(eElement.getAttribute("id"));
								String itemParentID=eElement.getAttribute("categoryid");
								String itemName=eElement.getAttribute("name");
								String fullImagesPath=eElement.getElementsByTagName("images").item(0).getTextContent();
								String itemDesc=eElement.getElementsByTagName("description").item(0).getTextContent();
								price=eElement.getElementsByTagName("price").item(0).getTextContent();
								
								if(subCatSelId.equals(itemParentID)){
									
									TableRow subCatRow=new TableRow(WaiterView.this);
									subCatRow.setId(itemID);
									subCatRow.setOnClickListener(new ItemClk());
									itemLayout.addView(subCatRow);
									TableLayout.LayoutParams  rowparams = (TableLayout.LayoutParams)subCatRow.getLayoutParams();
									subCatRow.setWeightSum(10);
									ImageView itemImage=new ImageView(WaiterView.this);
									if(fullImagesPath.equals("")){
										//itemImage.setBackgroundResource(R.drawable.food2);
										Bitmap btmp=BitmapFactory.decodeResource(getResources(),R.drawable.food2);
										Bitmap resizedbitmap=Bitmap.createScaledBitmap(btmp,60,60,true);
										itemImage.setImageBitmap(resizedbitmap);
									}
									else{
									StringTokenizer imageTokens=new StringTokenizer(fullImagesPath, ",");
									
									String firstImage=imageTokens.nextToken();
									String itemImgPath=firstImage.substring(firstImage.lastIndexOf("/")+1);
									
									File imagedir=WaiterView.this.getDir("data", Context.MODE_PRIVATE);
									File image=new File(imagedir,itemImgPath);
									
									
									if(image.exists()){
										if(image.isFile()){
											
											Bitmap bmp=new BitmapDrawable(image.getAbsolutePath()).getBitmap();
											if(bmp==null){
												itemImage.setBackgroundResource(R.drawable.food2);
											}else{
										    Bitmap resizedbitmap=Bitmap.createScaledBitmap(bmp,60,60,true);
											itemImage.setImageBitmap(resizedbitmap);
											}
										}
										else
											itemImage.setBackgroundResource(R.drawable.food2);
									}
									else{
										itemImage.setBackgroundResource(R.drawable.food2);
									}}
									//itemImage.setBackgroundResource(R.layout.imageback);
									itemImage.setPadding(10,10,10,0);
									//Log.d("Subcat row", subCatRow.toString());
									subCatRow.addView(itemImage);
									TableRow.LayoutParams imageparams = (TableRow.LayoutParams)itemImage.getLayoutParams();
									imageparams.weight=2;
									imageparams.width = 0 ;
									itemImage.setLayoutParams(imageparams);
									
									LinearLayout itemFrame=new LinearLayout(WaiterView.this);
									
									subCatRow.addView(itemFrame);
									
									TableRow.LayoutParams frameparams = (TableRow.LayoutParams)itemFrame.getLayoutParams();
									frameparams.weight  = 6.5f;
									frameparams.width = 0;
									frameparams.height = TableRow.LayoutParams.FILL_PARENT;
									itemFrame.setLayoutParams(frameparams);
									
									//subCatRow.addView(itemLayout);
									//LinearLayout itemLayout = new LinearLayout(WaiterActivity.this);
									itemFrame.setOrientation(LinearLayout.VERTICAL);
									Typeface tf=Typeface.createFromAsset(getAssets(),"Cuprum.ttf");
									TextView itemDescText=new TextView(WaiterView.this);
									TextView itemNameText=new TextView(WaiterView.this);
									itemNameText.setText(itemName);
									itemNameText.setTextColor(Theme.itemnametext);
									itemNameText.setTextSize(20);
									itemNameText.setTypeface(tf);
									itemNameText.setPadding(10,0,0,0);
									itemFrame.addView(itemNameText);
									itemDescText.setText(itemDesc);
									itemDescText.setPadding(10,0,0,0);
									itemDescText.setTypeface(tf);
									itemDescText.setTextSize(15);
									itemDescText.setTextColor(Theme.itemdesctext);
									itemFrame.addView(itemDescText);
									
									String tempdesctext=itemDesc;
									if(tempdesctext.length()>40)
										tempdesctext=itemDesc.substring(0,40)+"...";
									itemDescText.setText(tempdesctext);
									LinearLayout.LayoutParams frameLLparams = (LinearLayout.LayoutParams) itemNameText.getLayoutParams();
									frameLLparams.width = LinearLayout.LayoutParams.WRAP_CONTENT;
									frameLLparams.setMargins(0,8,0,0);
									itemNameText.setLayoutParams(frameLLparams);
									
									
									LinearLayout.LayoutParams desclp = (LinearLayout.LayoutParams) itemDescText.getLayoutParams();
									desclp.width = 265;
									desclp.setMargins(0,8,0,0);
									itemDescText.setLayoutParams(desclp);
									
									
									LinearLayout btnFrame=new LinearLayout(WaiterView.this);
									
									subCatRow.addView(btnFrame);
									Typeface tf1=Typeface.createFromAsset(getAssets(),"Corbel.ttf");
									TextView priceText=new TextView(WaiterView.this);
									priceText.setTextColor(Theme.itempricetext);
									priceText.setCompoundDrawablesWithIntrinsicBounds(R.drawable.rupeeswhite, 0,0, 0);
									priceText.setTextSize(14);
									
									priceText.setTypeface(tf1,Typeface.BOLD);
									priceText.setText(price);
									priceText.setPadding(0,5,0,15);
									
									TableRow.LayoutParams btnparams = (TableRow.LayoutParams)btnFrame.getLayoutParams();
									btnparams.weight  = 1.5f;
									btnparams.height = TableRow.LayoutParams.FILL_PARENT;
									btnFrame.setLayoutParams(btnparams);
									
									btnFrame.setOrientation(LinearLayout.VERTICAL);
									//btnFrame.setBackgroundColor(0xFF4444FF);
									
									Button addImage=new Button(WaiterView.this);
									
									btnFrame.addView(priceText);
									btnFrame.addView(addImage);
									addImage.setLayoutParams(new TableRow.LayoutParams(25,25));
									subCatRow.setPadding(0,0,0,10);
									
									addImage.setBackgroundResource(R.drawable.add_button_selector);
									addImage.setOnClickListener(new OnClickListener() {
										
										@Override
										public void onClick(View v) {
											
											MediaPlayer mp = MediaPlayer.create(WaiterView.this, R.raw.click);
											mp.setVolume(0.1f,0.1f);
						                    mp.setOnCompletionListener(new OnCompletionListener() {

						                        @Override
						                        public void onCompletion(MediaPlayer mp) {
						                            // TODO Auto-generated method stub
						                            mp.release();
						                        }

						                    });   
						                    //mp.start();
						                   
						                   int existFlag=0;
						                   LinearLayout priceLay,nameLay;
							               priceLay=(LinearLayout)v.getParent();
							               String priceForClicked=((TextView)priceLay.getChildAt(0)).getText().toString();
							               TableRow itemIdRow=(TableRow)priceLay.getParent();
							               int selectedItemID=itemIdRow.getId();
							               nameLay=(LinearLayout)(itemIdRow).getChildAt(1);
							               String nameForClicked=((TextView)nameLay.getChildAt(0)).getText().toString();
							                   
							               for(int k=0;k<orderItemLayout.getChildCount();k++){
							            	   TableRow tempRow=(TableRow)orderItemLayout.getChildAt(k);
							            	   int tempID=tempRow.getId();
							            	   if(selectedItemID==tempID)
							            		   existFlag=1;
							               }
							                   
							               if(existFlag==0){
							                   TableRow orderedRow=new TableRow(WaiterView.this); 
							                   //LinearLayout rowLayout=new LinearLayout(WaiterActivity.this);
							                   //TableLayout.LayoutParams rowparam=
							                   orderedRow.setLayoutParams(new TableLayout.LayoutParams(LayoutParams.MATCH_PARENT,LayoutParams.MATCH_PARENT));
							                   orderedRow.setWeightSum(5);
							                   //orderedRow.addView(rowLayout);
							     
							                   orderedRow.setId(selectedItemID);
							                   TextView orderedItemName=new TextView(WaiterView.this);
							                   orderedItemName.setText(nameForClicked);
							                   orderedItemName.setPadding(5, 0,0,0);
							                   orderedItemName.setTextColor(Theme.ordereditemtext);
							                   TextView orderedItemQty=new TextView(WaiterView.this);
							                   orderedItemQty.setText("1");
							                   orderedItemQty.setTextColor(Theme.ordereditemtext);
							                   ImageView deleteOrder=new ImageView(WaiterView.this);
							                   deleteOrder.setOnClickListener(new DeleteOrder());
							                   
							                   deleteOrder.setBackgroundResource(R.drawable.delete2);
							                   orderedRow.addView(orderedItemName);
							                   orderedRow.addView(orderedItemQty);
							                   orderedRow.addView(deleteOrder);
							                   orderedRow.setWeightSum(5);
							                   /*TableRow.LayoutParams rowLayParam=(TableRow.LayoutParams)rowLayout.getLayoutParams();
							                   rowLayParam.weight=5;
							                   rowLayout.setLayoutParams(rowLayParam);*/
							                   
							                   LinearLayout.LayoutParams insideViewLayout=(LinearLayout.LayoutParams)orderedItemName.getLayoutParams();
							                   insideViewLayout.weight=4.45f;
							                   orderedItemName.setLayoutParams(insideViewLayout);
							                   
							                   insideViewLayout=(LinearLayout.LayoutParams)orderedItemQty.getLayoutParams();
							                   insideViewLayout.weight=0.5f;
							                   orderedItemQty.setLayoutParams(insideViewLayout);
							                   
							                   insideViewLayout=(LinearLayout.LayoutParams)deleteOrder.getLayoutParams();
							                   insideViewLayout.weight=0.05f;
							                   deleteOrder.setLayoutParams(insideViewLayout);
							                   
							                   orderItemLayout.addView(orderedRow);
							                try{   
							                   File fdir=WaiterView.this.getDir("data", Context.MODE_PRIVATE);
								       			File f=new File(fdir,"menuitem.xml");
								       			DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
								       			DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
								       			Document doc = dBuilder.parse(f);
								       		 
								       			doc.getDocumentElement().normalize();
	
								       			XPathFactory xPathfactory = XPathFactory.newInstance();
								       			XPath xpath = xPathfactory.newXPath();
								       			XPathExpression expr1=xpath.compile("//Menu/rootcat/subcat/item[@id="+selectedItemID+"]/price");
								       			Double tempINt=(Double)expr1.evaluate(doc, XPathConstants.NUMBER);
								       			total=total+tempINt.intValue();
								       			totalPrice.setText("Total:  "+total);
							                }catch(Exception e){
							                	e.printStackTrace();
							                }
							               }
							               else{
							            	  TableRow existedRow=(TableRow) orderItemLayout.findViewById(selectedItemID);
							            	 TextView existedRowQty=(TextView) (existedRow.getChildAt(1));
							            	 int qty=Integer.parseInt(existedRowQty.getText().toString());
							            	 qty=qty+1;
							            	 existedRowQty.setText(qty+"");
							            	 try{   
								                   File fdir=WaiterView.this.getDir("data", Context.MODE_PRIVATE);
									       			File f=new File(fdir,"menuitem.xml");
									       			DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
									       			DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
									       			Document doc = dBuilder.parse(f);
									       		 
									       			doc.getDocumentElement().normalize();
		
									       			XPathFactory xPathfactory = XPathFactory.newInstance();
									       			XPath xpath = xPathfactory.newXPath();
									       			XPathExpression expr1=xpath.compile("//Menu/rootcat/subcat/item[@id="+selectedItemID+"]/price");
									       			Double tempINt=(Double)expr1.evaluate(doc, XPathConstants.NUMBER);
									       			total=total+tempINt.intValue();
									       			totalPrice.setText("Total:  "+total);
								                }catch(Exception e){
								                	e.printStackTrace();
								                }
							               }
										}
										
									});
									
									
	
								}
							}
						}
						itemLayout.setVisibility(View.VISIBLE);
						Animation slideUp=AnimationUtils.makeInChildBottomAnimation(WaiterView.this);
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
	
	class ImageAdapter extends BaseAdapter{
		String[] imagePaths;
		Context C;
		
		public ImageAdapter(Context c,String[] imagPaths) {
			// TODO Auto-generated constructor stub
			c=c;
			imagePaths=imagPaths;
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
			File imagedir=WaiterView.this.getDir("data", Context.MODE_PRIVATE);
			File image=new File(imagedir,imagePaths[position]);
			
			ImageView itemImage=new ImageView(WaiterView.this);
			if(image.exists()){
				if(image.isFile()){
					
					Bitmap bmp=new BitmapDrawable(image.getAbsolutePath()).getBitmap();
					if(bmp==null){
						itemImage.setBackgroundResource(R.drawable.food2);
						
					}else{
				    Bitmap resizedbitmap=Bitmap.createScaledBitmap(bmp,250,100,true);
					itemImage.setImageBitmap(resizedbitmap);
					}
				}
				else
					itemImage.setBackgroundResource(R.drawable.food2);
			}
			else{
				itemImage.setBackgroundResource(R.drawable.food2);
			}
			itemImage.setPadding(20,0,20,0);
			
			return itemImage;
		}
		
	}
	
	class ItemClk implements View.OnClickListener{

		@Override
		public void onClick(View v) {
			String itemDetID="",itemDetName="",itemDetDesc="",itemDetImg="",itemDetPrice="",itemDetPrep="",itemDetIngdr="";
		
			int clickedID=v.getId();
			try {
				 
				File fdir=WaiterView.this.getDir("data", Context.MODE_PRIVATE);
				File f=new File(fdir,"menuitem.xml");
				DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
				DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
				Document doc = dBuilder.parse(f);
			 
				doc.getDocumentElement().normalize();

				XPathFactory xPathfactory = XPathFactory.newInstance();
				XPath xpath = xPathfactory.newXPath();
				XPathExpression expr = xpath.compile("//Menu/rootcat/subcat/item[@id="+clickedID+"]");
				
				
				
				NodeList nl = (NodeList) expr.evaluate(doc, XPathConstants.NODESET);
				for (int i = 0; i < nl.getLength(); i++)
				{
				    Node currentItem = nl.item(i);
				    if (currentItem.getNodeType() == Node.ELEMENT_NODE) {
				    	 
						Element eElement = (Element) currentItem;
			 
						itemDetID=eElement.getAttribute("id");
						itemDetName=eElement.getAttribute("name");
						itemDetImg=eElement.getElementsByTagName("images").item(0).getTextContent();
						itemDetDesc=eElement.getElementsByTagName("description").item(0).getTextContent();
						itemDetIngdr=eElement.getElementsByTagName("ingredients").item(0).getTextContent();
						itemDetPrep=eElement.getElementsByTagName("preperation").item(0).getTextContent();
					}
				}
			    } catch (Exception e) {
				e.printStackTrace();
			    }
			
			
			WindowManager.LayoutParams wlp=new WindowManager.LayoutParams();
			
			Dialog infoItemDialog=new Dialog(WaiterView.this);
			
			infoItemDialog.getWindow().setBackgroundDrawable(new ColorDrawable(0xBB000000));
			infoItemDialog.setContentView(R.layout.iteminfo);
			
			TextView nameText=(TextView)infoItemDialog.findViewById(R.id.titleV);
			nameText.setText(itemDetName);
			Typeface tf = Typeface.createFromAsset(getAssets(),"Corbel.ttf");
			nameText.setTypeface(tf);
			TextView descText=(TextView)infoItemDialog.findViewById(R.id.itemDescValue);
			descText.setText(itemDetDesc);
			//GridView itemImgGrid=(GridView)infoItemDialog.findViewById(R.id.imageGrid);
			Gallery itemImagegallery=(Gallery)infoItemDialog.findViewById(R.id.itemimageGallery);
			int countI=0;
			StringTokenizer imageTokens=new StringTokenizer(itemDetImg, ",");
			String imgPths[]=new String[imageTokens.countTokens()];
			while(imageTokens.hasMoreTokens()){
				String img=imageTokens.nextToken();
				imgPths[countI]=img.substring(img.lastIndexOf("/")+1);
				countI++;
			}
			itemImagegallery.setAdapter(new ImageAdapter(WaiterView.this,imgPths ));
			TextView ingrdntText=(TextView)infoItemDialog.findViewById(R.id.ingrdntValue);
			ingrdntText.setText(itemDetIngdr);
			TextView prepText=(TextView)infoItemDialog.findViewById(R.id.prepValue);
			prepText.setText(itemDetPrep);
			wlp.copyFrom(infoItemDialog.getWindow().getAttributes());
			wlp.width=LayoutParams.MATCH_PARENT;
			
			wlp.height=LayoutParams.MATCH_PARENT;
			wlp.gravity=Gravity.CENTER_HORIZONTAL;
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
			 
			File fdir=WaiterView.this.getDir("data", Context.MODE_PRIVATE);
			File f=new File(fdir,"menuitem.xml");
			DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
			DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
			Document doc = dBuilder.parse(f);
			currentSelectedSubCat=0;
			subCatLayout.removeAllViews();
			itemLayout.removeAllViews();
			doc.getDocumentElement().normalize();
			NodeList nList = doc.getElementsByTagName("subcat");
			for(int temp=0;temp<nList.getLength();temp++){
		 
				Node nNode = nList.item(temp);
		 
				
				if (nNode.getNodeType() == Node.ELEMENT_NODE) {
		 
					Element eElement = (Element) nNode;
		 
					int subCatID=Integer.parseInt(eElement.getAttribute("id"));
					String subParentID=eElement.getAttribute("parent_id");
					String subCatName=eElement.getAttribute("name");
					String fullImagePath=eElement.getElementsByTagName("images").item(0).getTextContent();
					String subCatImgPath=fullImagePath.substring(fullImagePath.lastIndexOf("/")+1);
					
					
					
					if(tabId.equals(subParentID)){
						
						TableRow subCatRow=new TableRow(this);
						subCatRow.setId(subCatID);
						if(firstcount==0){
							firstid=subCatID;
						}
						
						File imagedir=WaiterView.this.getDir("data", Context.MODE_PRIVATE);
						File image=new File(fdir,subCatImgPath);
						
						
						Typeface tf=Typeface.createFromAsset(getAssets(),"Cuprum.ttf");
						
						TextView subCatText=new TextView(this);
						subCatText.setText(subCatName);
						subCatText.setTextColor(Theme.subcattext);
						subCatText.setTextSize(18);
						subCatText.setPadding(10,0,0,0);
						subCatText.setTypeface(tf);
						subCatText.setGravity(Gravity.CENTER_HORIZONTAL);
						//subCatText.setShadowLayer(0.3f, 0.5f,0.5f,0xff000000);
						subCatRow.addView(subCatText);
						subCatRow.setBackgroundColor(0x00000000);
						subCatRow.setPadding(0,0,0,10);
						subCatRow.setOnClickListener(new SubCatListener());
						subCatLayout.addView(subCatRow);
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
