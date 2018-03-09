package com.action;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.struts2.ServletActionContext;

import com.google.gson.Gson;

public class GetNearbyIndoormapBuildingListAction {
	/*
	 * 获取附近的室内地图建筑的列表
	 */
   public void indexAction(){
	    HttpServletResponse response = ServletActionContext.getResponse();
		HttpServletRequest request = ServletActionContext.getRequest();
		response.setCharacterEncoding("UTF-8");
		response.setContentType("text/html");
		try{
			   List<Map<String,Object>> list = new ArrayList<Map<String,Object>>();
			   Map<String,Object> map = new HashMap<String,Object>();
			   map.put("BuildingID", 1);
			   map.put("BuildingTypeID", 1);
			   map.put("BuildingName", "耀和广场");
			   map.put("Longitude", 113.353061);
			   map.put("Latitude", 23.149672);
			   map.put("Distance", 10);
			   map.put("BuildingIcon","");
			   list.add(map);
			   Map<String,Object> returnMap = new HashMap<String,Object>();
			   returnMap.put("IndoormapBuildingList", list);
			   Gson gson = new Gson();
			   response.getWriter().write(gson.toJson(returnMap));
		}catch(Exception e){
			
		}
	   
   }
}