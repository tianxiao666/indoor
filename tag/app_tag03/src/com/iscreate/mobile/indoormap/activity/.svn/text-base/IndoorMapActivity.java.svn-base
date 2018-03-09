package com.iscreate.mobile.indoormap.activity;

import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Timer;
import java.util.TimerTask;

import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.PictureDrawable;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.os.Message;
import android.view.Gravity;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.baidu.mapapi.map.LocationData;
import com.iscreate.mobile.baidu.BDMapApp;
import com.iscreate.mobile.indoormap.R;
import com.iscreate.mobile.indoormap.widget.PlaneGraphFrameLayout;
import com.iscreate.mobile.indoormap.widget.requestDataThread;
import com.iscreate.mobile.service.GsonService;
import com.iscreate.mobile.service.ServiceClientInterface;
import com.iscreate.mobile.svg.SVGParser;
import com.iscreate.mobile.utils.utils;
import com.iscreate.mobile.widget.LeftSliderLayout;
import com.iscreate.mobile.widget.LeftSliderLayout.OnLeftSliderLayoutStateListener;
import com.iscreate.mobile.widget.PopupNormalWindow;

public class IndoorMapActivity extends wifiDetecterActivity implements
		OnClickListener {
	/**
	 * 当前Activity显示的内容
	 * 
	 * @see #setContentView(View)
	 */
	private View contentView = null;
	/**
	 * 自定义控件，用于显示平面图
	 */
	private PlaneGraphFrameLayout fl_PlaneGraph = null;
	/**
	 * 用于UI线程中接收请求数据的消息
	 */
	private final int WHAT_REQUESTDATA = 0;
	/**
	 * 用于UI线程中接收更新步数的消息
	 */
	private final int WHAT_UPDATEACC = 2;
	/**
	 * 用于UI线程中接收SVG源代码转找成平面图的消息
	 */
	private final int WHAT_SVGSRCTODRAWABLE = 3;
	/**
	 * 耗时操作，显示进度
	 */
	private ProgressDialog pd = null;
	/**
	 * 显示当前位置
	 */
	private TextView locatione_tv = null;
	/**
	 * 显示楼层列表按钮
	 */
	private Button floors_btn = null;
	/**
	 * 楼层列表数据
	 */
	private List<HashMap<String, String>> IndoormapBuildingFloorList = null;
	/**
	 * 定时器，周期性的更新步数
	 */
	private Timer timer = null;
	/**
	 * 上次定位时间
	 */
	private Long lastsubmittime = null;
	/**
	 * 场所ID
	 */
	private String BUILDING_ID = null;
	/**
	 * 场所名
	 */
	private String BUILDING_NAME = null;
	/**
	 * 左上右下经纬度
	 */
	private double RB_LATITUDEL = 0;
	private double LT_LATITUDEL = 0;
	private double RB_LONGITUDEL = 0;
	private double LT_LONGITUDEL = 0;
	/**
	 * 楼层ID
	 */
	private String FLOOR_ID = null;
	/**
	 * 平面图ID
	 */
	private String DRAW_MAP_ID = null;
	/**
	 * 平面图源代码
	 */
	private String svgsrc = null;
	/**
	 * 当前位置的x坐标
	 */
	private Float location_x = null;
	/**
	 * 当前位置的y坐标
	 */
	private Float location_y = null;
	/**
	 * 当前位置
	 */
	private String LOCATION = null;
	/**
	 * 标题
	 */
	private TextView title_tv = null;
	/**
	 * 显示楼层列表
	 */
	private PopupNormalWindow popupWin_Floor = null;
	/**
	 * 显示图层列表
	 */
	private PopupNormalWindow popupWin_Layer = null;
	/**
	 * 图层列表数据
	 */
	private HashMap<String, String> LayerTypeMap = null;
	/**
	 * 是否可回到mainActivity
	 * 
	 * @see mainActivity
	 */
	private boolean GOBACKBD = false;
	/**
	 * 侧滑控件
	 */
	private LeftSliderLayout leftSliderLayout = null;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		contentView = LayoutInflater.from(IndoorMapActivity.this).inflate(
				R.layout.indoor, null);
		setContentView(contentView);
		initComponent();
		initComponentControl();
		onHandleIntent(getIntent());
	}

	/**
	 * 初始化控件
	 */
	private void initComponent() {
		logFunction("initComponent");
		leftSliderLayout = (LeftSliderLayout) findViewById(R.id.main_slider_layout);
		findViewById(R.id.search_btn).setOnClickListener(this);
		findViewById(R.id.location_btn).setOnClickListener(this);
		findViewById(R.id.more_btn).setOnClickListener(this);
		findViewById(R.id.about_btn).setOnClickListener(this);
		findViewById(R.id.LayerSelection_btn).setOnClickListener(this);
		findViewById(R.id.WifiSignalTestMenu_btn).setOnClickListener(this);
		findViewById(R.id.StepTestMenu_btn).setOnClickListener(this);
		findViewById(R.id.btn_setting).setOnClickListener(this);
		findViewById(R.id.exit_btn).setOnClickListener(this);
		title_tv = (TextView) findViewById(R.id.title);
		fl_PlaneGraph = (PlaneGraphFrameLayout) findViewById(R.id.fl_PlaneGraph);
		floors_btn = (Button) findViewById(R.id.floors_btn);
		locatione_tv = (TextView) findViewById(R.id.locatione_tv);
		pd = new ProgressDialog(IndoorMapActivity.this);
	}

	/**
	 * 初始化组件控制
	 */
	private void initComponentControl() {
		logFunction("initComponentControl");
		leftSliderLayout.enableSlide(true);
		leftSliderLayout
				.setOnLeftSliderLayoutListener(new OnLeftSliderLayoutStateListener() {
					@Override
					public void OnLeftSliderLayoutStateChanged(boolean bIsOpen) {

					}

					@Override
					public boolean OnLeftSliderLayoutInterceptTouch(
							MotionEvent ev) {
						return (false);
					}
				});
		floors_btn.setOnClickListener(this);
		pd.setTitle("请稍后");
		pd.setCancelable(false);
	}

	/**
	 * 处理Intent
	 * 
	 * @param intent
	 */
	private void onHandleIntent(Intent intent) {
		logFunction("onHandleIntent");
		BUILDING_ID = intent.getStringExtra("BUILDING_ID");
		BUILDING_NAME = intent.getStringExtra("BUILDING_NAME");
		FLOOR_ID = intent.getStringExtra("FLOOR_ID");
		DRAW_MAP_ID = intent.getStringExtra("DRAW_MAP_ID");
		svgsrc = intent.getStringExtra("SVGSRC");
		/**
		 * 已有定位
		 */
		if (svgsrc != null) {
			try {
				location_x = Float.parseFloat(intent.getStringExtra("X"));
				location_y = Float.parseFloat(intent.getStringExtra("Y"));
			} catch (Exception e) {
				logError("定位坐标解析错误！" + e.getMessage());
				location_x = null;
				location_y = null;
			}
			/**
			 * 没有定位
			 */
		} else {
			try {
				RB_LATITUDEL = Double.parseDouble(intent
						.getStringExtra("RB_LATITUDEL"));
				LT_LATITUDEL = Double.parseDouble(intent
						.getStringExtra("LT_LATITUDEL"));
				RB_LONGITUDEL = Double.parseDouble(intent
						.getStringExtra("RB_LONGITUDEL"));
				LT_LONGITUDEL = Double.parseDouble(intent
						.getStringExtra("LT_LONGITUDEL"));
			} catch (Exception e) {
				logError("左上右下经纬度获取失败！" + e.getMessage());
			}
		}
		GOBACKBD = intent.getBooleanExtra("GOBACKBD", false);
		LOCATION = intent.getStringExtra("LOCATION");
		title_tv.setText(BUILDING_NAME);
		if (BUILDING_ID != null) {
			floors_btn.setClickable(false);
			pd.setMessage("正在获取信息");
			pd.show();
			LayerTypeMap = null;
			popupWin_Layer = null;
			startThreadToGetBuildingFloorList();
		}
	}

	@Override
	protected void onNewIntent(Intent intent) {
		fl_PlaneGraph.setImageDrawable(null);
		onHandleIntent(intent);
		super.onNewIntent(intent);
	}

	private IndoorMapActivity getThis() {
		return (IndoorMapActivity.this);
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

	/**
	 * 新建一个Handler接受Message
	 */
	private Handler handler = new Handler(Looper.getMainLooper()) {
		@Override
		public void handleMessage(Message msg) {
			// logFunction("handler.handleMessage");
			String ErrorMessage = null;
			switch (msg.what) {
			case WHAT_REQUESTDATA:
				if (msg.arg2 == 1) {
					try {
						handleContent(msg.arg1, (String) msg.obj);
					} catch (Exception e) {
						ErrorMessage = e.getMessage();
					}
				} else {
					ErrorMessage = (String) msg.obj;
				}
				if (ErrorMessage != null) {
					handleError(msg.arg1);
				}
				break;
			case WHAT_UPDATEACC:
				/**
				 * 更新加速力计算步数
				 */
				updateStepDetecter();
				break;
			case WHAT_SVGSRCTODRAWABLE:
				if (msg.arg2 == 1) {
					/**
					 * SVG图片转换成功
					 */
					PictureDrawable svgbm = (PictureDrawable) msg.obj;
					fl_PlaneGraph.setImageDrawable(svgbm);
					fl_PlaneGraph.setLocation(location_x, location_y);
				} else {
					fl_PlaneGraph.setImageDrawable(null);
					ErrorMessage = (String) msg.obj;
				}
				pd.dismiss();
				break;
			}
			if (ErrorMessage != null) {
				logError(ErrorMessage);
				Toast.makeText(IndoorMapActivity.this, ErrorMessage,
						Toast.LENGTH_SHORT).show();
			}
		}
	};

	/**
	 * 处理服务器上传下来的content
	 * 
	 * @param actionID
	 *            the interface action id in class ServiceClientInterface
	 * @param content
	 *            the content from the JSON response
	 * @return true if succeed
	 * @throws Exception
	 */
	private void handleContent(int actionID, String content) throws Exception {
		logFunction("handleContent:"
				+ ServiceClientInterface.getServiceClientAction(actionID));
		switch (actionID) {
		case ServiceClientInterface.ID_ACTION_GetBuildingFloorList: {
			floors_btn.setClickable(true);
			HashMap<String, String> contentMap = GsonService
					.gsonGetHashMap(content);
			String BuildingFloorListStr = contentMap.get("BuildingFloorList");
			IndoormapBuildingFloorList = GsonService
					.gsonGetListHashMap(BuildingFloorListStr);
			if (((IndoormapBuildingFloorList == null) || (IndoormapBuildingFloorList
					.size() == 0))) {
				throw (new Exception("没有楼层！"));
			} else {
				initPopupWinFloor();
				if (BUILDING_ID != null) {
					if (FLOOR_ID != null && FLOOR_ID != "" && svgsrc != null
							&& DRAW_MAP_ID != null) {
						locatione_tv.setText(getLocationText());
						pd.setMessage("正在生成图片.....");
						new SvgToDrawableThread().start();
					} else {
						FLOOR_ID = null;
						DRAW_MAP_ID = null;
						svgsrc = null;
						location_x = null;
						location_y = null;
						LOCATION = null;
						LayerTypeMap = null;
						popupWin_Layer = null;
						LocationData locData = BDMapApp.getInstance()
								.getBDLocationData();
						if (locData.latitude >= RB_LATITUDEL
								&& locData.latitude <= LT_LATITUDEL
								&& locData.longitude <= RB_LONGITUDEL
								&& locData.longitude >= LT_LONGITUDEL) {
							if (startWifiScan()) {
								pd.setMessage("正在扫描......");
								pd.show();
							} else {
								logError("启动wifi扫描失败!");
								Toast.makeText(IndoorMapActivity.this,
										"启动wifi扫描失败!", Toast.LENGTH_SHORT)
										.show();
								fl_PlaneGraph.clrLocation();
								location_x = null;
								location_y = null;
								locatione_tv.setText(getLocationText());
								startThreadToGetBuildingFloorMap();
							}
						} else {
							pd.setMessage("正在获取平面图信息......");
							fl_PlaneGraph.clrLocation();
							location_x = null;
							location_y = null;
							locatione_tv.setText(getLocationText());
							startThreadToGetBuildingFloorMap();
						}
					}
				} else {
					if (startWifiScan()) {
						pd.setMessage("正在扫描......");
					} else {
						Toast.makeText(IndoorMapActivity.this, "启动wifi扫描失败!",
								Toast.LENGTH_SHORT).show();
						pd.dismiss();
					}
				}
			}
		}
			break;
		case ServiceClientInterface.ID_ACTION_GetLocation: {
			HashMap<String, String> contentMap = GsonService
					.gsonGetHashMap(content);
			svgsrc = contentMap.get("SVGSRC");
			if (svgsrc == "") {
				svgsrc = null;
			}
			FLOOR_ID = contentMap.get("FLOOR_ID");
			DRAW_MAP_ID = contentMap.get("DRAW_MAP_ID");
			try {
				location_x = Float.parseFloat(contentMap.get("X"));
				location_y = Float.parseFloat(contentMap.get("Y"));
			} catch (Exception e) {
				logError("定位坐标解析错误！" + e.getMessage());
				location_x = null;
				location_y = null;
			}
			if ((location_x == null) || (location_y == null)) {
				Toast.makeText(IndoorMapActivity.this, "定位失败！",
						Toast.LENGTH_SHORT).show();
				logError("定位失败！");
			}
			LOCATION = contentMap.get("LOCATION");
			locatione_tv.setText(getLocationText());
			if (svgsrc != null) {
				String LayerListStr = contentMap.get("LayerList");
				if (LayerListStr != null) {
					try {
						LayerTypeMap = GsonService.gsonGetHashMap(LayerListStr);
					} catch (Exception e) {
						logError("图层列表为空！");
					}
				}
				pd.setMessage("正在生成图片.....");
				new SvgToDrawableThread().start();
			} else {
				fl_PlaneGraph.setLocation(location_x, location_y);
				pd.dismiss();
			}
		}
			break;
		case ServiceClientInterface.ID_ACTION_GetBuildingFloorMap: {
			HashMap<String, String> contentMap = GsonService
					.gsonGetHashMap(content);
			String error = null;
			svgsrc = contentMap.get("SVGSRC");
			FLOOR_ID = contentMap.get("FLOOR_ID");
			DRAW_MAP_ID = contentMap.get("DRAW_MAP_ID");
			locatione_tv.setText(getLocationText());
			if (svgsrc == null) {
				logError("SVG源码为空！");
				error = "SVG源码为空！";
			} else {
				String LayerListStr = contentMap.get("LayerList");
				if (LayerListStr != null) {
					try {
						LayerTypeMap = GsonService.gsonGetHashMap(LayerListStr);
					} catch (Exception e) {
						logError("图层列表为空！");
					}
				}
				pd.setMessage("正在生成图片.....");
				// pd.show();
				new SvgToDrawableThread().start();
			}
			if (error != null) {
				fl_PlaneGraph.setImageDrawable(null);
				pd.dismiss();
				Toast.makeText(IndoorMapActivity.this, error,
						Toast.LENGTH_SHORT).show();
			}
		}
			break;
		}
	}

	/**
	 * 服务器取content出错或处理数据出错时的处理
	 * 
	 * @param actionID
	 *            the interface action id in class ServiceClientInterface
	 */
	private void handleError(int actionID) {
		logFunction("handleError");
		switch (actionID) {
		case ServiceClientInterface.ID_ACTION_GetBuildingFloorList:
			floors_btn.setClickable(true);
			// BUILDING_ID =null;
			// BUILDING_NAME =null;
			FLOOR_ID = null;
			DRAW_MAP_ID = null;
			svgsrc = null;
			location_x = null;
			location_y = null;
			LOCATION = null;
			pd.dismiss();
			break;
		case ServiceClientInterface.ID_ACTION_GetLocation:
			locatione_tv.setText("定位错误");
			fl_PlaneGraph.clrLocation();
			location_x = null;
			location_y = null;
			pd.dismiss();
			break;
		case ServiceClientInterface.ID_ACTION_GetBuildingFloorMap:
			fl_PlaneGraph.setImageDrawable(null);
			pd.dismiss();
			break;
		}
	}

	/**
	 * start a thread to request data from server
	 */
	private void startThreadToRequestData(int actionID) {
		logFunction("startThreadToRequestData");
		new requestDataThread(handler,//
				WHAT_REQUESTDATA,//
				actionID, //
				getParamsForRequestData(actionID)//
		).start();
	}

	/**
	 * start a thread to get the current location
	 */
	private void startThreadToGetLocation() {
		startThreadToRequestData(ServiceClientInterface.ID_ACTION_GetLocation);
	}

	/**
	 * start a thread to get the Floors
	 */
	private void startThreadToGetBuildingFloorList() {
		startThreadToRequestData(ServiceClientInterface.ID_ACTION_GetBuildingFloorList);
	}

	/**
	 * start a thread to get the Floor map
	 */
	private void startThreadToGetBuildingFloorMap() {
		startThreadToRequestData(ServiceClientInterface.ID_ACTION_GetBuildingFloorMap);
	}

	/**
	 * get the parameters for each action
	 * 
	 * @param actionID
	 *            the interface action id
	 */
	private String[] getParamsForRequestData(int actionID) {
		try {
			logFunction("getParamsForRequestData:"
					+ ServiceClientInterface.getServiceClientAction(actionID));
		} catch (Exception e) {
			logError("getParamsForRequestData:" + e.getMessage());
		}
		switch (actionID) {
		case ServiceClientInterface.ID_ACTION_GetBuildingFloorList: {
			String params[] = { BUILDING_ID };
			return (params);
		}
		case ServiceClientInterface.ID_ACTION_GetLocation: {
			long nowtime = System.currentTimeMillis();
			long timegap = 0;
			long stepsintimegap = 0;
			if (lastsubmittime != null) {
				timegap = nowtime - lastsubmittime;
				stepsintimegap = getSteps();
			}
			String WifiListJsonStr = testWifiActivity
					.getWifiListJsonStr(scanresultlist);
			if (WifiListJsonStr == null) {
				WifiListJsonStr = "";
				logError("WifiListJsonStr为空！");
			}
			LocationData locData = BDMapApp.getInstance().getBDLocationData();
			String[] params = {//
			"" + locData.longitude,//
					"" + locData.latitude,//
					WifiListJsonStr,//
					"" + stepsintimegap,//
					"" + timegap,//
					"" + getDirection(), //
					BUILDING_ID,//
					FLOOR_ID, //
					DRAW_MAP_ID //
			};
			if (lastsubmittime == null) {
				initTimer();
				lastsubmittime = System.currentTimeMillis();
			} else {
				resetStep();
				lastsubmittime = nowtime;
			}
			return (params);
		}
		case ServiceClientInterface.ID_ACTION_GetBuildingFloorMap: {
			if (FLOOR_ID == null) {
				FLOOR_ID = "";
			}
			if (BUILDING_ID == null) {
				BUILDING_ID = "";
				logError("BUILDING_ID为空！");
			}
			String params[] = { FLOOR_ID, BUILDING_ID };
			return (params);
		}
		}
		return (null);
	}

	/**
	 * 初始化楼层下拉菜单
	 */
	private void initPopupWinFloor() {
		logFunction("initPopupWinFloor");
		ListView popupView = (ListView) LayoutInflater.from(
				IndoorMapActivity.this).inflate(R.layout.floorsmenu, null);
		popupView.setAdapter(new BaseAdapter() {
			@Override
			public int getCount() {
				return ((IndoormapBuildingFloorList == null) ? 0
						: IndoormapBuildingFloorList.size());
			}

			@Override
			public View getView(int position, View convertView, ViewGroup parent) {
				HashMap<String, String> map = IndoormapBuildingFloorList
						.get(position);
				TextView tv = new TextView(IndoorMapActivity.this);
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
		popupWin_Floor = new PopupNormalWindow(popupView, 350,
				LinearLayout.LayoutParams.WRAP_CONTENT);
		popupView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
			@Override
			public void onItemClick(AdapterView<?> groupview, View view,
					int position, long arg3) {
				String floorId = (String) view.getTag();
				if ((floorId != null) && !floorId.equals(FLOOR_ID)) {
					FLOOR_ID = floorId;
					getThis().pd.setMessage("正在获取平面图信息......");
					getThis().pd.show();
					LayerTypeMap = null;
					popupWin_Layer = null;
					fl_PlaneGraph.clrLocation();
					location_x = null;
					location_y = null;
					locatione_tv.setText(getLocationText());
					startThreadToGetBuildingFloorMap();
				}
				popupWin_Floor.dismiss();
			}
		});
		popupWin_Floor.setAnimationStyle(R.style.popupttb);
	}

	/**
	 * convert svg source to PictureDrawable
	 * 
	 * @param svgsrc
	 *            the svg source string to convert
	 * @return PictureDrawable
	 * @throws Exception
	 */
	private PictureDrawable SvgToDrawable(String svgsrc) throws Exception {
		logFunction("SvgToDrawable");
		if (svgsrc != null) {
			return (SVGParser.getPictureDrawableFromString(svgsrc));
		} else {
			throw (new Exception("SVG源代码为空！"));
		}
	}

	/**
	 * 开启一个线程将svg源代码生成Drawable
	 */
	private class SvgToDrawableThread extends Thread {
		@Override
		public void run() {
			Message msg = handler.obtainMessage();
			msg.what = WHAT_SVGSRCTODRAWABLE;
			try {
				msg.obj = SvgToDrawable(svgsrc);
				msg.arg2 = 1;
			} catch (Exception e) {
				logError("SVG代码转换Drawable错误:" + e.getMessage() + "\nsvgsrc:\n"
						+ svgsrc);
				msg.obj = "SVG代码转换Drawable错误:" + e.getMessage();
				msg.arg2 = 0;
			}
			handler.sendMessage(msg);
		}
	}

	/**
	 * 进入查找
	 */
	private void gotoSearch() {
		logFunction("gotoSearch");
		Intent intent = new Intent(IndoorMapActivity.this, SearchActivity.class);
		startActivityForResult(intent, 1);
	}

	/**
	 * 在wifi扫描完后定位
	 */
	@Override
	public void OnHandleScanResult() {
		logFunction("OnHandleScanResult");
		pd.setMessage("正在提交......");
		if (DRAW_MAP_ID == null) {
			fl_PlaneGraph.clrLocation();
			location_x = null;
			location_y = null;
		}
		startThreadToGetLocation();
	}

	@Override
	public boolean onKeyUp(int keyCode, KeyEvent event) {
		if (keyCode == KeyEvent.KEYCODE_BACK) {
			/**
			 * 如果左侧菜单没有关闭，先关闭
			 */
			if (leftSliderLayout.isOpen()) {
				leftSliderLayout.close();
			} else {
				if (GOBACKBD) {
					Intent intent = new Intent(this, mainActivity.class);
					startActivity(intent);
				} else {
					return (super.onKeyUp(keyCode, event));
				}
			}
			return (true);
		}
		return (super.onKeyUp(keyCode, event));
	}

	/**
	 * 步数测试
	 */
	private void gotoStepTest() {
		logFunction("gotoStepTest");
		Intent intent = new Intent(this, testStepCountActivity.class);
		startActivity(intent);
	}

	/**
	 * wifi测试
	 */
	private void gotoWifiTest() {
		logFunction("gotoWifiTest");
		Intent intent = new Intent(this, testWifiActivity.class);
		startActivity(intent);
	}

	private HashMap<String, Integer> getLayerResIdMap() {
		logFunction("getLayerResIdMap");
		HashMap<String, Integer> layerResIdMap = new HashMap<String, Integer>();
		layerResIdMap.put("OUT_W", R.id.cb_layer_OUT_W);
		layerResIdMap.put("TOILE", R.id.cb_layer_TOILE);
		layerResIdMap.put("ELEVA", R.id.cb_layer_ELEVA);
		layerResIdMap.put("STAIR", R.id.cb_layer_STAIR);
		layerResIdMap.put("ROUTE", R.id.cb_layer_ROUTE);
		layerResIdMap.put("BUSSI", R.id.cb_layer_BUSSI);
		layerResIdMap.put("AP", R.id.cb_layer_AP);
		layerResIdMap.put("POI", R.id.cb_layer_POI);
		return (layerResIdMap);
	}

	/**
	 * 保存图层信息，哪个有选中，哪个没有选中
	 */
	private void saveLayers() {
		logFunction("saveLayers");
		HashMap<String, Integer> layerResIdMap = getLayerResIdMap();
		CheckBox cb = null;
		String layerType = null;
		View v = popupWin_Layer.getContentView();
		if (LayerTypeMap != null) {
			Iterator<String> iterator = LayerTypeMap.keySet().iterator();
			while (iterator.hasNext()) {
				layerType = iterator.next();
				cb = (CheckBox) v.findViewById(layerResIdMap.get(layerType));
				LayerTypeMap.put(layerType, cb.isChecked() ? "1" : "0");
			}
		} else {
			logError("LayerTypeMap为空！");
		}
	}

	/**
	 * 显示有的图层，没有的不显示，哪个应该选中，哪个不应该选中
	 */
	private void showLayers() {
		logFunction("showLayers");
		HashMap<String, Integer> layerResIdMap = getLayerResIdMap();
		String layerType = null;
		CheckBox cb = null;
		String checked = null;
		View v = popupWin_Layer.getContentView();
		if (LayerTypeMap != null) {
			Iterator<String> iterator = layerResIdMap.keySet().iterator();
			while (iterator.hasNext()) {
				layerType = iterator.next();
				cb = (CheckBox) v.findViewById(layerResIdMap.get(layerType));
				checked = LayerTypeMap.get(layerType);
				if (checked != null) {
					cb.setVisibility(View.VISIBLE);
					cb.setChecked(checked.equals("1"));
				} else {
					cb.setVisibility(View.GONE);
				}
			}
		}
	}

	/**
	 * 选择图层
	 */
	private void popupLayers() {
		logFunction("popupLayers");
		if (LayerTypeMap != null) {
			if (popupWin_Layer == null) {
				View popupContentView = LayoutInflater.from(this).inflate(
						R.layout.popuplayers, null);
				popupWin_Layer = new PopupNormalWindow(popupContentView,
						LinearLayout.LayoutParams.MATCH_PARENT,
						LinearLayout.LayoutParams.MATCH_PARENT);
				popupContentView.findViewById(R.id.bt_confirm)
						.setOnClickListener(new View.OnClickListener() {
							@Override
							public void onClick(View v) {
								saveLayers();
								if (popupWin_Layer != null) {
									popupWin_Layer.dismiss();
								} else {
									logError("popupWin_Layer为空！");
								}
							}
						});
				popupContentView.findViewById(R.id.bt_cancel)
						.setOnClickListener(new View.OnClickListener() {
							@Override
							public void onClick(View v) {
								if (popupWin_Layer != null) {
									popupWin_Layer.dismiss();
								} else {
									logError("popupWin_Layer为空！");
								}
							}
						});
			}
			try {
				showLayers();
			} catch (Exception e) {
				logFunction("popupLayers." + e.getMessage());
			}
			popupWin_Layer.showAtLocation(contentView, Gravity.CENTER, 0, 0);
		} else {
			Toast.makeText(this, "没有图层！", Toast.LENGTH_SHORT).show();
		}
	}

	private String getLocationText() {
		String location = getFloorName(FLOOR_ID);
		if ((location != null) && (location_x != null) && (location_y != null)) {
			location = location + "(" + location_x + "," + location_y + ")";
		}
		return (location);
	}

	/**
	 * 获取楼层名称
	 */
	private String getFloorName(String floorId) {
		if ((IndoormapBuildingFloorList != null) && (floorId != null)
				&& (floorId.length() > 0)) {
			HashMap<String, String> floorInfo = null;
			int count = IndoormapBuildingFloorList.size();
			int i = 0;
			while (i < count) {
				floorInfo = IndoormapBuildingFloorList.get(i);
				if (floorInfo != null) {
					if (floorId.equals(floorInfo.get("FLOOR_ID"))) {
						return (floorInfo.get("FLOOR_NAME"));
					}
				}
				++i;
			}
		}
		return (null);
	}

	/**
	 * 点击事件
	 */
	@Override
	public void onClick(View v) {
		logFunction("onClick");
		int id = v.getId();
		switch (id) {
		case R.id.LayerSelection_btn:
			popupLayers();
			break;
		case R.id.search_btn: {
			gotoSearch();
		}
			break;
		case R.id.more_btn: {
			if (leftSliderLayout.isOpen()) {
				leftSliderLayout.close();
			} else {
				leftSliderLayout.open();
			}
		}
			break;
		case R.id.about_btn: {
			Toast.makeText(this, "关于", Toast.LENGTH_SHORT).show();
		}
			break;
		case R.id.WifiSignalTestMenu_btn: {
			gotoWifiTest();
		}
			break;
		case R.id.StepTestMenu_btn: {
			gotoStepTest();
		}
		case R.id.btn_setting: {
			Toast.makeText(this, "设置", Toast.LENGTH_SHORT).show();
		}
			break;
		case R.id.exit_btn: {
			utils.exitApp(this);
		}
			break;
		case R.id.location_btn: {
			if (startWifiScan()) {
				pd.setMessage("正在扫描......");
				pd.show();
			} else {
				logError("启动wifi扫描失败!");
				Toast.makeText(IndoorMapActivity.this, "启动wifi扫描失败!",
						Toast.LENGTH_SHORT).show();
			}
		}
			break;
		case R.id.floors_btn: {
			if (popupWin_Floor != null) {
				if (popupWin_Floor.isShowing()) {
					popupWin_Floor.dismiss();
				} else {
					popupWin_Floor.showAsDropDown(floors_btn, 0, 0);
				}
			} else {
				logError("popupWin_Floor为空！");
			}
		}
		}
	}
}