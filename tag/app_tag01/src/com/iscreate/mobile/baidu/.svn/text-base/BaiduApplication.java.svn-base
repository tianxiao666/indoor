package com.iscreate.mobile.baidu;

import android.app.Application;
import android.content.Context;
import android.widget.Toast;

import com.baidu.mapapi.BMapManager;
import com.baidu.mapapi.MKGeneralListener;
import com.baidu.mapapi.map.MKEvent;

public class BaiduApplication extends Application {

	private static BaiduApplication mInstance = null;
	private boolean m_bKeyRight = true;
	private static final String strKey = "0C80BA8FF9454CD0510FDB0BD51CCAC498851917";
	private BMapManager mBMapManager = null;

	@Override
	public void onCreate() {
		super.onCreate();
		mInstance = this;
	}

	public static BaiduApplication getInstance() {
		return mInstance;
	}

	public BMapManager getBMapManager() {
		return (mBMapManager);
	}

	public static void createBMapManager(Context context) {
		BaiduApplication app = BaiduApplication.getInstance();
		if (app.mBMapManager == null) {
			app.mBMapManager = new BMapManager(context);
		}
		if (!app.mBMapManager.init(BaiduApplication.strKey,
				new BaiduMKGeneralListener())) {
			Toast.makeText(
					BaiduApplication.getInstance().getApplicationContext(),
					"BMapManager 初始化错误!", Toast.LENGTH_SHORT).show();
		}
	}

	public static void destroyBMapManager() {
		BaiduApplication app = BaiduApplication.getInstance();
		if (app.mBMapManager != null) {
			app.mBMapManager.stop();
			app.mBMapManager.destroy();
			app.mBMapManager = null;
		}
	}

	public boolean isBaiduKeyRight() {
		return (m_bKeyRight);
	}

	private static class BaiduMKGeneralListener implements MKGeneralListener {

		@Override
		public void onGetNetworkState(int iError) {
			if (iError == MKEvent.ERROR_NETWORK_CONNECT) {
				Toast.makeText(
						BaiduApplication.getInstance().getApplicationContext(),
						"您的网络出错啦！", Toast.LENGTH_LONG).show();
			} else if (iError == MKEvent.ERROR_NETWORK_DATA) {
				Toast.makeText(
						BaiduApplication.getInstance().getApplicationContext(),
						"输入正确的检索条件！", Toast.LENGTH_LONG).show();
			}
		}

		@Override
		public void onGetPermissionState(int iError) {
			if (iError == MKEvent.ERROR_PERMISSION_DENIED) {
				Toast.makeText(
						BaiduApplication.getInstance().getApplicationContext(),
						"百度地图授权Key错误！", Toast.LENGTH_LONG).show();
				BaiduApplication.getInstance().m_bKeyRight = false;
			}
		}
	}
}