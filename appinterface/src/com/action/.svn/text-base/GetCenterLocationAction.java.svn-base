package com.action;

import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.struts2.ServletActionContext;

import com.dao.SignalDao;
import com.dao.SignalInfoDao;
import com.dao.SignalRegionCenterDao;
import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;

public class GetCenterLocationAction {
	private SignalInfoDao signalInfoDao;
	private SignalDao signalDao;
	private SignalRegionCenterDao signalRegionCenterDao;
	public void getLocation(){
		HttpServletResponse response = ServletActionContext.getResponse();
		HttpServletRequest request = ServletActionContext.getRequest();
		response.setCharacterEncoding("UTF-8");
		response.setContentType("text/html");
		
		Map<String,Object> defaultMap = new HashMap<String,Object>();
		defaultMap.put("Iswlan3-loss", 5);//每米损耗
		defaultMap.put("iswlan27c-loss", 3);//每米损耗
		defaultMap.put("HakaLink-loss", 4.7);//每米损耗
		
		defaultMap.put("Iswlan3-max", -15);//最大信号强度
		defaultMap.put("iswlan27c-max", -22);//最大信号强度
		defaultMap.put("HakaLink-max", -30);//最大信号强度
		
		defaultMap.put("Iswlan3-x", 20.1);//
		defaultMap.put("Iswlan3-y", 12.2);//
		defaultMap.put("iswlan27c-x", 39.8);//
		defaultMap.put("iswlan27c-y", 17.8);//
		//defaultMap.put("HakaLink-x", 38.8);//
		//defaultMap.put("HakaLink-y", 2);//
		defaultMap.put("HakaLink-x", 24);//
		defaultMap.put("HakaLink-y", 2);//
		//System.out.println(Math.E);
		System.out.println(Math.pow(Math.E, 0.7));
		
    	try {
			String jsonstr = request.getParameter("jsonArrayObj");
		    jsonstr = jsonstr.replace("'", "");
		    
			Gson gson = new Gson();
			HashMap<String, String> allInfo = gson.fromJson(jsonstr,
					new TypeToken<HashMap<String, String>>() {
					}.getType());
			String wifinfoLastjsonstr = allInfo.get("wifiinfolast");
			String wifinfojsonstr = allInfo.get("wifiinfo");
			String position = allInfo.get("position");
			List<HashMap<String, String>> wifiinfoLastList = gson.fromJson(wifinfoLastjsonstr,
					new TypeToken<List<HashMap<String, String>>>() {}.getType());
			
			List<HashMap<String, String>> wifiinfoList = gson.fromJson(wifinfojsonstr,
					new TypeToken<List<HashMap<String, String>>>() {}.getType());
			
			//wifiinfoMap记录需要计算的3个yifi点的强度值,key是ssid，value是信号强度
		    /*long time_flag = System.currentTimeMillis();
		    if(wifiinfoList!=null){
				for(int i=0;i<wifiinfoList.size();i++){
				   Map<String,String> map = wifiinfoList.get(i);
				   String mac = (String) map.get("MAC");
				   int intensity = Integer.parseInt((String) map.get("LEVEL"));
				   String ssid = (String) map.get("SSID");
				   if(ssid != null && !ssid.equals("") && (ssid.equals("Iswlan3")||ssid.equals("iswlan27c")||ssid.equals("HakaLink"))){
					   //目前只考虑Iswlan3、iswlan27c
				       signalInfoDao.addSignalInfo(mac, ssid, intensity,position,time_flag);
				   }
				}
			}*/
		   
		  //得到刚入库的信号点的平均强度
			//List<Map<String,Object>> averageSignalList = signalInfoDao.getAverageSignalInfo(time_flag);
			//把信号源当前的信号强度列表更改为以信号源ssid为key,信号强度为value的map
			Map<String,Object> signalLastMap =  getSignalMapByList(wifiinfoLastList);
			Map<String,Object> signalMap =  getSignalMapByList(wifiinfoList);
			
			//Iswlan3圆
			float iswlan3_r = (Float.parseFloat(defaultMap.get("Iswlan3-max").toString())- Float.parseFloat(signalMap.get("Iswlan3").toString()))
			                     /Float.parseFloat(defaultMap.get("Iswlan3-loss").toString());
			float iswlan3_x =  Float.parseFloat(defaultMap.get("Iswlan3-x").toString());
			float iswlan3_y =  Float.parseFloat(defaultMap.get("Iswlan3-y").toString());
			
			//HakaLink圆
			float hakalink_r = (Float.parseFloat(defaultMap.get("HakaLink-max").toString())- Float.parseFloat(signalMap.get("HakaLink").toString()))
                                  /Float.parseFloat(defaultMap.get("HakaLink-loss").toString());
			float hakalink_x =  Float.parseFloat(defaultMap.get("HakaLink-x").toString());
			float hakalink_y =  Float.parseFloat(defaultMap.get("HakaLink-y").toString());
			//System.out.println(signalMap);
			//iswlan27c圆
			float iswlan27c_r = (Float.parseFloat(defaultMap.get("iswlan27c-max").toString())- Float.parseFloat(signalMap.get("iswlan27c").toString()))
			                       /Float.parseFloat(defaultMap.get("iswlan27c-loss").toString());
			float iswlan27c_x =  Float.parseFloat(defaultMap.get("iswlan27c-x").toString());
			float iswlan27c_y =  Float.parseFloat(defaultMap.get("iswlan27c-y").toString());
			
			
			//开始计算交点中心点
			Map<String,Float> nodeMap1 = computeNodeLocation(iswlan3_r,iswlan3_x,iswlan3_y,
					iswlan27c_r,iswlan27c_x,iswlan27c_y);
			
			//System.out.println(nodeMap1);
			Map<String,Float> nodeMap2 = computeNodeLocation(iswlan3_r,iswlan3_x,iswlan3_y,
					hakalink_r,hakalink_x,hakalink_y);
			//System.out.println(nodeMap2);
			
			Map<String,Float> nodeMap3 = computeNodeLocation(iswlan27c_r,iswlan27c_x,iswlan27c_y,
					hakalink_r,hakalink_x,hakalink_y);
			
			//计算三角形的中心点
			Map<String,Float> centerMap = computeTrangleCenter(nodeMap1,nodeMap2,nodeMap3);
		    
		    System.out.println(centerMap);
			
			
			Map<String,Object> returnMap = new HashMap<String,Object>(); 
			returnMap.put("photo_url", "http://192.168.206.116:8080/indoor/28F.jpg");
			returnMap.put("x", centerMap.get("x").toString());
			returnMap.put("y", centerMap.get("y").toString());
			//returnMap.put("region", regionMap.get("LAYER_TOPIC"));
			returnMap.put("width", "45");
			returnMap.put("height", "30");
			response.getWriter().write("["+gson.toJson(returnMap)+"]");
			
			}catch(Exception e){
				try {
					response.getWriter().write("error");
				} catch (IOException e1) {
					e1.printStackTrace();
				}
			}
	}
	
	/**
	 * 
	 * 计算两个圆心确定直线与两个圆的交点
	 * 返回介于两个圆心之间的交点，返回的交点可能是一个，或者两个
	 * 
	 */
	public Map<String,Float> computeNodeLocation(float r1,float a1,float b1,float r2,float a2,float b2){
		//System.out.println(r1+"|"+a1+"|"+b1+"|"+r2+"|"+a2+"|"+b2);
		//链表存储由两个圆心组成的直线与两个圆的交点
		List<Map<String,Float>> nodeList = new ArrayList<Map<String,Float>>();
		Map<String,Float> nodeMap1 = new HashMap<String,Float>();
		Map<String,Float> nodeMap2 = new HashMap<String,Float>();
		Map<String,Float> nodeMap3 = new HashMap<String,Float>();
		Map<String,Float> nodeMap4 = new HashMap<String,Float>();
		//计算直线方程，y=kx+b,计算k和b的值
		float k = (b1-b2)/(a1-a2);
		float b = b1-a1*(b1-b2)/(a1-a2);
		//System.out.println(k+"|"+b);
		//计算与圆1的交点
		//y=kx+b带入圆1的方程得到一元二次方程,求解方程为
		float r1_f = (2*a1-2*k*b+2*k*b1)*(2*a1-2*k*b+2*k*b1)-4*(k*k+1)*(a1*a1+b*b-2*b*b1+b1*b1-r1*r1);
		
		float r1_x1 = (float) ((2*a1+2*k*b1-2*k*b+Math.sqrt(r1_f))/(2*(k*k+1)));
		float r1_x2 = (float) ((2*a1+2*k*b1-2*k*b-Math.sqrt(r1_f))/(2*(k*k+1)));
		float r1_y1 = k*r1_x1+b;
		float r1_y2 = k*r1_x2+b;
		nodeMap1.put("x", r1_x1);
		nodeMap1.put("y", r1_y1);
		nodeList.add(nodeMap1);//第一个交点
		nodeMap2.put("x", r1_x2);
		nodeMap2.put("y", r1_y2);
		nodeList.add(nodeMap2);//第二个交点
		
		//y=kx+b带入圆2的方程得到一元二次方程,求解方程为
		float r2_f = (2*a2-2*k*b+2*k*b2)*(2*a2-2*k*b+2*k*b2)-4*(k*k-1)*(a2*a2+b*b-2*b*b2+b2*b2-r2*r2);
		
		float r2_x1 = (float) ((2*a2+2*k*b2-2*k*b+Math.sqrt(r2_f))/(2*(k*k+1)));
		float r2_x2 = (float) ((2*a2+2*k*b2-2*k*b-Math.sqrt(r2_f))/(2*(k*k+1)));
		float r2_y1 = k*r2_x1+b;
		float r2_y2 = k*r2_x2+b;
		
		nodeMap3.put("x", r2_x1);
		nodeMap3.put("y", r2_y1);
		nodeList.add(nodeMap3);//第一个交点
		nodeMap4.put("x", r2_x2);
		nodeMap4.put("y", r2_y2);
		nodeList.add(nodeMap4);//第二个交点
		float min_x = 0;//交点最小x值
		float max_x = 0;//交点最大x值
		float min_y = 0;//交点最小y值
		float max_y = 0;//交点最大y值
		if(a1 > a2){
			min_x = a2;
			max_x = a1;
		}else{
			min_x = a1;
			max_x = a2;
		}
		if(b1 > b2){
			min_y = b2;
			max_y = b1;
		}else{
			min_y = b1;
			max_y = b2;
		}
		
		List<Map<String,Float>> returnList = new ArrayList<Map<String,Float>>();
		
		//System.out.println(nodeList);
		//得到介于圆心之间的交点
		for(int i = 0;i<nodeList.size();i++){
			Map<String,Float> map = nodeList.get(i);
			float x = map.get("x");
			float y = map.get("y");
			if(x >= min_x && x <= max_x && y >= min_y && y <= max_y){
				//交点在圆心之间
				returnList.add(map);
			}
		}
		//System.out.println(returnList);
		Map<String,Float> returnMap = computeTwoPointCenter(returnList);
		return returnMap;
	}
	/**
	 * 计算两个坐标点的中心点
	 * list长度可能等于1,或者2
	 * 如果长度=1，则返回当前点，如果长度等于2或者大于2，则返回前两个点的中心点
	 */
	public Map<String,Float> computeTwoPointCenter(List<Map<String,Float>> list){
		Map<String,Float> returnMap = new HashMap<String,Float>();//返回的map
		if(list == null){
			//空值
			return null;
		}else if(list != null && list.size() == 1){
			//只有一个点
			returnMap = list.get(0);
			return returnMap;
		}else{
			float x = (list.get(0).get("x")+list.get(1).get("x"))/2;
			float y = (list.get(0).get("y")+list.get(1).get("y"))/2;
			returnMap.put("x", x);
			returnMap.put("y", y);
			return returnMap;
		}
		
	}
	/**
	 * 计算三角形的中心点（临时计算中心点算法）
	 * 首先取一边的中点，然后计算此中点与另外一个点的中心点
	 */
	public Map<String,Float> computeTrangleCenter(Map<String,Float> map1,Map<String,Float> map2,Map<String,Float> map3){
		float twoNodeCenter_x = (map1.get("x")+map2.get("x"))/2;
		float twoNodeCenter_y = (map1.get("y")+map2.get("y"))/2;
		
		float thireNodeCenter_x = (twoNodeCenter_x+map3.get("x"))/2;
		float thireNodeCenter_y = (twoNodeCenter_y+map3.get("y"))/2;
		
		Map<String,Float> returnMap = new HashMap<String,Float>();
		returnMap.put("x", thireNodeCenter_x);
		returnMap.put("y", thireNodeCenter_y);
		return returnMap;
	}
	/**
     *得到以信号源mac为key的MAP
     *
     */
    public Map<String,Object> getSignalMapByList(List<HashMap<String,String>> signalList){
    	Map<String,Object> signalMap = new HashMap<String,Object>();
    	if(signalList != null){
    		for(int i = 0;i<signalList.size();i++){
    			signalMap.put((String) signalList.get(i).get("SSID"), signalList.get(i).get("LEVEL"));
    		}
    	}
    	return signalMap;
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

	public void setSignalRegionCenterDao(SignalRegionCenterDao signalRegionCenterDao) {
		this.signalRegionCenterDao = signalRegionCenterDao;
	}
	
}
