package com.iscreate.mobile.service;

import java.util.HashMap;
import java.util.List;

import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

public class GsonService {
	/**
	 * 把src转换成Json字符串
	 * 
	 * @param jsonstr
	 *            Object
	 */
	public static String gsonObjectToJson(Object src) {
		Gson gson = new Gson();
		return (gson.toJson(src));
	}

	/**
	 * 把jsonstr转换成HashMap<String, String>对象
	 * 
	 * @param jsonstr
	 *            String
	 */
	public static HashMap<String, String> gsonGetHashMap(String jsonstr)
			throws Exception {
		try {
			Gson gson = new Gson();
			return (gson.fromJson(jsonstr,
					new TypeToken<HashMap<String, String>>() {
					}.getType()));
		} catch (Exception e) {
			throw (new Exception("不是HashMap的Json字符串,Gson解析出错!"));
		}
	}

	/**
	 * 把jsonstr转换成HashMap<String, HashMap<String, String>>对象
	 * 
	 * @param jsonstr
	 *            String
	 */
	public static HashMap<String, HashMap<String, String>> gsonGetHashMapHashMap(
			String jsonstr) throws Exception {
		try {
			Gson gson = new Gson();
			return (gson.fromJson(jsonstr,
					new TypeToken<HashMap<String, HashMap<String, String>>>() {
					}.getType()));
		} catch (Exception e) {
			throw (new Exception("不是HashMapHashMap的Json字符串,Gson解析出错!"));
		}
	}

	/**
	 * 把jsonstr转换成List<HashMap<String, String>>对象
	 * 
	 * @param jsonstr
	 *            String
	 */
	public static List<HashMap<String, String>> gsonGetListHashMap(
			String jsonstr) throws Exception {
		try {
			Gson gson = new Gson();
			return (gson.fromJson(jsonstr,
					new TypeToken<List<HashMap<String, String>>>() {
					}.getType()));
		} catch (Exception e) {
			throw (new Exception("不是ListHashMap的Json字符串,Gson解析出错!"));
		}
	}

	/**
	 * 把jsonstr转换成HashMap<String, List<HashMap<String, String>>>对象
	 * 
	 * @param jsonstr
	 *            String
	 */
	public static HashMap<String, List<HashMap<String, String>>> gsonGetHashMapListHashMap(
			String jsonstr) throws Exception {
		try {
			Gson gson = new Gson();
			return (gson
					.fromJson(
							jsonstr,
							new TypeToken<HashMap<String, List<HashMap<String, String>>>>() {
							}.getType()));
		} catch (Exception e) {
			throw (new Exception("不是HashMapListHashMap的Json字符串,Gson解析出错!"));
		}
	}

	/**
	 * 把jsonstr转换成List<HashMap<String, List<HashMap<String, String>>>>对象
	 * 
	 * @param jsonstr
	 *            String
	 */
	public static List<HashMap<String, List<HashMap<String, String>>>> gsonGetListHashMapListHashMap(
			String jsonstr) throws Exception {
		try {
			Gson gson = new Gson();
			return (gson
					.fromJson(
							jsonstr,
							new TypeToken<List<HashMap<String, List<HashMap<String, String>>>>>() {
							}.getType()));
		} catch (Exception e) {
			throw (new Exception("不是HashMapListHashMap的Json字符串,Gson解析出错!"));
		}
	}
}