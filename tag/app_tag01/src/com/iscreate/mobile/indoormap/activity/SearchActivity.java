package com.iscreate.mobile.indoormap.activity;

import java.util.HashMap;
import java.util.List;

import android.app.Activity;
import android.graphics.Color;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.BaseAdapter;
import android.widget.EditText;
import android.widget.GridView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.iscreate.mobile.indoormap.R;
import com.iscreate.mobile.indoormap.widget.PopupNormalWindow;
import com.iscreate.mobile.service.GsonService;
import com.iscreate.mobile.service.ServiceClientInterface;

public class SearchActivity extends Activity {

	public static final String searchKey = "searchKey";
	private final String[] SearchKeyList = { "入口", "出口", "无障碍出入口", "紧急出口",
			"扶梯", "电梯", "步行梯", "货梯", "洗手间", "女洗手间", "男洗手间", "无障碍洗手间", "母婴室",
			"餐饮", "购物", "商户", "娱乐", "电话", "VIP室", "服务台", "停车场", "客服中心", "收银台",
			"询问", "休息处", "行李提取", "行李寄存", "票务服务", "医疗急救", "自动检票机", "售票处",
			"自动提款机", "登机口", "值机岛", "超规行李托运", "邮局", "边检", "安检处", "行李查询", "吸烟处" };
	private List<HashMap<String, String>> IndoormapBuildingFloorSearchKeyList = null;
	private final int WHAT_LOADEDBUILDINGFLOORFASTSEARCHKEY = 0;
	private final int WHAT_LOADSEARCHRESULT = 1;
	private GridView fastsearchkey_gv = null;
	private EditText searchkey_et = null;
	private List<HashMap<String, String>> IndoormapBuildingFloorSearchList = null;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.search);
		searchkey_et = (EditText) findViewById(R.id.searchkey_et);
		fastsearchkey_gv = (GridView) findViewById(R.id.fastsearchkey_gv);
		fastsearchkey_gv.setOnItemClickListener(new OnItemClickListener() {
			@Override
			public void onItemClick(AdapterView<?> parent, View view,
					int position, long id) {
				new doIndoormapBuildingFloorSearchThread().start();
			}
		});
		new getIndoormapBuildingFloorFastSearchKeyListThread().start();
	}

	private Handler handler = new Handler() {
		@Override
		public void handleMessage(Message msg) {
			switch (msg.what) {
			case WHAT_LOADEDBUILDINGFLOORFASTSEARCHKEY:
				if (msg.arg2 == 0) {
					IndoormapBuildingFloorSearchKeyList = (List<HashMap<String, String>>) msg.obj;
					fastsearchkey_gv.setAdapter(new BaseAdapter() {

						@Override
						public int getCount() {

							return ((IndoormapBuildingFloorSearchKeyList == null) ? 0
									: IndoormapBuildingFloorSearchKeyList
											.size());
						}

						@Override
						public View getView(int position, View convertView,
								ViewGroup parent) {
							View v = LayoutInflater.from(SearchActivity.this)
									.inflate(R.layout.imagetext, null);
							TextView fastsearchkey_tv = (TextView) v
									.findViewById(R.id.fastsearchkey_tv);
							int fastSearchKeyID = -1;
							try {
								fastSearchKeyID = Integer
										.valueOf(IndoormapBuildingFloorSearchKeyList
												.get(position).get(
														"SearchKeyID"));
								fastsearchkey_tv
										.setText(SearchKeyList[fastSearchKeyID]);
							} catch (Exception e) {
							}
							return v;
						}

						@Override
						public Object getItem(int position) {
							return null;
						}

						@Override
						public long getItemId(int position) {
							return 0;
						}
					});
				} else {
					Toast.makeText(SearchActivity.this, (String) msg.obj,
							Toast.LENGTH_SHORT).show();
				}
				break;
			case WHAT_LOADSEARCHRESULT:
				if (msg.arg2 == 0) {
					IndoormapBuildingFloorSearchList = (List<HashMap<String, String>>) msg.obj;
					ListView popupView = (ListView) LayoutInflater.from(
							SearchActivity.this).inflate(R.layout.floorsmenu,
							null);
					popupView.setAdapter(new BaseAdapter() {

						@Override
						public int getCount() {
							return ((IndoormapBuildingFloorSearchList == null) ? 0
									: IndoormapBuildingFloorSearchList.size());
						}

						@Override
						public View getView(int position, View convertView,
								ViewGroup parent) {
							HashMap<String, String> map = IndoormapBuildingFloorSearchList
									.get(position);
							TextView tv = new TextView(SearchActivity.this);
							tv.setText(map.get("ShopName"));
							tv.setTextColor(Color.BLACK);
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
							popupView, LinearLayout.LayoutParams.MATCH_PARENT,
							LinearLayout.LayoutParams.WRAP_CONTENT);
					popupView
							.setOnItemClickListener(new AdapterView.OnItemClickListener() {
								@Override
								public void onItemClick(
										AdapterView<?> groupview, View view,
										int position, long arg3) {
									popupWin.dismiss();
								}
							});
					popupWin.setAnimationStyle(R.style.popupttb);
					popupWin.showAsDropDown(searchkey_et, 0, 0);
				} else {
					Toast.makeText(SearchActivity.this, (String) msg.obj,
							Toast.LENGTH_SHORT).show();
				}
				break;
			}
		}
	};

	private class getIndoormapBuildingFloorFastSearchKeyListThread extends
			Thread {
		@Override
		public void run() {
			Message msg = handler.obtainMessage();
			msg.what = WHAT_LOADEDBUILDINGFLOORFASTSEARCHKEY;
			try {
				msg.obj = getIndoormapBuildingFloorFastSearchKeyList();
				msg.arg2 = 0;
			} catch (Exception e) {
				msg.obj = "获取楼层快捷关键字错误:" + e.getMessage();
				msg.arg2 = 1;
			}
			handler.sendMessage(msg);
		}
	}

	private List<HashMap<String, String>> getIndoormapBuildingFloorFastSearchKeyList()
			throws Exception {
		int actionID = ServiceClientInterface.ID_ACTION_getIndoormapBuildingFloorFastSearchKeyList;
		String parmas[] = { "1", "-1" };
		String result = ServiceClientInterface.postRequest(actionID, parmas);
		HashMap<String, List<HashMap<String, String>>> resultmap = GsonService
				.gsonGetHashMapListHashMap(result);
		List<HashMap<String, String>> IndoormapBuildingFloorSearchKeyList = resultmap
				.get("IndoormapBuildingFloorSearchKeyList");
		return (IndoormapBuildingFloorSearchKeyList);
	}

	private class doIndoormapBuildingFloorSearchThread extends Thread {
		@Override
		public void run() {
			Message msg = handler.obtainMessage();
			msg.what = WHAT_LOADSEARCHRESULT;
			try {
				msg.obj = doIndoormapBuildingFloorSearch();
				msg.arg2 = 0;
			} catch (Exception e) {
				msg.obj = "获取楼层快捷关键字错误:" + e.getMessage();
				msg.arg2 = 1;
			}
			handler.sendMessage(msg);
		}
	}

	private List<HashMap<String, String>> doIndoormapBuildingFloorSearch()
			throws Exception {
		int actionID = ServiceClientInterface.ID_ACTION_doIndoormapBuildingFloorSearch;
		String parmas[] = { "1", "-1", "无障碍出入口" };
		String result = ServiceClientInterface.postRequest(actionID, parmas);
		HashMap<String, List<HashMap<String, String>>> resultmap = GsonService
				.gsonGetHashMapListHashMap(result);
		List<HashMap<String, String>> IndoormapBuildingFloorSearch = resultmap
				.get("IndoormapBuildingFloorSearch");
		return (IndoormapBuildingFloorSearch);
	}
}