package com.iscreate.mobile.indoormap.activity;

import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Timer;
import java.util.TimerTask;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.pm.PackageInfo;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Matrix;
import android.graphics.PixelFormat;
import android.graphics.drawable.PictureDrawable;
import android.hardware.Sensor;
import android.hardware.SensorEvent;
import android.hardware.SensorEventListener;
import android.hardware.SensorManager;
import android.net.wifi.ScanResult;
import android.net.wifi.WifiManager;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.telephony.NeighboringCellInfo;
import android.telephony.PhoneStateListener;
import android.telephony.SignalStrength;
import android.telephony.TelephonyManager;
import android.telephony.gsm.GsmCellLocation;
import android.util.Log;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.iscreate.mobile.indoormap.R;
import com.iscreate.mobile.indoormap.widget.MapItem;
import com.iscreate.mobile.indoormap.widget.PopupNormalWindow;
import com.iscreate.mobile.indoormap.widget.indoorimageview;
import com.iscreate.mobile.service.GsonService;
import com.iscreate.mobile.service.ServiceClientInterface;
import com.iscreate.mobile.util.StepDetecter;
import com.larvalabs.svgandroid.SVG;
import com.larvalabs.svgandroid.SVGParser;

public class indoormapActivity extends Activity {
	private indoorimageview iiv = null;
	private final int WHAT_LOADMAP = 0;
	private final int WHAT_SHOWMAP = 1;
	private final int WHAT_SUBMIT = 2;
	private final int WHAT_LOADEDBUILDINGFLOOR = 3;
	private final int WHAT_LOADEDBUILDINGFLOORMAP = 4;
	private final int WHAT_UPDATEACC = 5;
	private final int WHAT_SETSVG = 6;
	// private final int WHAT_LOADEDBUILDINGFLOORFASTSEARCHKEY = 5;
	private List<ScanResult> wifisScanResult = new ArrayList<ScanResult>();
	private View contentView;
	private boolean isscanning = false;
	// private EditText position_et;
	private GsmCellLocation gsmCellLocation;
	private int signalStrengthLevel = 0;
	private List<NeighboringCellInfo> NeighboringCellInfolist;
	// private int Oncescancount = signaltestActivity.DefaultOncescancount;
	// private int OncescanGap = signaltestActivity.DefaultOncescanGap;
	private List<List<ScanResult>> scanresultlist = new ArrayList<List<ScanResult>>();
	private ProgressDialog pd;
	private TextView locatione_tv;
	private TextView result_tv;
	private Button floors_btn = null;
	private List<HashMap<String, String>> IndoormapBuildingFloorList = null;
	private SensorManager sensorManager = null;
	private boolean hasAccelerometer = false;
	private StepDetecter stepDetecter = null;
	private long steps = 0;
	private Timer timer = null;
	private final long INTERVAL_MS = 1000 / 30;
	private Long lastsubmittime = null;
	private float derection = 0;
	private boolean hasOrientation = false;
	private List<List<ScanResult>> scanresultlistLast = null;
	private String BUILDING_ID = null;
	private String BUILDING_NAME = null;
	private String FLOOR_ID = null;
	private String svgsrc = null;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		BUILDING_ID = getIntent().getStringExtra("BUILDING_ID");
		BUILDING_NAME = getIntent().getStringExtra("BUILDING_NAME");
		sensorManager = (SensorManager) getSystemService(SENSOR_SERVICE);
		List<Sensor> sensorlist = sensorManager.getSensorList(Sensor.TYPE_ALL);
		for (Sensor sensor : sensorlist) {
			if (sensor.getType() == Sensor.TYPE_ACCELEROMETER) {
				hasAccelerometer = true;
				if (hasOrientation) {
					break;
				}
			}
			if (sensor.getType() == Sensor.TYPE_ORIENTATION) {
				hasOrientation = true;
				if (hasAccelerometer) {
					break;
				}
			}
		}
		if (!hasAccelerometer) {
			Toast.makeText(this, "该设备没有加速力传感器,无法计算移动步数!", Toast.LENGTH_SHORT)
					.show();
		}
		if (!hasOrientation) {
			Toast.makeText(this, "该设备没有方向传感器,无法计算方向!", Toast.LENGTH_SHORT)
					.show();
		}
		contentView = LayoutInflater.from(indoormapActivity.this).inflate(
				R.layout.indoormapmain, null);
		setContentView(contentView);
		TextView title_tv = (TextView) findViewById(R.id.title);
		title_tv.setText(BUILDING_NAME);
		iiv = (indoorimageview) findViewById(R.id.indoor_iv);
		Button more_btn = (Button) findViewById(R.id.more_btn);
		more_btn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				LinearLayout popupView = (LinearLayout) LayoutInflater.from(
						indoormapActivity.this)
						.inflate(R.layout.moremenu, null);
				final PopupNormalWindow popupWin = new PopupNormalWindow(
						popupView, LinearLayout.LayoutParams.WRAP_CONTENT,
						LinearLayout.LayoutParams.WRAP_CONTENT);
				popupWin.setAnimationStyle(R.style.popupbtt);
				Button about_btn = (Button) popupView
						.findViewById(R.id.about_btn);
				Button update_btn = (Button) popupView
						.findViewById(R.id.update_btn);
				final Button LayerSelection_btn = (Button) popupView
						.findViewById(R.id.LayerSelection_btn);
				Button SignalTestMenu_btn = (Button) popupView
						.findViewById(R.id.SignalTestMenu_btn);
				final LinearLayout submenu_ll = (LinearLayout) popupView
						.findViewById(R.id.submenu_ll);
				about_btn.setOnClickListener(new View.OnClickListener() {
					@Override
					public void onClick(View v) {
						LayerSelection_btn.setTextColor(Color.BLACK);
						submenu_ll.setVisibility(View.GONE);
					}
				});
				update_btn.setOnClickListener(new View.OnClickListener() {
					@Override
					public void onClick(View v) {
						LayerSelection_btn.setTextColor(Color.BLACK);
						submenu_ll.setVisibility(View.GONE);
					}
				});
				LayerSelection_btn
						.setOnClickListener(new View.OnClickListener() {
							@Override
							public void onClick(View v) {
								if (submenu_ll.getVisibility() != View.VISIBLE) {
									LayerSelection_btn.setTextColor(Color.RED);
									submenu_ll.setVisibility(View.VISIBLE);
								} else {
									LayerSelection_btn
											.setTextColor(Color.BLACK);
									submenu_ll.setVisibility(View.GONE);
								}
							}
						});
				SignalTestMenu_btn
						.setOnClickListener(new View.OnClickListener() {
							@Override
							public void onClick(View v) {
								Intent intent = new Intent(
										indoormapActivity.this,
										signaltestActivity.class);
								LayerSelection_btn.setTextColor(Color.BLACK);
								submenu_ll.setVisibility(View.GONE);
								startActivity(intent);
								popupWin.dismiss();
							}
						});
				popupWin.showAtLocation(v, Gravity.LEFT | Gravity.BOTTOM, 0,
						v.getHeight());
			}
		});
		Button search_btn = (Button) findViewById(R.id.search_btn);
		search_btn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				// new
				// getIndoormapBuildingFloorFastSearchKeyListThread().start();
				gotoSearch();
			}
		});
		Button svg_btn = (Button) findViewById(R.id.svg_btn);
		svg_btn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				// Intent intent = new Intent(
				// indoormapActivity.this,
				// com.iscreate.mobile.indoormap.activity.SvgActivity.class);
				// startActivityForResult(intent, 1);
				//iiv.reset();
			}
		});
		Button location_btn = (Button) findViewById(R.id.location_btn);
		location_btn.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				if (!isscanning) {
					pd.setMessage("正在扫描......");
					pd.show();
					wifisScanResult.clear();
					scanresultlist.clear();
					if (signaltestActivity
							.startWifiScan(indoormapActivity.this)) {
						int scancount = scanresultlist.size();
						pd.setMessage("第" + (scancount + 1) + "次扫描中......");
						isscanning = true;
					} else {
						Toast.makeText(indoormapActivity.this,
								"wifi没有开启或正在关闭!", Toast.LENGTH_SHORT).show();
					}
				} else {
					Toast.makeText(indoormapActivity.this, "正在扫描中,无法再次启动扫描!",
							Toast.LENGTH_SHORT).show();
				}
			}
		});
		floors_btn = (Button) findViewById(R.id.floors_btn);
		floors_btn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				if (IndoormapBuildingFloorList == null) {
					pd.setMessage("正在获取楼层列表......");
					pd.show();
				}
				new getIndoormapBuildingFloorListThread().start();
			}
		});
		result_tv = (TextView) findViewById(R.id.result_tv);
		locatione_tv = (TextView) findViewById(R.id.locatione_tv);
		pd = new ProgressDialog(indoormapActivity.this);
		pd.setTitle("请稍后");
		pd.setCancelable(false);
		IntentFilter filter = new IntentFilter();
		filter.addAction(WifiManager.SCAN_RESULTS_AVAILABLE_ACTION);
		registerReceiver(cmdReceiver, filter);
		wifisScanResult.clear();
		scanresultlist.clear();
		TelephonyManager telephonyManager = (TelephonyManager) getSystemService(Context.TELEPHONY_SERVICE);
		telephonyManager.listen(new MyPhoneStateListener(),
				PhoneStateListener.LISTEN_SIGNAL_STRENGTHS);
		telephonyManager.listen(new MyPhoneStateListener(),
				PhoneStateListener.LISTEN_CELL_LOCATION);
		NeighboringCellInfolist = telephonyManager.getNeighboringCellInfo();
		gsmCellLocation = (GsmCellLocation) telephonyManager.getCellLocation();
		if (!isscanning) {
			if (false) {
				pd.setMessage("正在扫描......");
				pd.show();
				wifisScanResult.clear();
				scanresultlist.clear();
				if (signaltestActivity.startWifiScan(indoormapActivity.this)) {
					isscanning = true;
				} else {
					isscanning = false;
					pd.dismiss();
					Toast.makeText(indoormapActivity.this, "wifi没有开启或正在关闭!",
							Toast.LENGTH_SHORT).show();
				}
			}
		} else {
			Toast.makeText(indoormapActivity.this, "正在扫描中,无法再次启动扫描!",
					Toast.LENGTH_SHORT).show();
		}
		stepDetecter = new StepDetecter() {
			@Override
			public void onStep() {
				++steps;
				// updateLog("" + steps);
				Toast.makeText(indoormapActivity.this, "走动" + steps + "步！",
						Toast.LENGTH_SHORT).show();
			}
		};
		// pd.setMessage("正在获取SVG源代码......");
		// pd.show();
		// new getSvgThread().start();
	}

	private indoormapActivity getThis() {
		return (indoormapActivity.this);
	}

	@Override
	protected void onResume() {
		super.onResume();
		sensorManager.registerListener(sensorEventListener,
				sensorManager.getDefaultSensor(Sensor.TYPE_ACCELEROMETER),
				SensorManager.SENSOR_DELAY_NORMAL);
		sensorManager.registerListener(sensorEventListener,
				sensorManager.getDefaultSensor(Sensor.TYPE_ORIENTATION),
				SensorManager.SENSOR_DELAY_NORMAL);
	}

	@Override
	protected void onPause() {
		sensorManager.unregisterListener(sensorEventListener);
		super.onPause();
	}

	@Override
	protected void onDestroy() {
		unregisterReceiver(cmdReceiver);
		if (timer != null) {
			timer.cancel();
			timer.purge();
		}
		super.onDestroy();
	}

	private void initTimer() {
		if (timer == null) {
			timer = new Timer("UpdateData", false);
			TimerTask task = new TimerTask() {
				@Override
				public void run() {
					Message msg = handler.obtainMessage();
					msg.what = WHAT_UPDATEACC;
					handler.sendMessage(msg);
				}
			};
			timer.schedule(task, 0, INTERVAL_MS);
		}
	}

	private Handler handler = new Handler() {
		@Override
		public void handleMessage(Message msg) {
			switch (msg.what) {
			case WHAT_LOADMAP:
				MapItem mapitem = (MapItem) msg.obj;
				iiv.addLocation(mapitem.coordx, mapitem.coordy);
				iiv.setImageBitmap(mapitem.bm);
				iiv.setCircle(mapitem.resultMap);
				pd.dismiss();
				break;
			case WHAT_SHOWMAP:
				break;
			case WHAT_SUBMIT:
				if (msg.arg2 == 0) {
					MapItem mi = (MapItem) msg.obj;
					if (mi != null) {
						result_tv.setText(mi.result);
						locatione_tv.setText(mi.region);
						pd.setMessage("正在下载地图......");
						pd.show();
						new LoadMapThread(mi).start();
					} else {
						result_tv.setText("MapItem为空");
						locatione_tv.setText("定位错误");
					}
				} else {
					String[] msgresult = (String[]) msg.obj;
					Toast.makeText(indoormapActivity.this, msgresult[0],
							Toast.LENGTH_SHORT).show();
					result_tv.setText(msgresult[1]);
					locatione_tv.setText("定位错误");
					pd.dismiss();
				}
				break;
			case WHAT_LOADEDBUILDINGFLOOR:
				if (msg.arg2 == 0) {
					IndoormapBuildingFloorList = (List<HashMap<String, String>>) msg.obj;
					if (((IndoormapBuildingFloorList == null) || (IndoormapBuildingFloorList
							.size() == 0))) {
						Toast.makeText(indoormapActivity.this, "没有楼层！",
								Toast.LENGTH_SHORT).show();
					} else {
						ListView popupView = (ListView) LayoutInflater.from(
								indoormapActivity.this).inflate(
								R.layout.floorsmenu, null);
						popupView.setAdapter(new BaseAdapter() {

							@Override
							public int getCount() {
								return ((IndoormapBuildingFloorList == null) ? 0
										: IndoormapBuildingFloorList.size());
							}

							@Override
							public View getView(int position, View convertView,
									ViewGroup parent) {
								HashMap<String, String> map = IndoormapBuildingFloorList
										.get(position);
								TextView tv = new TextView(
										indoormapActivity.this);
								tv.setText(map.get("FLOOR_NAME"));
								tv.setTag(map.get("FLOOR_ID"));
								tv.setBackgroundResource(R.drawable.bg_floorsmenuitem);
								tv.setGravity(Gravity.CENTER);
								tv.setTextColor(Color.WHITE);
								return (tv);
							}

							@Override
							public long getItemId(int position) {
								return (0);
							}

							@Override
							public Object getItem(int position) {
								return (null);
							}
						});
						final PopupNormalWindow popupWin = new PopupNormalWindow(
								popupView, 350,
								LinearLayout.LayoutParams.WRAP_CONTENT);
						popupView
								.setOnItemClickListener(new AdapterView.OnItemClickListener() {
									@Override
									public void onItemClick(
											AdapterView<?> groupview,
											View view, int position, long arg3) {
										getThis().pd
												.setMessage("正在获取平面图信息......");
										getThis().pd.show();
										FLOOR_ID = (String) view.getTag();
										new getIndoormapBuildingFloorSvgThread()
												.start();
										popupWin.dismiss();
									}
								});
						popupWin.setAnimationStyle(R.style.popupttb);
						popupWin.showAsDropDown(floors_btn, 0, 0);
					}
				} else {
					Toast.makeText(indoormapActivity.this, (String) msg.obj,
							Toast.LENGTH_SHORT).show();
				}
				pd.dismiss();
				break;
			case WHAT_LOADEDBUILDINGFLOORMAP:
				if (msg.arg2 == 0) {
					HashMap<String, String> svginfo = (HashMap<String, String>) msg.obj;
					String error = svginfo.get("error");
					if (error == null) {
						svgsrc = svginfo.get("SVGSRC");
						if (svgsrc == null) {
							error = "SVG源码为空！";
						} else {
							pd.setMessage("正在生成图片.....");
							pd.show();
							new setSvgThread().start();
						}
					}
					if (error != null) {
						pd.dismiss();
						Toast.makeText(indoormapActivity.this, error,
								Toast.LENGTH_SHORT).show();
					}
				} else {
					pd.dismiss();
					Toast.makeText(indoormapActivity.this, (String) msg.obj,
							Toast.LENGTH_SHORT).show();
				}
				break;
			case WHAT_UPDATEACC:
				stepDetecter.updateData();
				break;
			case WHAT_SETSVG:
				if (msg.arg2 == 0) {
					Bitmap svgbm = (Bitmap) msg.obj;
					 iiv.setImageBitmap(svgbm);
					//iiv.setImageResource(R.drawable.sssssss);
					iiv.addLocation(1, 1);
					iiv.addLocation(50, 1);
					iiv.addLocation(50, 100);
					iiv.addLocation(100, 100);
					iiv.addLocation(100, 150);
					iiv.addLocation(150, 150);
				} else {
					Toast.makeText(indoormapActivity.this, (String) msg.obj,
							Toast.LENGTH_SHORT).show();
				}
				pd.dismiss();
				break;
			}
		}
	};

	private Bitmap SvgToBitmap(String svgsrc) {
		if (svgsrc != null) {
			// SVGParser.setZoomValue(0.5f);
			SVG svg = SVGParser.getSVGFromString(svgsrc);
			PictureDrawable drawable = svg.createPictureDrawable();
			int w = drawable.getIntrinsicWidth();
			int h = drawable.getIntrinsicHeight();
			Bitmap bitmap = Bitmap
					.createBitmap(
							w,
							h,
							drawable.getOpacity() != PixelFormat.OPAQUE ? Bitmap.Config.ARGB_8888
									: Bitmap.Config.RGB_565);
			Canvas canvas = new Canvas(bitmap);
			// canvas.setBitmap(bitmap);
			drawable.setBounds(0, 0, w, h);
			drawable.draw(canvas);
			return (bitmap);
			// iiv.setBackgroundDrawable(drawable);
		}
		return (null);
	}

	private class LoadMapThread extends Thread {
		private MapItem mi;

		public LoadMapThread(MapItem mi) {
			this.mi = mi;
		}

		@Override
		public void run() {
			Message msg = handler.obtainMessage();
			mi.bm = getURLBitmap(mi.url);
			if (mi.bm != null) {
				mi.coordx = (int) (mi.bm.getWidth() * mi.x / mi.mapWidth);
				mi.coordy = (int) (mi.bm.getHeight() * mi.y / mi.mapheight);
				mi.coordx = (int) (mi.x * 28);
				mi.coordy = (int) (mi.y * 28);
			}
			msg.obj = mi;
			msg.what = WHAT_LOADMAP;
			handler.sendMessage(msg);
		}
	};

	private Bitmap getURLBitmap(String urlstr) {
		try {
			URL url = new URL(urlstr);
			HttpURLConnection httpurlc = (HttpURLConnection) url
					.openConnection();
			httpurlc.setRequestProperty("cookie", null);
			InputStream is = httpurlc.getInputStream();
			Bitmap bitmap = BitmapFactory.decodeStream(is);
			is.close();
			httpurlc.disconnect();
			return (bitmap);
		} catch (Exception e) {
		}
		return (null);
	}

	private class getIndoormapBuildingFloorListThread extends Thread {
		@Override
		public void run() {
			Message msg = handler.obtainMessage();
			msg.what = WHAT_LOADEDBUILDINGFLOOR;
			try {
				if (IndoormapBuildingFloorList == null) {
					msg.obj = getIndoormapBuildingFloorList();
				} else {
					msg.obj = IndoormapBuildingFloorList;
				}
				msg.arg2 = 0;
			} catch (Exception e) {
				msg.obj = "获取楼层错误:" + e.getMessage();
				msg.arg2 = 1;
			}
			handler.sendMessage(msg);
		}
	}

	private class setSvgThread extends Thread {
		@Override
		public void run() {
			Message msg = handler.obtainMessage();
			msg.what = WHAT_SETSVG;
			try {
				msg.obj = SvgToBitmap(svgsrc);
				msg.arg2 = 0;
			} catch (Exception e) {
				msg.obj = "获取SVG代码错误:" + e.getMessage();
				msg.arg2 = 1;
			}
			handler.sendMessage(msg);
		}
	}

	private List<HashMap<String, String>> getIndoormapBuildingFloorList()
			throws Exception {
		int actionID = ServiceClientInterface.ID_ACTION_getIndoormapBuildingFloorList;
		String parmas[] = { BUILDING_ID };
		String result = ServiceClientInterface.postRequest(actionID, parmas);
		List<HashMap<String, String>> resultmap = GsonService
				.gsonGetListHashMap(result);
		return (resultmap);
	}

	private class getIndoormapBuildingFloorSvgThread extends Thread {
		@Override
		public void run() {
			Message msg = handler.obtainMessage();
			msg.what = WHAT_LOADEDBUILDINGFLOORMAP;
			try {
				msg.obj = getIndoormapBuildingFloorSvg();
				msg.arg2 = 0;
			} catch (Exception e) {
				msg.obj = "获取楼层地图错误:" + e.getMessage();
				msg.arg2 = 1;
			}
			handler.sendMessage(msg);
		}
	}

	private HashMap<String, String> getIndoormapBuildingFloorSvg()
			throws Exception {
		int actionID = ServiceClientInterface.ID_ACTION_getIndoormapBuildingFloorSvg;
		String parmas[] = { FLOOR_ID, BUILDING_ID };
		String result = ServiceClientInterface.postRequest(actionID, parmas);
		HashMap<String, String> resultmap = GsonService.gsonGetHashMap(result);
		return (resultmap);
	}

	private HashMap<String, String> checkHasNewVersion() throws Exception {
		PackageInfo pi = getAppPackageInfo(this);
		int actionID = ServiceClientInterface.ID_ACTION_checkHasNewVersion;
		String parmas[] = { "" + pi.packageName, "" + pi.versionCode };
		String result = ServiceClientInterface.postRequest(actionID, parmas);
		HashMap<String, String> resultmap = GsonService.gsonGetHashMap(result);
		return (resultmap);
	}

	public static PackageInfo getAppPackageInfo(Context context)
			throws Exception {
		return (context.getPackageManager().getPackageInfo(
				context.getPackageName(), 0));
	}

	private void gotoSearch() {
		Intent intent = new Intent(indoormapActivity.this, SearchActivity.class);
		startActivityForResult(intent, 1);
	}

	private SensorEventListener sensorEventListener = new SensorEventListener() {

		@Override
		public void onSensorChanged(SensorEvent event) {
			switch (event.sensor.getType()) {
			case Sensor.TYPE_ACCELEROMETER:
				stepDetecter.setLastAcc(event.values);
				// analyseAccelerometerEvent(event);
				break;
			case Sensor.TYPE_ORIENTATION:
				// String DerectionString = getDerectionString(event);
				derection = event.values[SensorManager.DATA_X];
				Log.e("derection", "" + derection);
				break;
			}
		}

		@Override
		public void onAccuracyChanged(Sensor sensor, int accuracy) {
		}
	};
	private BroadcastReceiver cmdReceiver = new BroadcastReceiver() {
		@Override
		public void onReceive(Context context, Intent intent) {
			if (intent != null) {
				String action = intent.getAction();
				if (action
						.equalsIgnoreCase(WifiManager.SCAN_RESULTS_AVAILABLE_ACTION)) {
					WifiManager wifiManager = (WifiManager) getSystemService(WIFI_SERVICE);
					List<ScanResult> wifisScanResult1 = wifiManager
							.getScanResults();
					signaltestActivity.sortScanResultList(wifisScanResult1);
					if (isscanning) {
						wifisScanResult.addAll(wifisScanResult1);
						scanresultlist.add(wifisScanResult1);
						int scancount = scanresultlist.size();
						if (scancount >= signaltestActivity.Oncescancount) {
							isscanning = false;
							pd.setMessage("正在提交......");
							// pd.dismiss();
							new SubmitThread().start();
						} else {
							pd.setMessage("第" + (scancount + 1) + "次扫描中......");
							handler.postDelayed(new Runnable() {
								@Override
								public void run() {
									signaltestActivity
											.startWifiScan(indoormapActivity.this);
								}
							}, signaltestActivity.OncescanGap);
						}
					}
				}
			}
		}
	};

	private class SubmitThread extends Thread {
		@Override
		public void run() {
			Message msg = handler.obtainMessage();
			String result = null;
			try {
				msg.arg2 = 0;
				result = doSubmit();
			} catch (Exception e) {
				msg.arg2 = 1;
				String[] msgresult = { "获取返回值出错:" + e.getMessage(), "无返回值" };
				msg.obj = msgresult;
			}
			if (msg.arg2 == 0) {
				try {
					msg.obj = AnlayResult(result);
				} catch (Exception e) {
					msg.arg2 = 1;
					String[] msgresult = { "解析返回值出错:" + e.getMessage(), result };
					msg.obj = msgresult;
				}
			}
			msg.what = WHAT_SUBMIT;
			handler.sendMessage(msg);
		}
	}

	private String doSubmit() throws Exception {
		String positon = "" + System.currentTimeMillis();
		long nowtime = System.currentTimeMillis();
		long timegap = 0;
		long stepsintimegap = 0;
		if (lastsubmittime != null) {
			timegap = nowtime - lastsubmittime;
			stepsintimegap = steps;
		}
		String submitInfojsonstr = signaltestActivity.getSubmitInfoJsonStr(
				scanresultlist, gsmCellLocation, signalStrengthLevel,
				NeighboringCellInfolist, positon, timegap, stepsintimegap, ""
						+ derection, scanresultlistLast);
		String result = signaltestActivity.submit(submitInfojsonstr);
		if (lastsubmittime == null) {
			initTimer();
			lastsubmittime = System.currentTimeMillis();
		} else {
			steps = 0;
			lastsubmittime = nowtime;

		}
		if (scanresultlistLast == null) {
			scanresultlistLast = new ArrayList<List<ScanResult>>();
		} else {
			scanresultlistLast.clear();
		}
		scanresultlistLast.addAll(scanresultlist);
		return (result);
	}

	private MapItem AnlayResult(String result) throws Exception {
		HashMap<String, HashMap<String, String>> resultMap = GsonService
				.gsonGetHashMapHashMap(result);
		HashMap<String, String> resultmap = resultMap.get("return");
		MapItem mi = new MapItem();
		mi.result = result;
		mi.x = Float.valueOf(resultmap.get("x"));
		mi.y = Float.valueOf(resultmap.get("y"));
		mi.mapWidth = Float.valueOf(resultmap.get("width"));
		mi.mapheight = Float.valueOf(resultmap.get("height"));
		mi.region = resultmap.get("region");
		mi.url = resultmap.get("photo_url");
		mi.resultMap = resultMap;
		return (mi);
	}

	private class MyPhoneStateListener extends PhoneStateListener {

		public void onSignalStrengthsChanged(SignalStrength signalStrength) {
			super.onSignalStrengthsChanged(signalStrength);
			if (signalStrength.isGsm()) {
				if (signalStrength.getGsmSignalStrength() != 99) {
					signalStrengthLevel = signaltestActivity
							.todBm(signalStrength.getGsmSignalStrength());
				} else {
					signalStrengthLevel = signalStrength.getGsmSignalStrength();
				}
			} else {
				signalStrengthLevel = signalStrength.getCdmaDbm();
			}
		}
	}
}