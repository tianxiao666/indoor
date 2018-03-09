package com.iscreate.mobile.service;

public class ServiceClientInterface {
	private static final String DefaultParameter = "{\"11111\":\"222222\"}";
	private static final String[] ServiceClientActionArray = {
			"getNearbyIndoormapBuildingList",// 0
			"getIndoormapBuildingFloorList",// 1
			"getIndoormapBuildingFloorSvg", // 2
			"getIndoormapBuildingFloorFastSearchKeyList",// 3
			"doIndoormapBuildingFloorSearch",// 4
			"checkHasNewVersion",// 5
			"getSvg"// 6
	};

	private static final String[][] ServiceClientActionParameter = {
			{ "BUILDING_TYPE_ID", "LONGITUDE", "LATITUDE", "RANGE" },// 0
			{ "BUILDING_ID" },// 1
			{  "FLOOR_ID","BUILDING_ID" },// 2
			{ "BuildingID", "FloorID" }, // 3
			{ "BuildingID", "FloorID", "SearchKey" }, // 4
			{ "VersionName", "VersionCode" }, // 5
			{"DRAW_MAP_ID","FLOOR_ID","BUILDING_ID"}//6
	};

	public static final int ID_ACTION_getNearbyIndoormapBuildingList = 0;
	public static final int ID_ACTION_getIndoormapBuildingFloorList = 1;
	public static final int ID_ACTION_getIndoormapBuildingFloorSvg = 2;
	public static final int ID_ACTION_getIndoormapBuildingFloorFastSearchKeyList = 3;
	public static final int ID_ACTION_doIndoormapBuildingFloorSearch = 4;
	public static final int ID_ACTION_checkHasNewVersion = 5;
	public static final int ID_ACTION_getSvg = 6;
	public static final String getServiceClientAction(int actionID) {
		return ((actionID >= ServiceClientActionArray.length) ? null
				: ServiceClientActionArray[actionID]);
	}

	public static final String getServiceClientActionParameter(int actionID,
			String[] params) {
		if ((params != null)
				&& (actionID < ServiceClientActionParameter.length)) {
			return (getJsonStrParameter(ServiceClientActionParameter[actionID],
					params));
		}
		return (DefaultParameter);
	}

	private static String jsonKeyValue(String key, String value) {
		return ("\"" + key + "\":\"" + replaceEscape(value) + "\"");
	}

	private static String replaceEscape(String s) {
		if (s != null) {
			int len = s.length();
			if (len > 0) {
				StringBuffer sb = new StringBuffer();
				char c;
				int i = 0;
				while (i < len) {
					c = s.charAt(i);
					switch (c) {
					case '\"':
						sb.append("\\\"");
						break;
					case '\\':
						sb.append("\\\\");
						break;
					case '/':
						sb.append("\\/");
						break;
					case '\b':
						sb.append("\\b");
						break;
					case '\f':
						sb.append("\\f");
						break;
					case '\n':
						sb.append("\\n");
						break;
					case '\r':
						sb.append("\\r");
						break;
					case '\t':
						sb.append("\\t");
						break;
					default:
						sb.append(c);
					}
					++i;
				}
				return (sb.toString());
			}
		}
		return ("");
	}

	private static String getJsonStrParameter(String[] keyArray,
			String[] valueArray) {
		if ((keyArray != null) && (valueArray != null)) {
			int count = keyArray.length;
			if (count > valueArray.length) {
				count = valueArray.length;
			}
			String JsonStr = "{";
			if (count > 0) {
				JsonStr = JsonStr + jsonKeyValue(keyArray[0], valueArray[0]);
			}
			int i = 1;
			while (i < count) {
				JsonStr = JsonStr + ","
						+ jsonKeyValue(keyArray[i], valueArray[i]);
				++i;
			}
			JsonStr = JsonStr + "}";
			return (JsonStr);
		}
		return (null);
	}

	//public static String url = "http://192.168.206.239:8080/indoor/";
	public static String url = "http://192.168.243.185:8838/indoor/trunk/indooradmin/ea.php?r=ServiceClientInterface/";
	public static String cookie = null;

	public static String postRequest(int actionID, String[] parmas)
			throws Exception {
		return (HttpService.postRequest(url + getServiceClientAction(actionID),
				cookie, getServiceClientActionParameter(actionID, parmas)));
	}
}