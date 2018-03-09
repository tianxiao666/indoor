package com.iscreate.mobile.service;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;

public class HttpService {
	public static HttpResponse postRequest(String url, String cookie,
			List<NameValuePair> nameValuePairs) throws Exception {
		UrlEncodedFormEntity urlEncodedFormEntity = null;
		try {
			urlEncodedFormEntity = new UrlEncodedFormEntity(nameValuePairs);
		} catch (Exception e) {
			throw (new Exception(
					"nameValuePairs不正确,无法创建UrlEncodedFormEntity!\n"
							+ e.getMessage()));
		}
		HttpPost httpPost = null;
		try {
			httpPost = new HttpPost(url);
		} catch (Exception e) {
			throw (new Exception("URL不正确,无法创建HttpPost!\n" + e.getMessage()));
		}
		httpPost.addHeader("charset", HTTP.UTF_8);
		httpPost.setEntity(urlEncodedFormEntity);
		httpPost.setHeader("Cookie", cookie);
		try {
			DefaultHttpClient httpClient = new DefaultHttpClient();
			HttpResponse response = httpClient.execute(httpPost);
			return (response);
		} catch (Exception e) {
			throw (new Exception("HttpPost不正确,DefaultHttpClient执行错误!\n"
					+ e.getMessage()));
		}
	}

	public static String postRequest(String url, String cookie,
			String jsonArrayObj) throws Exception {
		List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>();
		nameValuePairs
				.add(new BasicNameValuePair("jsonArrayObj", jsonArrayObj));
		return (getEntityFromHttpResponse(HttpService.postRequest(url, cookie,
				nameValuePairs)));
	}

	public static String getEntityFromHttpResponse(HttpResponse httpResponse)
			throws Exception {
		if (httpResponse != null) {
			HttpEntity httpEntity = httpResponse.getEntity();
			if (httpEntity != null) {
				try {
					return (EntityUtils.toString(httpEntity));
				} catch (Exception e) {
					throw (new Exception("解析HttpEntity出错(" + e.getMessage()
							+ ")!"));
				}
			}
		}
		return (null);
	}
}