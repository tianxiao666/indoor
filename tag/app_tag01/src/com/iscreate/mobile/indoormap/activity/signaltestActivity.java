package com.iscreate.mobile.indoormap.activity;

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
import android.graphics.Color;
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
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout.LayoutParams;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.google.gson.Gson;
import com.iscreate.mobile.indoormap.R;
import com.iscreate.mobile.indoormap.widget.PopupNormalWindow;
import com.iscreate.mobile.service.HttpService;
import com.iscreate.mobile.service.ServiceClientInterface;
import com.iscreate.mobile.util.StepDetecter;

public class signaltestActivity extends Activity {
	private Button scanwifi_btn;
	private ListView wifilist_lv;
	private List<ScanResult> wifisScanResult = new ArrayList<ScanResult>();
	private ProgressBar wifiscan_pb;
	private View contentView;
	private boolean isscanning = false;
	private EditText position_et;
	private GsmCellLocation gsmCellLocation;
	private int signalStrengthLevel = 0;
	private List<NeighboringCellInfo> NeighboringCellInfolist;
	public static final int DefaultOncescancount = 1;
	public static final int DefaultOncescanGap = 500;
	public static int Oncescancount = DefaultOncescancount;
	public static int OncescanGap = DefaultOncescanGap;
	private List<List<ScanResult>> scanresultlist = new ArrayList<List<ScanResult>>();
	private ProgressDialog pd;
	private final int WHAT_SUBMIT = 0;
	private final int WHAT_UPDATEACC = 1;
	private static final String ActiontriangulationLocation = "triangulationLocation";
	private static final String ActionsignalHandle = "signalHandle";
	private static String locationAction = "getLocation";
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
					sortScanResultList(wifisScanResult1);
					if (isscanning) {
						wifisScanResult.addAll(wifisScanResult1);
						scanresultlist.add(wifisScanResult1);
						int scancount = scanresultlist.size();
						Toast.makeText(signaltestActivity.this,
								"第" + scancount + "次扫描完成!", Toast.LENGTH_SHORT)
								.show();
						if (scancount >= Oncescancount) {
							// wifisScanResult.addAll(wifisScanResult1);
							wifilist_lv.setAdapter(new WifiListAdapter());
							// scanwifi_btn.setClickable(true);
							wifilist_lv.setVisibility(View.VISIBLE);
							wifiscan_pb.setVisibility(View.GONE);
							isscanning = false;
						} else {
							handler.postDelayed(new Runnable() {
								@Override
								public void run() {
									startWifiScan(signaltestActivity.this);
								}
							}, OncescanGap);
						}
					}
					/*
					 * else { BaseAdapter wifilist_lvadapter = (BaseAdapter)
					 * wifilist_lv .getAdapter(); if (wifilist_lvadapter !=
					 * null) { wifilist_lvadapter.notifyDataSetChanged(); } }
					 */
				}
			}
		}
	};
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

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
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
		contentView = LayoutInflater.from(signaltestActivity.this).inflate(
				R.layout.activity_main, null);
		setContentView(contentView);
		scanwifi_btn = (Button) findViewById(R.id.scanwifi_btn);
		scanwifi_btn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				// Button btn = (Button) v;
				// btn.setClickable(false);
				if (!isscanning) {
					wifisScanResult.clear();
					scanresultlist.clear();
					if (startWifiScan(signaltestActivity.this)) {
						isscanning = true;
						wifilist_lv.setVisibility(View.GONE);
						wifiscan_pb.setVisibility(View.VISIBLE);
					} else {
						Toast.makeText(signaltestActivity.this,
								"wifi没有开启或正在关闭!", Toast.LENGTH_SHORT).show();
					}
				} else {
					Toast.makeText(signaltestActivity.this, "正在扫描中,无法再次启动扫描!",
							Toast.LENGTH_SHORT).show();
				}
			}
		});
		Button submit_btn = (Button) findViewById(R.id.submit_btn);
		submit_btn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				if (isscanning) {
					Toast.makeText(signaltestActivity.this, "正在扫描无法提交!",
							Toast.LENGTH_SHORT).show();
				} else {
					View popupView = LayoutInflater.from(
							signaltestActivity.this).inflate(R.layout.submit,
							null);
					position_et = (EditText) popupView
							.findViewById(R.id.position_et);
					Button submit_btn = (Button) popupView
							.findViewById(R.id.submit_btn);
					submit_btn.setOnClickListener(new View.OnClickListener() {
						@Override
						public void onClick(View v) {
							pd.show();
							new submitThread().start();
						}
					});
					final PopupNormalWindow popup = new PopupNormalWindow(
							popupView, LayoutParams.MATCH_PARENT,
							LayoutParams.MATCH_PARENT);
					position_et.setOnKeyListener(new View.OnKeyListener() {
						@Override
						public boolean onKey(View v, int keyCode, KeyEvent event) {
							switch (keyCode) {
							case KeyEvent.KEYCODE_BACK:
								popup.dismiss();
								return (true);
							case KeyEvent.KEYCODE_ENTER:
								pd.show();
								new submitThread().start();
								return (true);
							}
							return (false);
						}
					});
					popup.showAtLocation(contentView, Gravity.CENTER, 0, 0);
				}
			}
		});
		wifilist_lv = (ListView) findViewById(R.id.wifilist_lv);
		wifiscan_pb = (ProgressBar) findViewById(R.id.wifiscan_pb);
		IntentFilter filter = new IntentFilter();
		filter.addAction(WifiManager.SCAN_RESULTS_AVAILABLE_ACTION);
		registerReceiver(cmdReceiver, filter);
		wifisScanResult.clear();
		scanresultlist.clear();
		if (startWifiScan(signaltestActivity.this)) {
			isscanning = true;
			wifilist_lv.setVisibility(View.GONE);
			wifiscan_pb.setVisibility(View.VISIBLE);
		} else {
			wifilist_lv.setVisibility(View.VISIBLE);
			wifiscan_pb.setVisibility(View.GONE);
			Toast.makeText(signaltestActivity.this, "wifi没有开启或正在关闭!",
					Toast.LENGTH_SHORT).show();
		}
		TelephonyManager telephonyManager = (TelephonyManager) getSystemService(Context.TELEPHONY_SERVICE);
		telephonyManager.listen(new MyPhoneStateListener(),
				PhoneStateListener.LISTEN_SIGNAL_STRENGTHS);
		telephonyManager.listen(new MyPhoneStateListener(),
				PhoneStateListener.LISTEN_CELL_LOCATION);
		NeighboringCellInfolist = telephonyManager.getNeighboringCellInfo();
		telephonyManager.getNetworkType();
		gsmCellLocation = (GsmCellLocation) telephonyManager.getCellLocation();
		// CellLocation cellLocation = gsmCellLocation;
		pd = new ProgressDialog(this);
		pd.setTitle("请稍等");
		pd.setMessage("正在请交...");
		Button setting_btn = (Button) findViewById(R.id.setting_btn);
		setting_btn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				View popupView = LayoutInflater.from(signaltestActivity.this)
						.inflate(R.layout.setting, null);
				final EditText scantime_et = (EditText) popupView
						.findViewById(R.id.scantime_et);
				final EditText timegap_et = (EditText) popupView
						.findViewById(R.id.timegap_et);
				final EditText Actionname_et = (EditText) popupView
						.findViewById(R.id.Actionname_et);
				Button confirm_btn = (Button) popupView
						.findViewById(R.id.confirm_btn);
				Button cancel_btn = (Button) popupView
						.findViewById(R.id.cancel_btn);
				scantime_et.setText("" + Oncescancount);
				timegap_et.setText("" + OncescanGap);
				Actionname_et.setText(locationAction);
				final PopupNormalWindow popup = new PopupNormalWindow(
						popupView, LayoutParams.MATCH_PARENT,
						LayoutParams.MATCH_PARENT);
				scantime_et.setOnKeyListener(new View.OnKeyListener() {
					@Override
					public boolean onKey(View v, int keyCode, KeyEvent event) {
						switch (keyCode) {
						case KeyEvent.KEYCODE_BACK:
							popup.dismiss();
							return (true);
						}
						return (false);
					}
				});
				timegap_et.setOnKeyListener(new View.OnKeyListener() {
					@Override
					public boolean onKey(View v, int keyCode, KeyEvent event) {
						switch (keyCode) {
						case KeyEvent.KEYCODE_BACK:
							popup.dismiss();
							return (true);
						}
						return (false);
					}
				});
				Actionname_et.setOnKeyListener(new View.OnKeyListener() {
					@Override
					public boolean onKey(View v, int keyCode, KeyEvent event) {
						switch (keyCode) {
						case KeyEvent.KEYCODE_BACK:
							popup.dismiss();
							return (true);
						}
						return (false);
					}
				});
				confirm_btn.setOnClickListener(new View.OnClickListener() {
					@Override
					public void onClick(View v) {
						try {
							Oncescancount = Integer.valueOf(scantime_et
									.getText().toString());
							OncescanGap = Integer.valueOf(timegap_et.getText()
									.toString());
							locationAction = Actionname_et.getText().toString();
							popup.dismiss();
						} catch (Exception e) {
							Toast.makeText(signaltestActivity.this,
									e.getMessage(), Toast.LENGTH_SHORT).show();
						}
					}
				});
				cancel_btn.setOnClickListener(new View.OnClickListener() {
					@Override
					public void onClick(View v) {
						popup.dismiss();
					}
				});
				popup.showAtLocation(contentView, Gravity.CENTER, 0, 0);
			}
		});
		stepDetecter = new StepDetecter() {
			@Override
			public void onStep() {
				++steps;
				// updateLog("" + steps);
				Toast.makeText(signaltestActivity.this, "走动" + steps + "步！",
						Toast.LENGTH_SHORT).show();
			}
		};
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
			case WHAT_SUBMIT:
				if (msg.arg2 == 0) {
					Toast.makeText(signaltestActivity.this,
							"提交成功,返回 \"" + (String) msg.obj + "\"!",
							Toast.LENGTH_SHORT).show();
				} else {
					Toast.makeText(signaltestActivity.this,
							"提交失败,原因:\"" + (String) msg.obj + "\"!",
							Toast.LENGTH_SHORT).show();
				}
				pd.dismiss();
				break;
			case WHAT_UPDATEACC:
				stepDetecter.updateData();
				break;
			}
		}
	};

	private class submitThread extends Thread {
		@Override
		public void run() {
			Message msg = handler.obtainMessage();
			try {
				msg.arg2 = 0;
				msg.obj = doSubmit();
			} catch (Exception e) {
				msg.arg2 = 1;
				msg.obj = e.getMessage();
			}
			msg.what = WHAT_SUBMIT;
			handler.sendMessage(msg);
		}
	}

	private String doSubmit() throws Exception {
		if (position_et != null) {
			String positon = position_et.getText().toString();
			if ((positon != null) && (positon.length() > 0)) {
				long nowtime = System.currentTimeMillis();
				long timegap = 0;
				long stepsintimegap = 0;
				if (lastsubmittime != null) {
					timegap = nowtime - lastsubmittime;
					stepsintimegap = steps;
				}
				String submitInfojsonstr = getSubmitInfoJsonStr(scanresultlist,
						gsmCellLocation, signalStrengthLevel,
						NeighboringCellInfolist, positon, timegap,
						stepsintimegap, "" + derection, scanresultlistLast);
				String result = submit(submitInfojsonstr);
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
			} else {
				throw (new Exception("请输入你的位置号!"));
			}
		} else {
			throw (new Exception("请输入你的位置号!"));
		}
	}

	private class MyPhoneStateListener extends PhoneStateListener {

		public void onSignalStrengthsChanged(SignalStrength signalStrength) {
			super.onSignalStrengthsChanged(signalStrength);
			if (signalStrength.isGsm()) {
				if (signalStrength.getGsmSignalStrength() != 99) {
					signalStrengthLevel = todBm(signalStrength
							.getGsmSignalStrength());
				} else {
					signalStrengthLevel = signalStrength.getGsmSignalStrength();
				}
			} else {
				signalStrengthLevel = signalStrength.getCdmaDbm();
			}
		}
	}

	private class WifiListAdapter extends BaseAdapter {
		@Override
		public int getCount() {
			return ((wifisScanResult == null) ? 0 : wifisScanResult.size())
					+ ((gsmCellLocation == null) ? 0 : 1)
					+ ((NeighboringCellInfolist == null) ? 0
							: NeighboringCellInfolist.size());
		}

		@Override
		public View getView(int position, View convertView, ViewGroup parent) {
			if ((wifisScanResult != null)
					&& (position < wifisScanResult.size())) {
				View v = LayoutInflater.from(signaltestActivity.this).inflate(
						R.layout.wifiitem, null);
				TextView id_tv = (TextView) v.findViewById(R.id.id_tv);
				if (getCount() >= 10) {
					if (position + 1 >= 10) {
						id_tv.setText("" + (position + 1));
					} else {
						id_tv.setText("0" + (position + 1));
					}
				} else {
					id_tv.setText("" + (position + 1));
				}
				ScanResult scanResult = wifisScanResult.get(position);
				TextView SSID_tv = (TextView) v.findViewById(R.id.SSID_tv);
				TextView BSSID_tv = (TextView) v.findViewById(R.id.BSSID_tv);
				TextView signal_tv = (TextView) v.findViewById(R.id.signal_tv);
				TextView MAC_tv = (TextView) v.findViewById(R.id.MAC_tv);
				TextView frequency_tv = (TextView) v
						.findViewById(R.id.frequency_tv);
				TextView channel_tv = (TextView) v
						.findViewById(R.id.channel_tv);
				SSID_tv.setText(scanResult.SSID);
				BSSID_tv.setText(scanResult.BSSID);
				signal_tv.setText("" + scanResult.level);
				MAC_tv.setText(scanResult.BSSID);
				frequency_tv.setText("" + scanResult.frequency);
				int channel = FrequencyToChannel(scanResult.frequency);

				if ((channel > 0) && (channel < 15)) {
					channel_tv.setText("" + channel);
				} else {
					channel_tv.setText("" + channel + "(无效)");
					channel_tv.setTextColor(Color.RED);
				}
				return (v);
			} else {
				View v = LayoutInflater.from(signaltestActivity.this).inflate(
						R.layout.cellitem, null);
				TextView id_tv = (TextView) v.findViewById(R.id.id_tv);
				if (getCount() >= 10) {
					if (position + 1 >= 10) {
						id_tv.setText("" + (position + 1));
					} else {
						id_tv.setText("0" + (position + 1));
					}
				} else {
					id_tv.setText("" + (position + 1));
				}
				if (wifisScanResult != null) {
					position = position - wifisScanResult.size();
				}
				TextView LAC_tv = (TextView) v.findViewById(R.id.LAC_tv);
				TextView CID_tv = (TextView) v.findViewById(R.id.CID_tv);
				TextView signal_tv = (TextView) v.findViewById(R.id.signal_tv);
				if (gsmCellLocation != null) {
					LAC_tv.setText("" + gsmCellLocation.getLac());
					CID_tv.setText("" + gsmCellLocation.getCid());
					signal_tv.setText("" + signalStrengthLevel);
				} else {
					--position;
					if ((NeighboringCellInfolist != null)
							&& (position < NeighboringCellInfolist.size())) {
						NeighboringCellInfo neighboringCellInfo = NeighboringCellInfolist
								.get(position);
						if (neighboringCellInfo != null) {
							LAC_tv.setText("" + neighboringCellInfo.getLac());
							CID_tv.setText("" + neighboringCellInfo.getCid());
							signal_tv.setText(""
									+ todBm(neighboringCellInfo.getRssi()));
						}
					}
				}
				return (v);
			}
		}

		@Override
		public long getItemId(int position) {
			return 0;
		}

		@Override
		public Object getItem(int position) {
			return null;
		}
	}

	public static String getSubmitInfoJsonStr(
			List<List<ScanResult>> WifiScanResult,
			GsmCellLocation gsmCellLocation, int signalStrengthLevel,
			List<NeighboringCellInfo> NeighboringCellInfolist, String position,
			long timegap, long steps, String derection,
			List<List<ScanResult>> scanresultlistlast) throws Exception {
		Gson gson = new Gson();
		HashMap<String, String> submitInfo = new HashMap<String, String>();
		List<ScanResult> wifisSRALL = new ArrayList<ScanResult>();
		for (List<ScanResult> wifisSR : WifiScanResult) {
			if (wifisSR != null) {
				wifisSRALL.addAll(wifisSR);
			}
		}
		if (wifisSRALL != null) {
			List<HashMap<String, String>> wifiinfoList = new ArrayList<HashMap<String, String>>();
			for (ScanResult scanResult : wifisSRALL) {
				HashMap<String, String> wifiInfoItem = new HashMap<String, String>();
				if (scanResult.SSID == null) {
					scanResult.SSID = "unknow";
				}
				wifiInfoItem.put("SSID", scanResult.SSID);
				wifiInfoItem.put("BSSID", scanResult.BSSID);
				wifiInfoItem.put("LEVEL", "" + scanResult.level);
				wifiInfoItem.put("MAC", scanResult.BSSID);
				wifiInfoItem.put("CHANNEL", ""
						+ FrequencyToChannel(scanResult.frequency));
				wifiinfoList.add(wifiInfoItem);
			}
			String wifiinfo = gson.toJson(wifiinfoList);
			submitInfo.put("wifiinfo", wifiinfo);
		}
		List<HashMap<String, String>> cellinfoList = new ArrayList<HashMap<String, String>>();
		if (gsmCellLocation != null) {
			HashMap<String, String> cellInfoItem = new HashMap<String, String>();
			cellInfoItem.put("LAC", "" + gsmCellLocation.getLac());
			cellInfoItem.put("CID", "" + gsmCellLocation.getCid());
			cellInfoItem.put("LEVEL", "" + signalStrengthLevel);
			cellinfoList.add(cellInfoItem);
		}
		if (NeighboringCellInfolist != null) {
			for (NeighboringCellInfo neighboringCellInfo : NeighboringCellInfolist) {
				HashMap<String, String> cellInfoItem = new HashMap<String, String>();
				cellInfoItem.put("LAC", "" + neighboringCellInfo.getLac());
				cellInfoItem.put("CID", "" + neighboringCellInfo.getCid());
				cellInfoItem.put("LEVEL",
						"" + todBm(neighboringCellInfo.getRssi()));
				cellinfoList.add(cellInfoItem);
			}
		}
		String cellinfo = gson.toJson(cellinfoList);
		submitInfo.put("cellinfo", cellinfo);
		submitInfo.put("position", "" + position);
		submitInfo.put("TimeGapInMillis", "" + timegap);
		submitInfo.put("steps", "" + steps);
		submitInfo.put("derection", "" + derection);
		if (scanresultlistlast != null) {
			List<ScanResult> wifisSRALLlast = new ArrayList<ScanResult>();
			for (List<ScanResult> wifisSR : scanresultlistlast) {
				if (wifisSR != null) {
					wifisSRALLlast.addAll(wifisSR);
				}
			}
			if (wifisSRALLlast != null) {
				List<HashMap<String, String>> wifiinfoList = new ArrayList<HashMap<String, String>>();
				for (ScanResult scanResult : wifisSRALLlast) {
					HashMap<String, String> wifiInfoItem = new HashMap<String, String>();
					if (scanResult.SSID == null) {
						scanResult.SSID = "unknow";
					}
					wifiInfoItem.put("SSID", scanResult.SSID);
					wifiInfoItem.put("BSSID", scanResult.BSSID);
					wifiInfoItem.put("LEVEL", "" + scanResult.level);
					wifiInfoItem.put("MAC", scanResult.BSSID);
					wifiInfoItem.put("CHANNEL", ""
							+ FrequencyToChannel(scanResult.frequency));
					wifiinfoList.add(wifiInfoItem);
				}
				String wifiinfo = gson.toJson(wifiinfoList);
				submitInfo.put("wifiinfolast", wifiinfo);
			}
		}
		return (gson.toJson(submitInfo));
	}

	public static String submit(String submitInfojsonstr) throws Exception {
		return (HttpService.postRequest(ServiceClientInterface.url + locationAction,
				null, submitInfojsonstr));
	}

	public static boolean startWifiScan(Context context) {
		WifiManager wifiManager = (WifiManager) context
				.getSystemService(WIFI_SERVICE);
		int wifiState = wifiManager.getWifiState();
		switch (wifiState) {
		case WifiManager.WIFI_STATE_ENABLED:
		case WifiManager.WIFI_STATE_ENABLING:
			wifiManager.startScan();
			return (true);
		}
		return (false);
	}

	public static void sortScanResultList(List<ScanResult> wifiSR) {
		if (wifiSR != null) {
			ScanResult SRi;
			ScanResult SRj;
			int count = wifiSR.size();
			int i = 0;
			int j = 0;
			while (i < count) {
				SRi = wifiSR.get(i);
				j = i + 1;
				while (j < count) {
					SRj = wifiSR.get(j);
					if (SRj.level > SRi.level) {
						wifiSR.set(i, SRj);
						wifiSR.set(j, SRi);
						SRi = SRj;
					}
					++j;
				}
				++i;
			}
		}
	}

	public static int todBm(int asu) {
		int dBm = -113 + 2 * asu;
		return (dBm);
	}

	public static int FrequencyToChannel(int frequency) {
		int channel = 0;
		switch (frequency) {
		case 2412:
			channel = 1;
			break;
		case 2417:
			channel = 2;
			break;
		case 2422:
			channel = 3;
			break;
		case 2427:
			channel = 4;
			break;
		case 2432:
			channel = 5;
			break;
		case 2437:
			channel = 6;
			break;
		case 2442:
			channel = 7;
			break;
		case 2447:
			channel = 8;
			break;
		case 2452:
			channel = 9;
			break;
		case 2457:
			channel = 10;
			break;
		case 2462:
			channel = 11;
			break;
		case 2467:
			channel = 12;
			break;
		case 2472:
			channel = 13;
			break;
		case 2484:
			channel = 14;
			break;
		}
		return (channel);
	}
}