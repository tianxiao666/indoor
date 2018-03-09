package com.action;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.struts2.ServletActionContext;

import com.google.gson.Gson;

public class CheckHasNewVersionAction {
	/*
	 * 检测版本更新
	 */
   public void indexAction(){
	    HttpServletResponse response = ServletActionContext.getResponse();
		HttpServletRequest request = ServletActionContext.getRequest();
		response.setCharacterEncoding("UTF-8");
		response.setContentType("text/html");
		try{
			   Map<String,Object> returnMap = new HashMap<String,Object>();
			   returnMap.put("VersionName", "测试版");
			   returnMap.put("VersionCode", "V1");
			   returnMap.put("UpdateTime", "2013-08-06");
			   returnMap.put("UpdateDescription", "第一版上线");
			   returnMap.put("FileSize", "66M");
			   returnMap.put("url", "");
			   Gson gson = new Gson();
			   response.getWriter().write(gson.toJson(returnMap));
		}catch(Exception e){
			
		}
	   
   }
}
