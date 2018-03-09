package com.action;

import java.io.IOException;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.struts2.ServletActionContext;

import com.dao.SignalDao;
import com.dao.SignalInfoDao;
import com.dao.SignalRegionCenterDao;
import com.dao.common.MathUtil;
import com.dao.common.PointD;
import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

public class TriangulationLocationAction {
	private SignalInfoDao signalInfoDao;
	private SignalDao signalDao;
	private SignalRegionCenterDao signalRegionCenterDao;

	public void getLocation() {
		HttpServletResponse response = ServletActionContext.getResponse();
		HttpServletRequest request = ServletActionContext.getRequest();
		response.setCharacterEncoding("UTF-8");
		response.setContentType("text/html");

		String floor = "28";// 楼层
		Map<String, Float> defaultMap = new HashMap<String, Float>();

		// 74:25:8a:59:bd:d0=-66,
		// 74:25:8a:59:bd:b0=-77,
		// 74:25:8a:44:9d:d0=-92,
		// String ap_Iswlan3 = "Iswlan3";
		// String ap_iswlan27c = "iswlan27c";
		// String ap_HakaLink = "HakaLink";
		String ap_Iswlan3 = "74:25:8a:59:bd:d0";
		String ap_iswlan27c = "74:25:8a:59:bd:b0";
		String ap_HakaLink = "74:25:8a:44:9d:d0";
		// defaultMap.put("Iswlan3-loss", 3.2);//每米损耗
		defaultMap.put(ap_Iswlan3 + "-loss", 3f);// 每米损耗(is.wlan.sdc.201)
		defaultMap.put(ap_iswlan27c + "-loss", 3f);// 每米损耗
		defaultMap.put(ap_HakaLink + "-loss", 3f);// 每米损耗
		// defaultMap.put("iswlansdc201-loss", 3.2);//每米损耗

		// defaultMap.put("Iswlan3-max", -40);//4米信号强度
		defaultMap.put(ap_Iswlan3 + "-max", -35f);// 4米信号强度(is.wlan.sdc.201)
		defaultMap.put(ap_iswlan27c + "-max", -35f);// 4米信号强度
		defaultMap.put(ap_HakaLink + "-max", -35f);// 4米信号强度
		// defaultMap.put("iswlansdc201-max", 3.2);//每米损耗

		// iswlan3坐标 (132.10546875,45.8984375)
		// iswlan27坐标 (300.10546875,75.3984375)
		// haka坐标 (222,248)
		defaultMap.put(ap_Iswlan3 + "-x", 132.10546875f * 0.15f);//
		defaultMap.put(ap_Iswlan3 + "-y", 45.8984375f * 0.15f);//
		// defaultMap.put("iswlansdc201-x", 20.1);//
		// defaultMap.put("iswlansdc201-y", 12.2);//
		defaultMap.put(ap_iswlan27c + "-x", 300.10546875f * 0.15f);//
		defaultMap.put(ap_iswlan27c + "-y", 75.3984375f * 0.15f);//
		// defaultMap.put("HakaLink-x", 38.8);//
		// defaultMap.put("HakaLink-y", 2);//
		defaultMap.put(ap_HakaLink + "-x", 222f * 0.15f);//
		defaultMap.put(ap_HakaLink + "-y", 248f * 0.15f);//
		try {
			String jsonstr = "[{\"LEVEL\":\"-66\",\"CHANNEL\":\"11\",\"MAC_BSSID\":\"74:25:8a:59:bd:d0\",\"FREQUENCY\":\"2462\",\"EQUT_SSID\":\"IS_WLAN\"},{\"LEVEL\":\"-68\",\"CHANNEL\":\"11\",\"MAC_BSSID\":\"74:25:8a:59:bd:d1\",\"FREQUENCY\":\"2462\",\"EQUT_SSID\":\"IS_WLAN_C\"},{\"LEVEL\":\"-77\",\"CHANNEL\":\"11\",\"MAC_BSSID\":\"74:25:8a:59:bd:b0\",\"FREQUENCY\":\"2462\",\"EQUT_SSID\":\"IS_WLAN\"},{\"LEVEL\":\"-77\",\"CHANNEL\":\"11\",\"MAC_BSSID\":\"74:25:8a:59:bd:b1\",\"FREQUENCY\":\"2462\",\"EQUT_SSID\":\"IS_WLAN_C\"},{\"LEVEL\":\"-87\",\"CHANNEL\":\"6\",\"MAC_BSSID\":\"78:54:2e:a5:eb:b4\",\"FREQUENCY\":\"2437\",\"EQUT_SSID\":\"gdhytz\"},{\"LEVEL\":\"-92\",\"CHANNEL\":\"6\",\"MAC_BSSID\":\"74:25:8a:44:9d:d0\",\"FREQUENCY\":\"2437\",\"EQUT_SSID\":\"IS_WLAN\"},{\"LEVEL\":\"-94\",\"CHANNEL\":\"6\",\"MAC_BSSID\":\"74:25:8a:59:be:d1\",\"FREQUENCY\":\"2437\",\"EQUT_SSID\":\"IS_WLAN_C\"},{\"LEVEL\":\"-95\",\"CHANNEL\":\"6\",\"MAC_BSSID\":\"74:25:8a:44:9d:d1\",\"FREQUENCY\":\"2437\",\"EQUT_SSID\":\"IS_WLAN_C\"},{\"LEVEL\":\"-95\",\"CHANNEL\":\"6\",\"MAC_BSSID\":\"74:25:8a:59:be:d0\",\"FREQUENCY\":\"2437\",\"EQUT_SSID\":\"IS_WLAN\"},{\"LEVEL\":\"-93\",\"CHANNEL\":\"11\",\"MAC_BSSID\":\"0c:da:41:20:6f:10\",\"FREQUENCY\":\"2462\",\"EQUT_SSID\":\"IS_WLAN\"},{\"LEVEL\":\"-100\",\"CHANNEL\":\"12\",\"MAC_BSSID\":\"ec:6c:9f:18:d4:4a\",\"FREQUENCY\":\"2467\",\"EQUT_SSID\":\"YeahWorld0003\"}]";
			// String jsonstr = request.getParameter("jsonArrayObj");
			// jsonstr = jsonstr.replace("'", "");
			Gson gson = new Gson();
			// HashMap<String, String> allInfo = gson.fromJson(jsonstr,
			// new TypeToken<HashMap<String, String>>() {
			// }.getType());

			String wifinfoLastjsonstr = jsonstr;
			String wifinfojsonstr = jsonstr;
			// String steps = "0";
			// String direction = "10";

			List<HashMap<String, String>> wifiinfoLastList = gson.fromJson(
					wifinfoLastjsonstr,
					new TypeToken<List<HashMap<String, String>>>() {
					}.getType());

			List<HashMap<String, String>> wifiinfoList = gson.fromJson(
					wifinfojsonstr,
					new TypeToken<List<HashMap<String, String>>>() {
					}.getType());

			// 把信号源当前的信号强度列表更改为以信号源ssid为key,信号强度为value的map
			Map<String, Float> signalLastMap = getSignalMapByList(wifiinfoLastList);
			Map<String, Float> signalMap = getSignalMapByList(wifiinfoList);
			System.out.println("上一次采集信号:" + signalLastMap);
			System.out.println("本次采集信号:" + signalMap);

			// String ap_Iswlan3 = "74:25:8a:59:bd:d0";
			// String ap_iswlan27c = "74:25:8a:59:bd:b0";
			// String ap_HakaLink = "74:25:8a:44:9d:d0";

			Float iswlan3level = signalMap.get(ap_Iswlan3);
			Float iswlan3levellast = signalLastMap.get(ap_Iswlan3);
			Float iswlan27clevel = signalMap.get(ap_iswlan27c);
			Float iswlan27clevellast = signalLastMap.get(ap_iswlan27c);
			Float HakaLinklevel = signalMap.get(ap_HakaLink);
			Float HakaLinklevellast = signalLastMap.get(ap_HakaLink);

			// 我的座位地图坐标 (242,20)
			// iswlan3坐标 (132.10546875,45.8984375)
			// iswlan27坐标 (300.10546875,75.3984375)
			// haka坐标 (222,248)

			// iswlan3和座位的距离半径为：16.93575
			// iswlan27和座位的距离半径为：12.042331
			// haka和座位的距离半径为：34.33133
			MathUtil mathUtil = new MathUtil();
			float myseatx = 242f;
			float myseaty = 20f;
			float apx = 132.10546875f;
			float apy = 45.8984375f;
			float iswlan3_real_r = (float) mathUtil.getDistanceForTwoPoint(apx,
					apy, myseatx, myseaty) * 0.15f;
			apx = 300.10546875f;
			apy = 75.3984375f;
			float iswlan27_real_r = (float) mathUtil.getDistanceForTwoPoint(
					apx, apy, myseatx, myseaty) * 0.15f;
			apx = 222f;
			apy = 248f;
			float haka_real_r = (float) mathUtil.getDistanceForTwoPoint(apx,
					apy, myseatx, myseaty) * 0.15f;
			// iswlan3圆
			double iswlan3_r = getRadiusForSingal(defaultMap.get(ap_Iswlan3
					+ "-max"), iswlan3level, defaultMap.get(ap_Iswlan3
					+ "-loss"));
			System.out.println("信号强度为" + iswlan3level + "，iswlan3_r="
					+ iswlan3_r + ",应该为" + iswlan3_real_r);
			// iswlan3_r = 16.93575f;
			double iswlan3_last_r = getRadiusForSingal(defaultMap
					.get(ap_Iswlan3 + "-max"), iswlan3levellast, defaultMap
					.get(ap_Iswlan3 + "-loss"));
			// HakaLink圆
			double hakalink_r = getRadiusForSingal(defaultMap.get(ap_HakaLink
					+ "-max"), HakaLinklevel, defaultMap.get(ap_HakaLink
					+ "-loss"));
			System.out.println("信号强度为" + HakaLinklevel + "，hakalink_r="
					+ hakalink_r + ",应该为" + haka_real_r);
			// hakalink_r = 34.33133f;
			double hakalink_last_r = getRadiusForSingal(defaultMap
					.get(ap_HakaLink + "-max"), HakaLinklevellast, defaultMap
					.get(ap_HakaLink + "-loss"));
			// iswlan27c圆
			double iswlan27c_r = getRadiusForSingal(defaultMap.get(ap_iswlan27c
					+ "-max"), iswlan27clevel, defaultMap.get(ap_iswlan27c
					+ "-loss"));
			System.out.println("信号强度为" + iswlan27clevel + "，iswlan27c_r="
					+ iswlan27c_r + ",应该为" + iswlan27_real_r);
			// iswlan27c_r = 12.042331f;
			double iswlan27c_last_r = getRadiusForSingal(defaultMap
					.get(ap_iswlan27c + "-max"), iswlan27clevellast, defaultMap
					.get(ap_iswlan27c + "-loss"));
			float iswlan3_x = defaultMap.get(ap_Iswlan3 + "-x");
			float iswlan3_y = defaultMap.get(ap_Iswlan3 + "-y");
			float iswlan27c_x = defaultMap.get(ap_iswlan27c + "-x");
			float iswlan27c_y = defaultMap.get(ap_iswlan27c + "-y");
			float hakalink_x = defaultMap.get(ap_HakaLink + "-x");
			float hakalink_y = defaultMap.get(ap_HakaLink + "-y");
			System.out.println("上一次计算半径:iswlan3_last_r--" + iswlan3_last_r
					+ "||iswlan27c_last_r--" + iswlan27c_last_r
					+ "||hakalink_last_R--" + hakalink_last_r);
			System.out.println("本次计算半径:iswlan3_r--" + iswlan3_r
					+ "||iswlan27c_r--" + iswlan27c_r + "||hakalink_r--"
					+ hakalink_r);
			// -------------------------
			PointD[] list_wlan3_wlan27 = mathUtil.getIntersectionForTwoCircle(
					iswlan3_r, iswlan3_x, iswlan3_y, iswlan27c_r, iswlan27c_x,
					iswlan27c_y);
			if (list_wlan3_wlan27 != null) {
				System.out.println("iswlan3和iswlan27c有交点");
			}
			// 开始计算3个圆的交点
			PointD[] list_wlan3_haka = mathUtil.getIntersectionForTwoCircle(
					iswlan3_r, iswlan3_x, iswlan3_y, hakalink_r, hakalink_x,
					hakalink_y);
			if (list_wlan3_haka != null) {
				System.out.println("iswlan3和hakalink有交点");
			}
			PointD[] list_wlan27_haka = mathUtil.getIntersectionForTwoCircle(
					iswlan27c_r, iswlan27c_x, iswlan27c_y, hakalink_r,
					hakalink_x, hakalink_y);
			if (list_wlan27_haka != null) {
				System.out.println("iswlan27c和hakalink有交点");
			}
			PointD[] list_wlan3_wlan27_last = mathUtil
					.getIntersectionForTwoCircle(iswlan3_last_r, iswlan3_x,
							iswlan3_y, iswlan27c_last_r, iswlan27c_x,
							iswlan27c_y);
			if (list_wlan3_wlan27_last != null) {
				System.out.println("iswlan3_last和iswlan27c_last有交点");
			}
			PointD[] list_wlan3_haka_last = mathUtil
					.getIntersectionForTwoCircle(iswlan3_last_r, iswlan3_x,
							iswlan3_y, hakalink_last_r, hakalink_x, hakalink_y);
			if (list_wlan3_haka_last != null) {
				System.out.println("iswlan3_last和hakalink_last有交点");
			}
			PointD[] list_wlan27_haka_last = mathUtil
					.getIntersectionForTwoCircle(iswlan27c_last_r, iswlan27c_x,
							iswlan27c_y, hakalink_last_r, hakalink_x,
							hakalink_y);
			if (list_wlan27_haka_last != null) {
				System.out.println("iswlan27c_last和hakalink_last有交点");
			}
			PointD locationlast = null;
			// 计算上次的位置
			if (locationlast == null) {
				locationlast = getLocation(hakalink_last_r, hakalink_x,
						hakalink_y, list_wlan3_wlan27_last);
			}
			if (locationlast == null) {
				locationlast = getLocation(iswlan27c_last_r, iswlan27c_x,
						iswlan27c_y, list_wlan3_haka_last);
			}
			if (locationlast == null) {
				locationlast = getLocation(iswlan3_last_r, iswlan3_x,
						iswlan3_y, list_wlan27_haka_last);
			}
			PointD location = null;
			// 计算本次的位置
			if (location == null) {
				location = getLocation(hakalink_r, hakalink_x, hakalink_y,
						list_wlan3_wlan27);
			}
			if (location == null) {
				location = getLocation(iswlan27c_r, iswlan27c_x, iswlan27c_y,
						list_wlan3_haka);
			}
			if (location == null) {
				location = getLocation(iswlan3_r, iswlan3_x, iswlan3_y,
						list_wlan27_haka);
			}
			if (location == null) {
				location = new PointD();
			}
			System.out.println(" 本次地图坐标位置:(" + (location.x / 0.15f) + ","
					+ (location.y / 0.15f) + ") px");
			// System.out.println("共走的步数:" + steps);
			HashMap<String, Object> returnMap = new HashMap<String, Object>();
			returnMap.put("x", "" + location.x);
			returnMap.put("y", "" + location.y);
			returnMap
					.put(
							"photo_url",
							"http://192.168.243.185:8818/indooradmin/medialib/indoor_plane_map_auto/2/2_stran.png");
			// returnMap.put("region", regionMap.get("LAYER_TOPIC"));
			returnMap.put("width", "45");
			returnMap.put("height", "30");
			Map<String, Object> cicleMap1 = new HashMap<String, Object>();
			Map<String, Object> cicleMap2 = new HashMap<String, Object>();
			Map<String, Object> cicleMap3 = new HashMap<String, Object>();
			cicleMap1.put("x", iswlan3_x);
			cicleMap1.put("y", iswlan3_y);
			cicleMap1.put("r", iswlan3_r);

			cicleMap2.put("x", iswlan27c_x);
			cicleMap2.put("y", iswlan27c_y);
			cicleMap2.put("r", iswlan27c_r);

			cicleMap3.put("x", hakalink_x);
			cicleMap3.put("y", hakalink_y);
			cicleMap3.put("r", hakalink_r);

			Map<String, Object> returnAllMap = new HashMap<String, Object>();
			returnAllMap.put("return", returnMap);
			returnAllMap.put("cicle1", cicleMap1);
			returnAllMap.put("cicle2", cicleMap2);
			returnAllMap.put("cicle3", cicleMap3);
			response.getWriter().write(gson.toJson(returnAllMap));
		} catch (Exception e) {
			try {
				response.getWriter().write("error:" + e.getMessage());
			} catch (IOException e1) {
				e1.printStackTrace();
			}
		}
	}

	private double getRadiusForSingal(double level0, double level, double loss) {
		double exponent = (level0 - level) / (loss * 10);
		double radius = Math.pow(Math.E, exponent) * 4;
		return (radius);
	}

	private PointD getLocation(double r, double r_x, double r_y,
			PointD[] intersections) {
		if (intersections != null) {
			float x0 = (float) intersections[0].x;
			float y0 = (float) intersections[0].y;
			float x1 = x0;
			float y1 = y0;
			if (intersections.length > 1) {
				x1 = (float) intersections[1].x;
				y1 = (float) intersections[1].y;
			}
			return (computeLineCircle(r, r_x, r_y, x0, y0, x1, y1));
		}
		return (null);
	}

	/**
	 * 计算两个圆的交点组成的直线，与另外一个圆的交点
	 * 
	 * @param r
	 *            第三个圆的半径
	 * @param r_x
	 *            第三个圆坐标x
	 * @param r_y
	 *            第三个圆坐标y
	 * @param x0
	 *            直线的两个点x
	 * @param y0
	 *            直线的两个点y
	 * @param x1
	 *            直线的两个点x
	 * @param y1
	 *            直线的两个点y
	 */
	private PointD computeLineCircle(double r, double r_x, double r_y,
			double x0, double y0, double x1, double y1) {
		// 两个圆有交点,开始计算交点的直线公式
		// 第三个圆与第一个交点的距离
		double distance0 = new MathUtil().getDistanceForTwoPoint(r_x, r_y, x0,
				y0);
		// 第三个圆与第二个交点的距离
		double distance1 = new MathUtil().getDistanceForTwoPoint(r_x, r_y, x1,
				y1);
		// System.out.println(distance0+"||"+distance1+"||"+r);
		PointD p = new PointD();
		if (r < distance0 && r <= distance1) {// 两个圆的直线与第三个圆没有交点，且距离大于第三个圆的半径
			if (distance0 < distance1) {
				p.x = x0;
				p.y = y0;
			} else {
				p.x = x1;
				p.y = y1;
			}
		} else if (r >= distance0 && r >= distance1) {// 两个圆的直线与第三个圆没有交点，且距离小于第三个圆的半径
			if (distance0 < distance1) {
				p.x = x1;
				p.y = y1;
			} else {
				p.x = x0;
				p.y = y0;
			}
		} else {
			PointD[] intersections = new MathUtil()
					.getIntersectionForCircleAndLine(r, r_x, r_y, x0, y0, x1,
							y1);
			if (intersections != null) {
				if (intersections.length > 1) {
					if (Math.min(x0, x1) <= intersections[1].x
							&& intersections[1].x <= Math.max(x0, x1)) {
						p = intersections[1];
					}
				} else {
					p = intersections[0];
				}
			}
		}
		return (p);
	}

	/**
	 *得到以信号源mac为key的MAP
	 * 
	 */
	private Map<String, Float> getSignalMapByList(
			List<HashMap<String, String>> signalList) {
		Map<String, Float> signalMap = new HashMap<String, Float>();
		if (signalList != null) {
			for (int i = 0; i < signalList.size(); i++) {
				signalMap.put(signalList.get(i).get("MAC_BSSID"), Float
						.parseFloat(signalList.get(i).get("LEVEL")));
			}
		}
		return signalMap;
	}

	/**
	 * 计算两个坐标点的中心点 参数list中需要有两个map,每一个map中都存储一个坐标点的经纬度
	 * list长度最小必须为2，当长度大于2的时候，只考虑前两个元素
	 */
	private Map<String, Object> computeTwoPointCenter(
			List<Map<String, Object>> list) {
		if (list == null || list.size() < 2 || list.get(0).get("x").equals("")
				|| list.get(1).get("x").equals("")
				|| list.get(0).get("y").equals("")
				|| list.get(1).get("y").equals("")) {
			// 参数为空，或者列表长度小于2个，或者列表前两个元素中不包含x和y
			return null;
		} else {
			float x1 = Float.parseFloat(list.get(0).get("x").toString());
			float x2 = Float.parseFloat(list.get(1).get("x").toString());
			float y1 = Float.parseFloat(list.get(0).get("y").toString());
			float y2 = Float.parseFloat(list.get(1).get("y").toString());
			float center_x = (x1 + x2) / 2;
			float center_y = (y1 + y2) / 2;
			Map<String, Object> returnMap = new HashMap<String, Object>();
			returnMap.put("center_x", center_x);
			returnMap.put("center_y", center_y);
			return returnMap;
		}
	}

	/**
	 * 判断两个圆是否有交点 r1,r2：两个圆的半径 a1,b1:半径为r1的圆心坐标 a2,b2:半径为r2的圆心坐标
	 */
	private boolean checkCircleHaveIntersection1(float r1, float a1, float b1,
			float r2, float a2, float b2) {
		// 计算两个圆心之间的距离
		float distance = (float) Math.sqrt((a1 - a2) * (a1 - a2) + (b1 - b2)
				* (b1 - b2));
		// System.out.println(distance);
		// System.out.println(r1+"||"+r2);
		float min = Math.abs(r1 - r2);
		float max = r1 + r2;
		if (min <= distance && max >= distance) {
			// 有交点
			return true;
		} else {
			// 无交点
			return false;
		}
	}

	/**
	 * 计算多个点的中心点
	 */
	private Map<String, Float> computeCenterNode(
			List<Map<String, Float>> list1, List<Map<String, Float>> list2,
			List<Map<String, Float>> list3) {
		float total_x = 0;
		float total_y = 0;
		int num = 0;
		if (list1 != null) {
			if (list1.get(0).get("x") > 0 && list1.get(0).get("y") > 0
					&& list1.get(0).get("x") < 50 && list1.get(0).get("y") < 30) {
				num++;
				total_x = total_x + list1.get(0).get("x");
				total_y = total_y + list1.get(0).get("y");
			}
			if (list1.get(1).get("x") > 0 && list1.get(1).get("y") > 0
					&& list1.get(1).get("x") < 50 && list1.get(1).get("y") < 30) {
				num++;
				total_x = total_x + list1.get(1).get("x");
				total_y = total_y + list1.get(1).get("y");
			}

		}
		if (list2 != null) {
			if (list2.get(0).get("x") > 0 && list2.get(0).get("y") > 0
					&& list2.get(0).get("x") < 50 && list2.get(0).get("y") < 30) {
				num++;
				total_x = total_x + list2.get(0).get("x");
				total_y = total_y + list2.get(0).get("y");
			}
			if (list2.get(1).get("x") > 0 && list2.get(1).get("y") > 0
					&& list2.get(1).get("x") < 50 && list2.get(1).get("y") < 30) {
				num++;
				total_x = total_x + list2.get(1).get("x");
				total_y = total_y + list2.get(1).get("y");
			}

		}
		if (list3 != null) {
			if (list3.get(0).get("x") > 0 && list3.get(0).get("y") > 0
					&& list3.get(0).get("x") < 50 && list3.get(0).get("y") < 30) {
				num++;
				total_x = total_x + list3.get(0).get("x");
				total_y = total_y + list3.get(0).get("y");
			}
			if (list3.get(1).get("x") > 0 && list3.get(1).get("y") > 0
					&& list3.get(1).get("x") < 50 && list3.get(1).get("y") < 30) {
				num++;
				total_x = total_x + list3.get(1).get("x");
				total_y = total_y + list3.get(1).get("y");
			}

		}
		if (num > 0) {
			float x = total_x / num;
			float y = total_y / num;
			Map<String, Float> returnMap = new HashMap<String, Float>();
			returnMap.put("x", x);
			returnMap.put("y", y);
			return returnMap;
		} else {
			return null;
		}

	}

	public SignalInfoDao getSignalInfoDao() {
		return signalInfoDao;
	}

	public void setSignalInfoDao(SignalInfoDao signalInfoDao) {
		this.signalInfoDao = signalInfoDao;
	}

	public SignalDao getSignalDao() {
		return signalDao;
	}

	public void setSignalDao(SignalDao signalDao) {
		this.signalDao = signalDao;
	}

	public SignalRegionCenterDao getSignalRegionCenterDao() {
		return signalRegionCenterDao;
	}

	public void setSignalRegionCenterDao(
			SignalRegionCenterDao signalRegionCenterDao) {
		this.signalRegionCenterDao = signalRegionCenterDao;
	}
}
