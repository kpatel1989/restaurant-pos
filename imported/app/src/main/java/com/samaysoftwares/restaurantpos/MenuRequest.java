package com.samaysoftwares.restaurantpos;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.URL;
import java.util.StringTokenizer;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.apache.http.HttpResponse;
import org.apache.http.HttpStatus;
import org.apache.http.StatusLine;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.w3c.dom.Document;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Bitmap.CompressFormat;
import android.os.AsyncTask;
import android.os.Bundle;
import android.widget.ImageView;

public class MenuRequest extends Activity {
	String path;
	ImageView img1;
	LoadAllProducts l;
	Info info = new Info();
	int flag = 0;
	String act;
	private ProgressDialog pDialog;
	String theme="";

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		act = getIntent().getExtras().get("ActivityToBeStarted").toString();
		l = new LoadAllProducts();
		l.execute();

	}

	class LoadAllProducts extends AsyncTask<String, String, String> {
		String responseString = "";

		@Override
		protected void onPreExecute() {
			super.onPreExecute();

			pDialog = new ProgressDialog(MenuRequest.this);
			pDialog.setMessage("Loading Items. Please wait...");
			pDialog.setIndeterminate(false);
			pDialog.setCancelable(false);
			pDialog.show();
		}

		protected String doInBackground(String... args) {
			// et=(EditText)findViewById(R.id.editText1);
			try {
				HttpClient httpclient = new DefaultHttpClient();
				HttpResponse response;

				response = httpclient
						.execute(new HttpGet("http://" + info.getIP()
								+ "/android_menuitems.php"));

				StatusLine statusLine = response.getStatusLine();
				if (statusLine.getStatusCode() == HttpStatus.SC_OK) {
					ByteArrayOutputStream out = new ByteArrayOutputStream();
					response.getEntity().writeTo(out);
					out.close();
					responseString = out.toString();
				} else {
					// Closes the connection.
					response.getEntity().getContent().close();
					throw new IOException(statusLine.getReasonPhrase());
				}
				
				
				HttpClient httpclient1 = new DefaultHttpClient();
				HttpResponse response1;

				response1 = httpclient1
						.execute(new HttpGet("http://" + info.getIP()
								+ "/android_theme.php"));

				StatusLine statusLine1 = response1.getStatusLine();
				if (statusLine1.getStatusCode() == HttpStatus.SC_OK) {
					ByteArrayOutputStream out1 = new ByteArrayOutputStream();
					response1.getEntity().writeTo(out1);
					out1.close();
					theme = out1.toString();
					new Theme(theme);
				} else {
					// Closes the connection.
					response1.getEntity().getContent().close();
					throw new IOException(statusLine.getReasonPhrase());
				}
				
				
			} catch (ClientProtocolException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			
			File fdir = MenuRequest.this.getDir("data", Context.MODE_PRIVATE);
			File f = new File(fdir, "menuitem.xml");
			try {
				FileOutputStream fos = new FileOutputStream(f);
				fos.write(responseString.getBytes());
				fos.close();
				getimages(f);

			} catch (FileNotFoundException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}

			return responseString;
		}

		/**
		 * After completing background task Dismiss the progress dialog
		 * **/
		protected void onPostExecute(String file_url) {

			
			pDialog.dismiss();
			if (Integer.parseInt(act) == 0) {
				Intent myIntent = new Intent(MenuRequest.this,
						TableView.class);

				MenuRequest.this.startActivity(myIntent);
				MenuRequest.this.finish();
			} else {
				Intent myIntent = new Intent(MenuRequest.this,
						WaiterView.class);
				MenuRequest.this.startActivity(myIntent);
				MenuRequest.this.finish();
			}
		}

		public void getimages(File fXmlFile) {

			try {
				DocumentBuilderFactory dbFactory = DocumentBuilderFactory
						.newInstance();
				DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
				Document doc = dBuilder.parse(fXmlFile);
				doc.getDocumentElement().normalize();
				NodeList nList = doc.getElementsByTagName("images");

				for (int temp = 0; temp < nList.getLength(); temp++) {
					Node nNode = nList.item(temp);
					StringTokenizer st = new StringTokenizer(
							nNode.getTextContent(), ",");
					while (st.hasMoreTokens()) {
						path = st.nextToken();
						URL url = new URL("http://" + info.getIP()
								+ "/" + path);
						InputStream is = (InputStream) url.getContent();
						Bitmap image = BitmapFactory.decodeStream(is);
						File fdir = MenuRequest.this.getDir("data",
								Context.MODE_PRIVATE);
						File fn = new File(fdir, path.substring(path
								.lastIndexOf("/") + 1));
						try {
							FileOutputStream fos = new FileOutputStream(fn);
							image.compress(CompressFormat.JPEG, 100, fos);
							fos.close();

						} catch (FileNotFoundException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						} catch (IOException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}

					}

				}
			} catch (Exception e) {
				e.printStackTrace();
			}

		}
	}

}
