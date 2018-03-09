package com.iscreate.mobile.indoormap.activity;

import java.util.HashMap;
import java.util.List;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.drawable.Drawable;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.baidu.location.BDLocation;
import com.baidu.location.BDLocationListener;
import com.baidu.location.LocationClient;
import com.baidu.location.LocationClientOption;
import com.baidu.mapapi.map.ItemizedOverlay;
import com.baidu.mapapi.map.LocationData;
import com.baidu.mapapi.map.MapController;
import com.baidu.mapapi.map.MapView;
import com.baidu.mapapi.map.MyLocationOverlay;
import com.baidu.mapapi.map.Overlay;
import com.baidu.mapapi.map.OverlayItem;
import com.baidu.platform.comapi.basestruct.GeoPoint;
import com.iscreate.mobile.baidu.BaiduApplication;
import com.iscreate.mobile.indoormap.R;
import com.iscreate.mobile.service.GsonService;
import com.iscreate.mobile.service.ServiceClientInterface;

public class baidumapActivity extends Activity {
	private MapView baidumap_mv = null;
	private MapController mMapController = null;
	private LocationClient mLocClient = null;
	private LocationData locData = null;
	private MyLocationListenner myListener = new MyLocationListenner();
	private LocationOverlay myLocationOverlay = null;
	private boolean isFirstLoc = true;
	private final int ZoomPointerCount = 2;
	private final float ZoomPointerPerimeterChange = 20;
	private Float PointerPerimeter = null;
	private IndoorMapOverlay indoorMapOverlay = null;
	private List<HashMap<String, String>> indoormapList = null;
	private ListView buildingres_lv = null;
	private final int WHAT_LOADEDBUILDINGRES = 0;
	private ProgressDialog pd = null;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		BaiduApplication.createBMapManager(baidumapActivity.this);
		mLocClient = new LocationClient(getApplicationContext());
		locData = new LocationData();
		mLocClient.registerLocationListener(myListener);
		LocationClientOption option = new LocationClientOption();
		option.setOpenGps(true);
		option.setCoorType("bd09ll");
		option.setScanSpan(5000);
		// option.setPoiNumber(5);
		// option.setPoiExtraInfo(true);
		// option.setPoiDistance(1000);
		// option.disableCache(true);
		mLocClient.setLocOption(option);
		// mLocClient.start();
		setContentView(R.layout.baidumap);
		baidumap_mv = (MapView) findViewById(R.id.baidumap_mv);
		buildingres_lv = (ListView) findViewById(R.id.buildingres_lv);
		buildingres_lv.setVisibility(View.VISIBLE);
		buildingres_lv.setOnItemClickListener(new OnItemClickListener() {
			@Override
			public void onItemClick(AdapterView<?> parent, View view,
					int position, long id) {
				HashMap<String, String> map = indoormapList.get(position);
				gotoIndoorMap(map.get("BUILDING_ID"), map.get("BUILDING_NAME"));
			}
		});
		baidumap_mv.setVisibility(View.GONE);
		buildingres_lv.setVisibility(View.VISIBLE);
		Button function_btn = (Button) findViewById(R.id.function_btn);
		function_btn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Button btn = (Button) v;
				if (buildingres_lv.getVisibility() == View.VISIBLE) {
					btn.setBackgroundResource(R.drawable.icon_locationmode);
					baidumap_mv.setVisibility(View.VISIBLE);
					buildingres_lv.setVisibility(View.GONE);
					initBaiduMap();
				} else {
					btn.setBackgroundResource(R.drawable.icon_listmode);
					buildingres_lv.setVisibility(View.VISIBLE);
					baidumap_mv.setVisibility(View.GONE);
				}
			}
		});
		Button teststeps_btn = (Button) findViewById(R.id.teststeps_btn);
		teststeps_btn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				// Intent intent = new Intent(baidumapActivity.this,
				// testGyroscopeActivity.class);
				// baidumapActivity.this.startActivity(intent);
				new getNearbyIndoormapBuildingListThread().start();
			}
		});
		Button teststepsfilter_btn = (Button) findViewById(R.id.teststepsfilter_btn);
		teststepsfilter_btn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				Intent intent = new Intent(baidumapActivity.this,
						testStepCountActivity.class);
				baidumapActivity.this.startActivity(intent);
			}
		});
		pd = new ProgressDialog(baidumapActivity.this);
		pd.setTitle("请稍后");
		pd.setMessage("正在获取建筑信息......");
		pd.setCancelable(false);
		pd.show();
		new getNearbyIndoormapBuildingListThread().start();
	}

	@Override
	protected void onResume() {
		mLocClient.start();
		baidumap_mv.onResume();
		super.onResume();
	}

	@Override
	protected void onPause() {
		if (mLocClient.isStarted()) {
			mLocClient.stop();
		}
		baidumap_mv.onPause();
		super.onPause();
	}

	@Override
	protected void onDestroy() {
		clearOverlay();
		baidumap_mv.destroy();
		BaiduApplication.destroyBMapManager();
		super.onDestroy();
	}

	private Handler handler = new Handler() {
		@Override
		public void handleMessage(Message msg) {
			switch (msg.what) {
			case WHAT_LOADEDBUILDINGRES:
				if (msg.arg2 == 0) {
					indoormapList = (List<HashMap<String, String>>) msg.obj;
					buildingres_lv.setAdapter(new BaseAdapter() {
						@Override
						public int getCount() {
							return ((indoormapList == null) ? 0 : indoormapList
									.size());
						}

						@Override
						public View getView(int position, View convertView,
								ViewGroup parent) {
							View v = LayoutInflater.from(baidumapActivity.this)
									.inflate(R.layout.buildinglistviewitem,
											null);
							TextView buildingname_tv = (TextView) v
									.findViewById(R.id.buildingname_tv);
							TextView distance_tv = (TextView) v
									.findViewById(R.id.distance_tv);
							buildingname_tv.setText(indoormapList.get(position)
									.get("BUILDING_NAME"));
							distance_tv.setText(indoormapList.get(position)
									.get("DISTANCE") + "千米");
							return (v);
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
				} else {
					Toast.makeText(baidumapActivity.this, (String) msg.obj,
							Toast.LENGTH_SHORT).show();
				}
				pd.dismiss();
				break;
			}
		}
	};

	private class getNearbyIndoormapBuildingListThread extends Thread {
		@Override
		public void run() {
			Message msg = handler.obtainMessage();
			msg.what = WHAT_LOADEDBUILDINGRES;
			try {
				msg.obj = getNearbyIndoormapBuildingList();
				msg.arg2 = 0;
			} catch (Exception e) {
				msg.obj = "获取建筑信息出错:" + e.getMessage();
				msg.arg2 = 1;
			}
			handler.sendMessage(msg);
		}
	}

	private List<HashMap<String, String>> getNearbyIndoormapBuildingList()
			throws Exception {
		int actionID = ServiceClientInterface.ID_ACTION_getNearbyIndoormapBuildingList;
		String parmas[] = { "0", "113.353061", " ", "-1" };
		String result = ServiceClientInterface.postRequest(actionID, parmas);
		List<HashMap<String, String>> IndoormapBuildingList = GsonService
				.gsonGetListHashMap(result);
		return (IndoormapBuildingList);
	}

	private void initBaiduMap() {
		mMapController = baidumap_mv.getController();
		mMapController.enableClick(true);// 设置地图是否响应点击事件 .
		mMapController.setZoom(14);
		mMapController.setCenter(new GeoPoint((int) (23.149672 * 1e6),
				(int) (113.353061 * 1e6)));
		baidumap_mv.setBuiltInZoomControls(true);
		clearOverlay();
		initOverlay();
		baidumap_mv.setOnTouchListener(new View.OnTouchListener() {
			@Override
			public boolean onTouch(View v, MotionEvent event) {
				int action = event.getAction() & 0x000000ff;
				int PointerCount = event.getPointerCount();
				switch (action) {
				case MotionEvent.ACTION_DOWN:
					if (baidumap_mv.getZoomLevel() == baidumap_mv
							.getMaxZoomLevel()) {
						PointerPerimeter = -1f;
					} else {
						PointerPerimeter = null;
					}
					break;
				case MotionEvent.ACTION_UP:
					PointerPerimeter = null;
					break;
				case MotionEvent.ACTION_MOVE:
					if (PointerPerimeter != null) {
						if (PointerCount >= ZoomPointerCount) {
							if (PointerPerimeter == -1f) {
								PointerPerimeter = getEventPointerPerimeter(event);
							}
							float nowPointerPerimeter = getEventPointerPerimeter(event);
							if ((nowPointerPerimeter - PointerPerimeter) > ZoomPointerPerimeterChange
									* PointerCount) {
								if (baidumap_mv.getZoomLevel() == baidumap_mv
										.getMaxZoomLevel()) {
									handleMapViewMaxZoom(baidumap_mv);
									PointerPerimeter = null;
								} else {
									PointerPerimeter = nowPointerPerimeter;
								}
							}
						}
					}
					break;
				case MotionEvent.ACTION_POINTER_DOWN:
					if (PointerPerimeter != null) {
						PointerPerimeter = getEventPointerPerimeter(event);
					}
					break;
				case MotionEvent.ACTION_POINTER_UP:
					if (PointerPerimeter != null) {
						if (PointerCount <= ZoomPointerCount) {
							PointerPerimeter = -1f;
						} else {
							PointerPerimeter = getEventPointerPerimeter(event);
						}
					}
					break;
				}
				return (false);
			}
		});
		myLocationOverlay = new LocationOverlay(baidumap_mv);
		myLocationOverlay.setData(locData);
		List<Overlay> overlaylist = baidumap_mv.getOverlays();
		if (overlaylist != null) {
			overlaylist.add(myLocationOverlay);
		}
		myLocationOverlay.enableCompass();
		baidumap_mv.refresh();
	}

	private void initOverlay() {
		if (indoormapList != null) {
			if (indoorMapOverlay != null) {
				indoorMapOverlay.removeAll();
			}
			indoorMapOverlay = new IndoorMapOverlay(getResources().getDrawable(
					R.drawable.icon_indoormap), baidumap_mv);
			HashMap<String, String> carstatemap;
			int count = indoormapList.size();
			int i = 0;
			while (i < count) {
				carstatemap = indoormapList.get(i);
				try {
					int log = (int) (Double.parseDouble(carstatemap
							.get("Longitude")) * 1E6);
					int lan = (int) (Double.parseDouble(carstatemap
							.get("Latitude")) * 1E6);
					GeoPoint gp = new GeoPoint(lan, log);
					OverlayItem oi = new OverlayItem(gp,
							carstatemap.get("carType"),
							carstatemap.get("carType"));
					oi.setMarker(getResources().getDrawable(
							R.drawable.icon_indoormap));
					indoorMapOverlay.addItem(oi);
				} catch (Exception e) {
				}
				++i;
			}
			List<Overlay> overlaylist = baidumap_mv.getOverlays();
			if (overlaylist != null) {
				overlaylist.add(indoorMapOverlay);
			}
		}
	}

	private void clearOverlay() {
		if (indoorMapOverlay != null) {
			indoorMapOverlay.removeAll();
		}
		// baidumap_mv.refresh();
	}

	private void gotoIndoorMap(String BUILDING_ID,String BUILDING_NAME) {
		Intent intent = new Intent(baidumapActivity.this,
				indoormapActivity.class);
		intent.putExtra("BUILDING_ID", BUILDING_ID);
		intent.putExtra("BUILDING_NAME", BUILDING_NAME);
		startActivity(intent);
	}

	private void handleMapViewMaxZoom(MapView mv) {
		GeoPoint centerPoint = baidumap_mv.getMapCenter();
		int tbSpan = baidumap_mv.getLatitudeSpan();
		int lrSpan = baidumap_mv.getLongitudeSpan();
		GeoPoint ltPoint = new GeoPoint(centerPoint.getLatitudeE6() - tbSpan
				/ 2, centerPoint.getLongitudeE6() - lrSpan / 2);
		GeoPoint rbPoint = new GeoPoint(centerPoint.getLatitudeE6() + tbSpan
				/ 2, centerPoint.getLongitudeE6() + lrSpan / 2);
	}

	private float getEventPointerPerimeter(MotionEvent ev) {
		int PointerCount = ev.getPointerCount();
		float[][] PointerCoord = new float[PointerCount][2];
		int i = 0;
		while (i < PointerCount) {
			PointerCoord[i][0] = ev.getX(i);
			PointerCoord[i][1] = ev.getY(i);
			++i;
		}
		i = 0;
		int j;
		float[] tmp;
		while (i < PointerCount) {
			j = i + 1;
			while (j < PointerCount) {
				if (PointerCoord[j][0] < PointerCoord[i][0]) {
					tmp = PointerCoord[i];
					PointerCoord[i] = PointerCoord[j];
					PointerCoord[j] = tmp;
				} else {
					if (PointerCoord[j][0] == PointerCoord[i][0]) {
						if (PointerCoord[j][1] > PointerCoord[i][1]) {
							tmp = PointerCoord[i];
							PointerCoord[i] = PointerCoord[j];
							PointerCoord[j] = tmp;
						}
					}
				}
				++j;
			}
			++i;
		}
		float x;
		float y;
		float sum = 0;
		i = 0;
		while (i < PointerCount) {
			x = (PointerCoord[i][0] - PointerCoord[(i + 1) % PointerCount][0]);
			y = (PointerCoord[i][1] - PointerCoord[(i + 1) % PointerCount][1]);
			sum = (sum + (float) Math.sqrt(x * x + y * y));
			++i;
		}
		return (sum);
	}

	private class IndoorMapOverlay extends ItemizedOverlay<OverlayItem> {

		public IndoorMapOverlay(Drawable defaultMarker, MapView mapView) {
			super(defaultMarker, mapView);
		}

		@Override
		public boolean onTap(int index) {
			HashMap<String, String> map = indoormapList.get(index);
			gotoIndoorMap(map.get("BUILDING_ID"), map.get("BUILDING_NAME"));
			return (true);
		}

		@Override
		public boolean onTap(GeoPoint pt, MapView mv) {
			return (false);
		}
	}

	private class LocationOverlay extends MyLocationOverlay {

		public LocationOverlay(MapView mapView) {
			super(mapView);
		}

		@Override
		protected boolean dispatchTap() {
			HashMap<String, String> map = indoormapList.get(0);
			gotoIndoorMap(map.get("BUILDING_ID"), map.get("BUILDING_NAME"));
			return (true);
		}
	}

	private class MyLocationListenner implements BDLocationListener {

		@Override
		public void onReceiveLocation(BDLocation location) {
			if (location != null) {
				locData.latitude = location.getLatitude();
				locData.longitude = location.getLongitude();
				locData.accuracy = location.getRadius();
				locData.direction = location.getDerect();
				if (myLocationOverlay != null) {
					myLocationOverlay.setData(locData);
					baidumap_mv.refresh();
				}
				if (isFirstLoc) {
					if (mMapController != null) {
						mMapController.animateTo(new GeoPoint(
								(int) (locData.latitude * 1e6),
								(int) (locData.longitude * 1e6)));
					}
				}
				isFirstLoc = false;
			}
		}

		@Override
		public void onReceivePoi(BDLocation arg0) {
		}
	}
}