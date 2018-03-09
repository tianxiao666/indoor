package com.iscreate.mobile.baidu;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;

import com.google.gson.Gson;
import com.iscreate.mobile.service.GsonService;
import com.iscreate.mobile.service.HttpService;

public class MapHelper {

	// 经纬度转换地址 百度地图api url
	public static final String MAPCODEURL = "http://api.map.baidu.com/geocoder/v2/?ak=844f1e1721c48a98b2c8dd39fee35a78&output=json&pois=0";

	/**
	 * 
	 * @description: 经纬度转换为实际地址
	 * @author：yuan.yw
	 * @param longitude
	 * @param latitude
	 * @return
	 * @return String
	 * @date：Jul 22, 2013 2:30:21 PM
	 */
	public static String convertLatlngToAddress(String longitude,
			String latitude) {
		String result = "";
		try {
			String location = "&location=" + latitude + "," + longitude;
			List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
			HttpResponse httpResponse = HttpService.postRequest(MAPCODEURL
					+ location, null, nameValuePairs);// http 链接
			result = HttpService.getEntityFromHttpResponse(httpResponse);
			if (result != null && !"".equals(result)) {// 返回结果 取出地址
				Gson gson = new Gson();
				MapReturnResult mapReturnResult = null;
				mapReturnResult = gson.fromJson(result, MapReturnResult.class);// json格式数据转换
				if (mapReturnResult != null) {
					MapAddressResult r = mapReturnResult.getResult();
					if ("0".equals(mapReturnResult.getStatus())) {// status 为 0
																	// 返回成功
																	// 具体status值参见mapReturnResult.class
						result = r.getFormatted_address();// 实际地址
					} else {
						result = "";
					}
				}
			}
		} catch (Exception e) {
			result = "";
		}
		return result;// 返回结果
	}

	/*
	 * public static void main(String[] args){
	 * System.out.println(MapHelper.convertLatlngToAddress(116.322987,
	 * 39.983424)); }
	 */

}
