package com.action;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.struts2.ServletActionContext;

import com.google.gson.Gson;

public class GetIndoormapBuildingFloorMapUrlAction {

	/*
	 * 获取楼层地图
	 */
   public void indexAction(){
	    HttpServletResponse response = ServletActionContext.getResponse();
		HttpServletRequest request = ServletActionContext.getRequest();
		response.setCharacterEncoding("UTF-8");
		response.setContentType("text/html");
		try{
			   Map<String,Object> returnMap = new HashMap<String,Object>();
			   returnMap.put("url", "http://192.168.206.116:8080/indoor/28F.jpg");
			   Gson gson = new Gson();
			   response.getWriter().write(gson.toJson(returnMap));
		}catch(Exception e){
			
		}
	   
   }
}
