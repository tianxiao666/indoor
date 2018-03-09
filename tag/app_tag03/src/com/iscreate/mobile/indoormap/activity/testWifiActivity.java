package com.iscreate.mobile.indoormap.activity;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Timer;
import java.util.TimerTask;

import android.app.ProgressDialog;
import android.graphics.Color;
import android.net.wifi.ScanResult;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.telephony.NeighboringCellInfo;
import android.telephony.gsm.GsmCellLocation;
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
import com.iscreate.mobile.config.EnvConfig;
import com.iscreate.mobile.indoormap.R;
import com.iscreate.mobile.service.HttpService;
import com.iscreate.mobile.utils.Signal;
import com.iscreate.mobile.widget.PopupNormalWindow;

public class testWifiActivity extends wifiDetecterActivity {
	private Button scanwifi_btn;
	private ListView wifilist_lv;
	private ProgressBar wifiscan_pb;
	private View contentView;
	private EditText position_et;
	private ProgressDialog pd;
	private final int WHAT_SUBMIT = 0;
	private final int WHAT_UPDATEACC = 1;
	private static String locationAction = "getLocation";
	private Timer timer = null;
	private Long lastsubmittime = null;
	private List<List<ScanResult>> scanresultlistLast = null;
	public int signalStrengthLevel = 0;
	public GsmCellLocation gsmCellLocation = null;
	public List<NeighboringCellInfo> NeighboringCellInfolist = null;
	private List<ScanResult> wifisScanResult1 = null;

	@Override
	public void OnHandleScanResult() {
		wifisScanResult1 = getWifisScanResult();
		wifilist_lv.setAdapter(new WifiListAdapter());
		// scanwifi_btn.setClickable(true);
		wifilist_lv.setVisibility(View.VISIBLE);
		wifiscan_pb.setVisibility(View.GONE);
		pd.dismiss();
	}

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		contentView = LayoutInflater.from(testWifiActivity.this).inflate(
				R.layout.wifitest, null);
		setContentView(contentView);
		scanwifi_btn = (Button) findViewById(R.id.scanwifi_btn);
		scanwifi_btn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				// Button btn = (Button) v;
				// btn.setClickable(false);
				if (startWifiScan()) {
					pd.setMessage("正在扫描......");
					pd.show();
				} else {
					Toast.makeText(testWifiActivity.this, "启动wifi扫描失败!",
							Toast.LENGTH_SHORT).show();
				}
			}
		});
		Button submit_btn = (Button) findViewById(R.id.submit_btn);
		submit_btn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				View popupView = LayoutInflater.from(testWifiActivity.this)
						.inflate(R.layout.wifitestsubmit, null);
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
		});
		wifilist_lv = (ListView) findViewById(R.id.wifilist_lv);
		wifiscan_pb = (ProgressBar) findViewById(R.id.wifiscan_pb);
		clearWifisScanResult();
		scanresultlist.clear();
		if (startWifiScan()) {
			wifilist_lv.setVisibility(View.GONE);
			wifiscan_pb.setVisibility(View.VISIBLE);
		} else {
			wifilist_lv.setVisibility(View.VISIBLE);
			wifiscan_pb.setVisibility(View.GONE);
			Toast.makeText(testWifiActivity.this, "启动wifi扫描失败!",
					Toast.LENGTH_SHORT).show();
		}
		pd = new ProgressDialog(this);
		pd.setTitle("请稍等");
		pd.setMessage("正在请交...");
		pd.setCancelable(false);
		Button setting_btn = (Button) findViewById(R.id.setting_btn);
		setting_btn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				View popupView = LayoutInflater.from(testWifiActivity.this)
						.inflate(R.layout.wifitestsetting, null);
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
				scantime_et.setText("" + OnceScanCount);
				timegap_et.setText("" + OnceScanTimeGap);
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
							OnceScanCount = Integer.valueOf(scantime_et
									.getText().toString());
							OnceScanTimeGap = Integer.valueOf(timegap_et
									.getText().toString());
							locationAction = Actionname_et.getText().toString();
							popup.dismiss();
						} catch (Exception e) {
							Toast.makeText(testWifiActivity.this,
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
	}

	@Override
	protected void onDestroy() {
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
			timer.schedule(task, 0, stepDetecterByNetActivity.INTERVAL_MS);
		}
	}

	private Handler handler = new Handler() {
		@Override
		public void handleMessage(Message msg) {
			switch (msg.what) {
			case WHAT_SUBMIT:
				if (msg.arg2 == 0) {
					Toast.makeText(testWifiActivity.this,
							"提交成功,返回 \"" + (String) msg.obj + "\"!",
							Toast.LENGTH_SHORT).show();
				} else {
					Toast.makeText(testWifiActivity.this,
							"提交失败,原因:\"" + (String) msg.obj + "\"!",
							Toast.LENGTH_SHORT).show();
				}
				pd.dismiss();
				break;
			case WHAT_UPDATEACC:
				updateStepDetecter();
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
					stepsintimegap = getSteps();
				}
				String submitInfojsonstr = getSubmitInfoJsonStr(scanresultlist,
						gsmCellLocation, signalStrengthLevel,
						NeighboringCellInfolist, positon, timegap,
						stepsintimegap, "" + getDirection(), scanresultlistLast);
				String result = submit(submitInfojsonstr);
				if (lastsubmittime == null) {
					initTimer();
					lastsubmittime = System.currentTimeMillis();
				} else {
					resetStep();
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

	private class WifiListAdapter extends BaseAdapter {
		@Override
		public int getCount() {
			return ((wifisScanResult1 == null) ? 0 : wifisScanResult1.size())
					+ ((gsmCellLocation == null) ? 0 : 1)
					+ ((NeighboringCellInfolist == null) ? 0
							: NeighboringCellInfolist.size());
		}

		@Override
		public View getView(int position, View convertView, ViewGroup parent) {
			if ((wifisScanResult1 != null)
					&& (position < wifisScanResult1.size())) {
				View v = LayoutInflater.from(testWifiActivity.this).inflate(
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
				ScanResult scanResult = wifisScanResult1.get(position);
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
				int channel = Signal.toChannel(scanResult.frequency);

				if ((channel > 0) && (channel < 15)) {
					channel_tv.setText("" + channel);
				} else {
					channel_tv.setText("" + channel + "(无效)");
					channel_tv.setTextColor(Color.RED);
				}
				return (v);
			} else {
				View v = LayoutInflater.from(testWifiActivity.this).inflate(
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
				if (wifisScanResult1 != null) {
					position = position - wifisScanResult1.size();
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
									+ Signal.todBm(neighboringCellInfo
											.getRssi()));
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

	/**
	 * 查找mac相等的ScanResult
	 */
	public static Integer getScanResultByMac(List<ScanResult> srList, String mac) {
		if ((srList != null) && (mac != null)) {
			int count = srList.size();
			int i = 0;
			while (i < count) {
				if (mac.compareToIgnoreCase(srList.get(i).BSSID) == 0) {
					return (i);
				}
				++i;
			}
		}
		return (null);
	}

	/**
	 * 查找结果转找为JSON字符串
	 */
	public static String getWifiListJsonStr(
			List<List<ScanResult>> WifiScanResultListList) {
		if (WifiScanResultListList != null) {
			List<ScanResult> WifiScanResultList = new ArrayList<ScanResult>();
			List<ScanResult> scanResultList = new ArrayList<ScanResult>();
			for (List<ScanResult> scanResultListTemp : WifiScanResultListList) {
				if (scanResultListTemp != null) {
					WifiScanResultList.addAll(scanResultListTemp);
					for (ScanResult sr : scanResultListTemp) {
						Integer pos = getScanResultByMac(scanResultList,
								sr.BSSID);
						if (pos != null) {
							ScanResult possr = scanResultList.get(pos);
							possr.level = (possr.level + sr.level) / 2;
							scanResultList.set(pos, possr);
						} else {
							scanResultList.add(sr);
						}
					}
				}
			}
			List<HashMap<String, String>> wifiInfoMapList = new ArrayList<HashMap<String, String>>();
			for (ScanResult scanResult : scanResultList) {
				HashMap<String, String> wifiInfoMap = new HashMap<String, String>();
				if (scanResult.SSID == null) {
					scanResult.SSID = "unknow";
				}
				wifiInfoMap.put("EQUT_SSID", scanResult.SSID);
				wifiInfoMap.put("MAC_BSSID", scanResult.BSSID);
				wifiInfoMap.put("FREQUENCY", "" + scanResult.frequency);
				wifiInfoMap.put("CHANNEL",
						"" + Signal.toChannel(scanResult.frequency));
				wifiInfoMap.put("LEVEL", "" + scanResult.level);
				wifiInfoMapList.add(wifiInfoMap);
			}
			return ((new Gson()).toJson(wifiInfoMapList));
		}
		return (null);
	}

	public static String getSubmitInfoJsonStr(
			List<List<ScanResult>> WifiScanResult,
			GsmCellLocation gsmCellLocation, int signalStrengthLevel,
			List<NeighboringCellInfo> NeighboringCellInfolist, String position,
			long timegap, long steps, String derection,
			List<List<ScanResult>> scanresultlistlast) throws Exception {
		Gson gson = new Gson();
		HashMap<String, String> submitInfo = new HashMap<String, String>();
		String WifiListJsonStr = testWifiActivity
				.getWifiListJsonStr(WifiScanResult);
		if (WifiListJsonStr != null) {
			submitInfo.put("wifiinfo", WifiListJsonStr);
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
						"" + Signal.todBm(neighboringCellInfo.getRssi()));
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
					wifiInfoItem.put("CHANNEL",
							"" + Signal.toChannel(scanResult.frequency));
					wifiinfoList.add(wifiInfoItem);
				}
				String wifiinfo = gson.toJson(wifiinfoList);
				submitInfo.put("wifiinfolast", wifiinfo);
			}
		}
		return (gson.toJson(submitInfo));
	}

	public static String submit(String submitInfojsonstr) throws Exception {
		return (HttpService.post(EnvConfig.getUrl() + locationAction, null,
				submitInfojsonstr));
	}
}