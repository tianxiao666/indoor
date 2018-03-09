package com.action;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.struts2.ServletActionContext;

import com.google.gson.Gson;

public class GetIndoormapBuildingFloorListAction {
	/*
	 * 获取建筑楼层列表
	 */
   public void indexAction(){
	    HttpServletResponse response = ServletActionContext.getResponse();
		HttpServletRequest request = ServletActionContext.getRequest();
		response.setCharacterEncoding("UTF-8");
		response.setContentType("text/html");
		try{
			   List<Map<String,Object>> list = new ArrayList<Map<String,Object>>();
			   Map<String,Object> map = new HashMap<String,Object>();
			   map.put("FloorID", 1);
			   map.put("FloorName", "一层");
			   map.put("FloorSummary", "衣服商场");
			   list.add(map);
			   Map<String,Object> returnMap = new HashMap<String,Object>();
			   returnMap.put("IndoormapBuildingFloorList", list);
			   Gson gson = new Gson();
			   response.getWriter().write(gson.toJson(returnMap));
		}catch(Exception e){
			
		}
	   
   }
}