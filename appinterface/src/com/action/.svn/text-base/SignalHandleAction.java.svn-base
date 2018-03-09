package com.action;

import java.io.IOException;
import java.lang.reflect.Type;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
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

public class SignalHandleAction {

	private SignalInfoDao signalInfoDao;
	private SignalDao signalDao;
	private SignalRegionCenterDao signalRegionCenterDao;
    public void signalHandleAction(){
    	HttpServletResponse response = ServletActionContext.getResponse();
		HttpServletRequest request = ServletActionContext.getRequest();
		response.setCharacterEncoding("UTF-8");
		response.setContentType("text/html");
		String floor = "28";//楼层
    	try {
			String jsonstr = request.getParameter("jsonArrayObj");
		    jsonstr = jsonstr.replace("'", "");
		    
			Gson gson = new Gson();
			HashMap<String, String> allInfo = gson.fromJson(jsonstr,
					new TypeToken<HashMap<String, String>>() {
					}.getType());
			//System.out.println(allInfo);
			
			String wifinfojsonstr = allInfo.get("wifiinfo");
			String position = allInfo.get("position");
			
			List<HashMap<String, String>> wifiinfoList = gson.fromJson(wifinfojsonstr,
					new TypeToken<List<HashMap<String, String>>>() {}.getType());
			
		     long time_flag = System.currentTimeMillis();
			if(wifiinfoList!=null){
				for(int i=0;i<wifiinfoList.size();i++){
				   Map<String,String> map = wifiinfoList.get(i);
				   String mac = (String) map.get("MAC");
				   int intensity = Integer.parseInt((String) map.get("LEVEL"));
				   String ssid = (String) map.get("SSID");
				   if(ssid != null || !ssid.equals("")){
				       signalInfoDao.addSignalInfo(mac, ssid, intensity,position,time_flag);
				   }
				}
			}
			//得到刚入库的信号点的平均强度
			List<Map<String,Object>> averageSignalList = signalInfoDao.getAverageSignalInfo(time_flag);
			//把信号源当前的信号强度列表更改为以信号源mac为key,信号强度为value的map
			Map<String,Object> signalMap =  getSignalMapByList(averageSignalList);
			//测试使用
			Map<String,Object> signalSsidMap =  getSignalSsidMapByList(averageSignalList);
			
			List<Map<String,Object>> floorList = signalDao.getRegionListByFloor(floor);
			
			//初始楼层区域的命中数，命中数初始都为0,区域标识为key,命中数为value
			Map<String,Object> floorRegionMap = getFloorRegionMapByList(floorList);
			//通过楼层区域的平均信号强度，以及当前信号强度，判断所在区域命中率
			
			floorRegionMap = computeRegion(floorList,signalMap,floorRegionMap);
			//计算当前信号强度与原区域各信号强度的差值,返回的是区域标识是key,信号强度差值综合为value
			
			System.out.println(signalSsidMap);
			//System.out.println(signalMap);
			System.out.println(floorRegionMap);
			//得到命中率最大的那个区域标识
			String[] matchRegionArray = getRegion(floorRegionMap);
			int matchNum = Integer.parseInt(matchRegionArray[0]);
			String region_id_string = "";
			if(matchNum == 0){
				region_id_string = "0";
				//response.getWriter().write("没有匹配到");
			}else if(matchNum == 1){
				region_id_string = matchRegionArray[1];
				
			}else{//匹配到多个区域
				float signalDiffence = 100000;
				String returnRegion = "0";
				for(int i = 1;i<=matchNum;i++){
					 String region = matchRegionArray[i];
					 
					 List<Map<String,Object>> regionSignalList =  signalDao.getSignalListByFloorAndRegion(floor,Integer.parseInt(region));
					 float diffence = calculatSumDiffence(signalMap,regionSignalList);
					
					 if(diffence <= signalDiffence){//信号强度差越小，匹配度越高
						 
						    Map<String,Object> regionMap = signalRegionCenterDao.getRegionInfoById(Integer.parseInt(matchRegionArray[i]));
							//System.out.println(signalMap);
							if(regionMap != null && regionMap.get("RELA_MAC") != null && !regionMap.get("RELA_MAC").equals("")){
								 //当前区域关联的有信号源,但是采集的信号中没有此信号源，则直接跳出
								 if(signalMap.get(regionMap.get("RELA_MAC").toString()) == null
										 || signalMap.get(regionMap.get("RELA_MAC").toString()).equals("")){
									 
									 continue;
								 }else{
									 signalDiffence = diffence;
									   returnRegion = region;
								 }
							 }else{
								   signalDiffence = diffence;
								   returnRegion = region;
							 }
					 }
				}
				region_id_string = returnRegion;
			}
			
			//response.getWriter().write(region);
			int region_id = Integer.parseInt(region_id_string);
			Map<String,Object> regionMap = signalRegionCenterDao.getRegionInfoById(region_id);
			// System.out.println("ggg"); 
			//System.out.println(regionMap);
			Map<String,Object> returnMap = new HashMap<String,Object>(); 
			returnMap.put("photo_url", "http://192.168.206.116:8080/indoor/28F.jpg");
			returnMap.put("x", regionMap.get("CENTER_X").toString());
			returnMap.put("y", regionMap.get("CENTER_Y").toString());
			returnMap.put("region", regionMap.get("LAYER_TOPIC"));
			returnMap.put("width", "45");
			returnMap.put("height", "30");
		   //System.out.println("["+gson.toJson(returnMap)+"]");
			response.getWriter().write("["+gson.toJson(returnMap)+"]");
			
			}catch(Exception e){
				try {
					response.getWriter().write("error");
				} catch (IOException e1) {
					// TODO Auto-generated catch block
					e1.printStackTrace();
				}
				//response.getWriter().write("当前位置：B区");
			}
    }
    /**
     * 得到命中率最大的区域标识
     * 可能会多个，所以返回的是一个数组
     * 数组第一存放的是匹配的区域个数
     */
    public String[] getRegion(Map<String,Object> floorRegionMap){
    	//String region = "no";
    	int current_num = 0;
    	//找到最大的匹配数
    	for(Map.Entry<String, Object> entry: floorRegionMap.entrySet()) {
    		 int num = Integer.parseInt(entry.getValue().toString());
    		 if(num > current_num){
    			 current_num = num;
    		 }
    	}
    	String[] returnRegion = new String[10];
    	int regionIndex = 0;
    	if(current_num != 0){//匹配到区域
	    	for(Map.Entry<String, Object> entry: floorRegionMap.entrySet()) {
		   		 int num = Integer.parseInt(entry.getValue().toString());
		   		 if(num == current_num){
		   			 regionIndex++;
		   			 returnRegion[regionIndex] = entry.getKey();
		   		 }
	   	     }
	    	returnRegion[0] = String.valueOf(regionIndex);//匹配到的区域个数
    	}else{//未匹配到任何区域
    		returnRegion[0] = "0";
    	}
    	return returnRegion;
    }
    /**
     * 通过当前信号强度，计算区域的命中率
     */
   public Map<String,Object> computeRegion(List<Map<String,Object>> floorList,Map<String,Object> signalMap,
		      Map<String,Object> floorRegionMap){
	   if(floorList != null){
		   for(int i=0;i<floorList.size();i++){
			   Map<String,Object> floorMap = floorList.get(i);
			   float intensity = Float.parseFloat(floorMap.get("SIGNAL_AVERAGE").toString());
			   String mac = floorMap.get("SIGNAL_MAC").toString();
			   String region_id = floorMap.get("REGION_ID").toString();
			   if(signalMap.get(mac) != null){
				   //当前信号源存在原系统中
				   float current_intensity = Float.parseFloat(signalMap.get(mac).toString());
				   if((intensity-8) <= current_intensity && (intensity+8) >= current_intensity){
					   //命中,区域命中数加1
					   int num = Integer.parseInt(floorRegionMap.get(region_id).toString());
					   floorRegionMap.put(region_id, num+1);
				   }
			   }
		   }
	   }
	   return floorRegionMap;
	   
   }
    
    /**
     *得到以楼层区域标识为key的MAP
     *
     */
    public Map<String,Object> getFloorRegionMapByList(List<Map<String,Object>> floorList){
    	Map<String,Object> floorRegionMap = new HashMap<String,Object>();
    	if(floorList != null){
    		for(int i = 0;i<floorList.size();i++){
    			floorRegionMap.put(floorList.get(i).get("REGION_ID").toString(), 0);
    		}
    	}
    	return floorRegionMap;
    }
    /**
     *得到以信号源mac为key的MAP
     *
     */
    public Map<String,Object> getSignalMapByList(List<Map<String,Object>> signalList){
    	Map<String,Object> signalMap = new HashMap<String,Object>();
    	if(signalList != null){
    		for(int i = 0;i<signalList.size();i++){
    			signalMap.put((String) signalList.get(i).get("SIGNAL_MAC"), signalList.get(i).get("AVERAGE_SIGNAL"));
    		}
    	}
    	return signalMap;
    }
    /**
     *得到以信号源mac为key的MAP
     *
     */
    public Map<String,Object> getSignalSsidMapByList(List<Map<String,Object>> signalList){
    	Map<String,Object> signalMap = new HashMap<String,Object>();
    	if(signalList != null){
    		for(int i = 0;i<signalList.size();i++){
    			signalMap.put((String) signalList.get(i).get("SIGNAL_SSID"), signalList.get(i).get("AVERAGE_SIGNAL"));
    		}
    	}
    	return signalMap;
    }
    /**
     * 得到当前信号源信号强度与原信号强度的差值综合
     * @return
     */
    public float calculatSumDiffence(Map<String,Object> signalMap,List<Map<String,Object>> signalList){
    	if(signalList != null){
    		float sumDiffence = 0;//初始差值
    		for(int i=0;i<signalList.size();i++){
    			Map<String,Object> oneSignalMap = signalList.get(i);
    			String mac = oneSignalMap.get("SIGNAL_MAC").toString();
    			float signal_average = Float.parseFloat(oneSignalMap.get("SIGNAL_AVERAGE").toString());
    			float current_signal = -100;//初始没有找到信号源时的信号强度
    			if(signalMap.get(mac) != null && !signalMap.get(mac).equals("")){//采集的信号中有区域记录的信号
    				 current_signal = Float.parseFloat(signalMap.get(mac).toString());
    				
    			}
    			//System.out.println(mac+"||"+current_signal+"||"+signal_average);
    			sumDiffence += Math.abs(current_signal-signal_average);
    		}
    		return sumDiffence;
    	}else{
    		return 0;
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
	public void setSignalRegionCenterDao(SignalRegionCenterDao signalRegionCenterDao) {
		this.signalRegionCenterDao = signalRegionCenterDao;
	}
	
	
	

}
