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

public class TriangulationLocationAction {
	private SignalInfoDao signalInfoDao;
	private SignalDao signalDao;
	private SignalRegionCenterDao signalRegionCenterDao;
	public void getLocation(){
		HttpServletResponse response = ServletActionContext.getResponse();
		HttpServletRequest request = ServletActionContext.getRequest();
		response.setCharacterEncoding("UTF-8");
		response.setContentType("text/html");
		
		String floor = "28";//楼层
		Map<String,Object> defaultMap = new HashMap<String,Object>();
		
		//defaultMap.put("Iswlan3-loss", 3.2);//每米损耗
		defaultMap.put("Iswlan3-loss", 3);//每米损耗(is.wlan.sdc.201)
		defaultMap.put("iswlan27c-loss", 3);//每米损耗
		defaultMap.put("HakaLink-loss", 3);//每米损耗
		//defaultMap.put("iswlansdc201-loss", 3.2);//每米损耗
		
		//defaultMap.put("Iswlan3-max", -40);//4米信号强度
		defaultMap.put("Iswlan3-max", -55);//4米信号强度(is.wlan.sdc.201)
		defaultMap.put("iswlan27c-max", -35);//4米信号强度
		defaultMap.put("HakaLink-max", -45);//4米信号强度
		//defaultMap.put("iswlansdc201-max", 3.2);//每米损耗
		
		defaultMap.put("Iswlan3-x", 20.1);//
		defaultMap.put("Iswlan3-y", 12.2);//
		//defaultMap.put("iswlansdc201-x", 20.1);//
		//defaultMap.put("iswlansdc201-y", 12.2);//
		defaultMap.put("iswlan27c-x", 37.8);//
		defaultMap.put("iswlan27c-y", 15.8);//
		//defaultMap.put("HakaLink-x", 38.8);//
		//defaultMap.put("HakaLink-y", 2);//
		defaultMap.put("HakaLink-x", 34);//
		defaultMap.put("HakaLink-y", 3);//
		
		
    	try {
			String jsonstr = request.getParameter("jsonArrayObj");
		    jsonstr = jsonstr.replace("'", "");
		    
			Gson gson = new Gson();
			HashMap<String, String> allInfo = gson.fromJson(jsonstr,
					new TypeToken<HashMap<String, String>>() {
					}.getType());
			
			String wifinfoLastjsonstr = allInfo.get("wifiinfolast");
			String wifinfojsonstr = allInfo.get("wifiinfo");
			String steps = allInfo.get("steps");
			String direction = allInfo.get("direction");
			
			List<HashMap<String, String>> wifiinfoLastList = gson.fromJson(wifinfoLastjsonstr,
					new TypeToken<List<HashMap<String, String>>>() {}.getType());
			
			List<HashMap<String, String>> wifiinfoList = gson.fromJson(wifinfojsonstr,
					new TypeToken<List<HashMap<String, String>>>() {}.getType());
			
			//把信号源当前的信号强度列表更改为以信号源ssid为key,信号强度为value的map
			Map<String,Object> signalLastMap =  getSignalMapByList(wifiinfoLastList);
			Map<String,Object> signalMap =  getSignalMapByList(wifiinfoList);
			System.out.println("上一次采集信号:"+signalLastMap);
			System.out.println("本次采集信号:"+signalMap);
			
			//Iswlan3圆
			//float iswlan3 = (float) ((Float.parseFloat(defaultMap.get("Iswlan3-max").toString())- Float.parseFloat(signalMap.get("Iswlan3").toString()))
			//                     /(Float.parseFloat(defaultMap.get("Iswlan3-loss").toString())*10));
			
			//is.wlan.sdc.201圆
			float iswlan3 = (float) ((Float.parseFloat(defaultMap.get("Iswlan3-max").toString())- Float.parseFloat(signalMap.get("is.wlan.sdc.201").toString()))
			                     /(Float.parseFloat(defaultMap.get("Iswlan3-loss").toString())*10));
			
			float iswlan3_r = (float) Math.pow(Math.E, iswlan3)*4;
			//float iswlan3_last = (float) ((Float.parseFloat(defaultMap.get("Iswlan3-max").toString())- Float.parseFloat(signalLastMap.get("Iswlan3").toString()))
            //        /(Float.parseFloat(defaultMap.get("Iswlan3-loss").toString())*10));
			float iswlan3_last = (float) ((Float.parseFloat(defaultMap.get("Iswlan3-max").toString())- Float.parseFloat(signalLastMap.get("is.wlan.sdc.201").toString()))
                    /(Float.parseFloat(defaultMap.get("Iswlan3-loss").toString())*10));

            float iswlan3_last_r = (float) Math.pow(Math.E, iswlan3_last)*4;
			
			float iswlan3_x =  Float.parseFloat(defaultMap.get("Iswlan3-x").toString());
			float iswlan3_y =  Float.parseFloat(defaultMap.get("Iswlan3-y").toString());
			
			//HakaLink圆
			float hakalink = (Float.parseFloat(defaultMap.get("HakaLink-max").toString())- Float.parseFloat(signalMap.get("HakaLink").toString()))
                                  /(Float.parseFloat(defaultMap.get("HakaLink-loss").toString())*10);
			float hakalink_r = (float) Math.pow(Math.E, hakalink)*4;
			float hakalink_last = (Float.parseFloat(defaultMap.get("HakaLink-max").toString())- Float.parseFloat(signalLastMap.get("HakaLink").toString()))
            		/(Float.parseFloat(defaultMap.get("HakaLink-loss").toString())*10);
			float hakalink_last_r = (float) Math.pow(Math.E, hakalink_last)*4;
			
			float hakalink_x =  Float.parseFloat(defaultMap.get("HakaLink-x").toString());
			float hakalink_y =  Float.parseFloat(defaultMap.get("HakaLink-y").toString());
			System.out.println(signalMap);
			//System.out.println(signalLastMap);
			//iswlan27c圆
			float iswlan27c = (Float.parseFloat(defaultMap.get("iswlan27c-max").toString())- Float.parseFloat(signalMap.get("iswlan27c").toString()))
			                       /(Float.parseFloat(defaultMap.get("iswlan27c-loss").toString())*10);
			float iswlan27c_r = (float) Math.pow(Math.E, iswlan27c)*4;
			float iswlan27c_last = (Float.parseFloat(defaultMap.get("iswlan27c-max").toString())- Float.parseFloat(signalLastMap.get("iswlan27c").toString()))
            		/(Float.parseFloat(defaultMap.get("iswlan27c-loss").toString())*10);
			float iswlan27c_last_r = (float) Math.pow(Math.E, iswlan27c_last)*4;
			
			float iswlan27c_x =  Float.parseFloat(defaultMap.get("iswlan27c-x").toString());
			float iswlan27c_y =  Float.parseFloat(defaultMap.get("iswlan27c-y").toString());
			
			System.out.println("上一次计算半径:iswlan3_last_r--"+iswlan3_last_r+"||iswlan27c_last_r--"+iswlan27c_last_r+"||hakalink_last_R--"+hakalink_last_r);
			System.out.println("本次计算半径:iswlan3_r--"+iswlan3_r+"||iswlan27c_r--"+iswlan27c_r+"||hakalink_r--"+hakalink_r);
			
			//开始计算3个圆的交点
			if(checkCircleHaveIntersection(iswlan3_last_r,iswlan3_x,iswlan3_y,
					iswlan27c_last_r,iswlan27c_x,iswlan27c_y)){
				System.out.println("iswlan3_last和iswlan27c_last有交点");
			}
			if(checkCircleHaveIntersection(iswlan3_last_r,iswlan3_x,iswlan3_y,
					hakalink_last_r,hakalink_x,hakalink_y)){
				System.out.println("iswlan3_last和hakalink_last有交点");
			}
			if(checkCircleHaveIntersection(iswlan27c_last_r,iswlan27c_x,iswlan27c_y,
					hakalink_last_r,hakalink_x,hakalink_y)){
				System.out.println("iswlan27c_last和hakalink_last有交点");
			}
			//System.out.println(iswlan27c_r+"||"+iswlan27c_x+"||"+iswlan27c_y+"||"+
			//		hakalink_r+"||"+hakalink_x+"||"+hakalink_y);
			if(checkCircleHaveIntersection(iswlan3_r,iswlan3_x,iswlan3_y,
					iswlan27c_r,iswlan27c_x,iswlan27c_y)){
				System.out.println("iswlan3和iswlan27c有交点");
			}
			if(checkCircleHaveIntersection(iswlan3_r,iswlan3_x,iswlan3_y,
					hakalink_r,hakalink_x,hakalink_y)){
				System.out.println("iswlan3和hakalink有交点");
			}
			if(checkCircleHaveIntersection(iswlan27c_r,iswlan27c_x,iswlan27c_y,
					hakalink_r,hakalink_x,hakalink_y)){
				System.out.println("iswlan27c和hakalink有交点");
			}
			
			List<Map<String,Float>> list1 = computeXYLocation(iswlan3_r,iswlan3_x,iswlan3_y,
					iswlan27c_r,iswlan27c_x,iswlan27c_y);
			List<Map<String,Float>> list2 = computeXYLocation(iswlan3_r,iswlan3_x,iswlan3_y,
					hakalink_r,hakalink_x,hakalink_y);
			//System.out.println(list2);
			List<Map<String,Float>> list3 = computeXYLocation(iswlan27c_r,iswlan27c_x,iswlan27c_y,
					hakalink_r,hakalink_x,hakalink_y);
			
			List<Map<String,Float>> list1_last = computeXYLocation(iswlan3_last_r,iswlan3_x,iswlan3_y,
					iswlan27c_last_r,iswlan27c_x,iswlan27c_y);
			List<Map<String,Float>> list2_last = computeXYLocation(iswlan3_last_r,iswlan3_x,iswlan3_y,
					hakalink_last_r,hakalink_x,hakalink_y);
			//System.out.println(list2);
			List<Map<String,Float>> list3_last = computeXYLocation(iswlan27c_last_r,iswlan27c_x,iswlan27c_y,
					hakalink_last_r,hakalink_x,hakalink_y);
			//Map<String,Float> regionMap = computeCenterNode(list1,null,null);
			System.out.println("上一次iswlan3和iswlan27c的交点:"+list1_last);
			System.out.println("上一次iswlan3和hakalink的交点:"+list2_last);
			System.out.println("上一次iswlan27c和hakalink的交点:"+list3_last);
			
			System.out.println("本次iswlan3和iswlan27c的交点:"+list1);
			System.out.println("本次iswlan3和hakalink的交点:"+list2);
			System.out.println("本次iswlan27c和hakalink的交点:"+list3);
			
			Map<String,Object> returnMap = new HashMap<String,Object>(); 
			Map<String,Object> returnLastMap = new HashMap<String,Object>(); 
			//计算上次的位置
			if(list1_last!=null){
				   //两个圆有交点,开始计算交点的直线公式
				   float r = hakalink_last_r;//半径
				   float r_x = hakalink_x;//圆心
				   float r_y = hakalink_y;//圆心
				   float x0 = list1_last.get(0).get("x");
				   float y0 = list1_last.get(0).get("y");
				   float x1 = list1_last.get(1).get("x");
				   float y1 = list1_last.get(1).get("y");
				   returnLastMap = computeLineCircle(r,r_x,r_y,x0,y0,x1,y1);
				   
			   }else if(list2_last!=null){
				   //两个圆有交点,开始计算交点的直线公式
				   float r = iswlan27c_last_r;//半径
				   float r_x = iswlan27c_x;//圆心
				   float r_y = iswlan27c_y;//圆心
				   float x0 = list2_last.get(0).get("x");
				   float y0 = list2_last.get(0).get("y");
				   float x1 = list2_last.get(1).get("x");
				   float y1 = list2_last.get(1).get("y");
				   returnLastMap = computeLineCircle(r,r_x,r_y,x0,y0,x1,y1);
			   }else if(list3_last!=null){
				   //两个圆有交点,开始计算交点的直线公式
				   float r = iswlan3_last_r;//半径
				   float r_x = iswlan3_x;//圆心
				   float r_y = iswlan3_y;//圆心
				   float x0 = list3_last.get(0).get("x");
				   float y0 = list3_last.get(0).get("y");
				   float x1 = list3_last.get(1).get("x");
				   float y1 = list3_last.get(1).get("y");
				   returnLastMap = computeLineCircle(r,r_x,r_y,x0,y0,x1,y1);
			   }
			//计算本次的位置
		   if(list1!=null){
			   //两个圆有交点,开始计算交点的直线公式
			   float r = hakalink_r;//半径
			   float r_x = hakalink_x;//圆心
			   float r_y = hakalink_y;//圆心
			   float x0 = list1.get(0).get("x");
			   float y0 = list1.get(0).get("y");
			   float x1 = list1.get(1).get("x");
			   float y1 = list1.get(1).get("y");
			   returnMap = computeLineCircle(r,r_x,r_y,x0,y0,x1,y1);
			   
		   }else if(list2!=null){
			   //两个圆有交点,开始计算交点的直线公式
			   float r = iswlan27c_r;//半径
			   float r_x = iswlan27c_x;//圆心
			   float r_y = iswlan27c_y;//圆心
			   float x0 = list2.get(0).get("x");
			   float y0 = list2.get(0).get("y");
			   float x1 = list2.get(1).get("x");
			   float y1 = list2.get(1).get("y");
			   returnMap = computeLineCircle(r,r_x,r_y,x0,y0,x1,y1);
		   }else if(list3!=null){
			   //两个圆有交点,开始计算交点的直线公式
			   float r = iswlan3_r;//半径
			   float r_x = iswlan3_x;//圆心
			   float r_y = iswlan3_y;//圆心
			   float x0 = list3.get(0).get("x");
			   float y0 = list3.get(0).get("y");
			   float x1 = list3.get(1).get("x");
			   float y1 = list3.get(1).get("y");
			   returnMap = computeLineCircle(r,r_x,r_y,x0,y0,x1,y1);
		   }
		  
		  
		   System.out.println("上一次的位置:"+returnLastMap);
		   System.out.println("本次的位置:"+returnMap);
		   System.out.println("共走的步数:"+steps);
		   returnMap.put("photo_url", "http://192.168.243.185:8818/indooradmin/medialib/indoor_plane_map_auto/2/2_stran.png");
			//returnMap.put("region", regionMap.get("LAYER_TOPIC"));
			returnMap.put("width", "45");
			returnMap.put("height", "30");
			List<Map<String,Object>> cicleList = new ArrayList<Map<String,Object>>();
			Map<String,Object> cicleMap1 = new HashMap<String,Object>(); 
			Map<String,Object> cicleMap2 = new HashMap<String,Object>(); 
			Map<String,Object> cicleMap3 = new HashMap<String,Object>(); 
			cicleMap1.put("x", iswlan3_x);
			cicleMap1.put("y", iswlan3_y);
			cicleMap1.put("r", iswlan3_r);
			
			cicleMap2.put("x", iswlan27c_x);
			cicleMap2.put("y", iswlan27c_y);
			cicleMap2.put("r", iswlan27c_r);
			
			cicleMap3.put("x", hakalink_x);
			cicleMap3.put("y", hakalink_y);
			cicleMap3.put("r", hakalink_r);
			
			
			
			Map<String,Object> returnAllMap = new HashMap<String,Object>(); 
			returnAllMap.put("return", returnMap);
			returnAllMap.put("cicle1", cicleMap1);
			returnAllMap.put("cicle2", cicleMap2);
			returnAllMap.put("cicle3", cicleMap3);
			
			response.getWriter().write(gson.toJson(returnAllMap));
			
			}catch(Exception e){
				try {
					response.getWriter().write("error");
				} catch (IOException e1) {
					e1.printStackTrace();
				}
			}
	}
	
	/**
	 * 计算两个圆的交点组成的直线，与另外一个圆的交点
	 * r:第三个圆的半径
	 * r_x,r_y 圆心坐标
	 * x0,y0,x1,y1 直线的两个点
	 */
	public  Map<String,Object> computeLineCircle(float r,float r_x,float r_y,float x0,float y0,float x1,float y1){
		//两个圆有交点,开始计算交点的直线公式
		   Map<String,Object> returnMap = new HashMap<String,Object>();
		   float k = (y0-y1)/(x0-x1);
		   float b = y0-k*x0;
		   //第三个圆与第一个交点的距离
		   float distance0 = computeTwoPointDistance(r_x,r_y,x0,y0);
		   //第三个圆与第二个交点的距离
		   float distance1 = computeTwoPointDistance(r_x,r_y,x1,y1);
		   //System.out.println(distance0+"||"+distance1+"||"+r);
		   if(r < distance0 && r <=distance1){//两个圆的直线与第三个圆没有交点，且距离大于第三个圆的半径
			 
			      if(distance0 < distance1){
			    	    returnMap.put("x", x0);
						returnMap.put("y", y0);
			      }else{
			    	    returnMap.put("x", x1);
					    returnMap.put("y", y1);
			      }
		   }else if(r >= distance0 && r >= distance1){//两个圆的直线与第三个圆没有交点，且距离小于第三个圆的半径
			   if(distance0 < distance1){
		    	    returnMap.put("x", x1);
					returnMap.put("y", y1);
		      }else{
		    	    returnMap.put("x", x0);
				    returnMap.put("y", y0);
		      }
		   }else{
			 
			   //两个圆交点的直线与第三个圆有交点
			   float line_x1 = (float) (((2*r_x+2*k*r_y-2*k*b)+Math.sqrt((2*r_x+2*k*r_y-2*k*b)*(2*r_x+2*k*r_y-2*k*b)-4*(1+k*k)*(r_x*r_x+r_y*r_y-r*r-2*b*r_y+b*b)))/(2*(1+k*k)));
			   float line_x2 = (float) (((2*r_x+2*k*r_y-2*k*b)-Math.sqrt((2*r_x+2*k*r_y-2*k*b)*(2*r_x+2*k*r_y-2*k*b)-4*(1+k*k)*(r_x*r_x+r_y*r_y-r*r-2*b*r_y+b*b)))/(2*(1+k*k)));
			   //System.out.println("line:"+line_x1+"||"+line_x2);
			   if(Math.min(x0, x1) <=line_x1  &&  line_x1<= Math.max(x0, x1)){
		    	   //直线与第三个圆的交点在前两个圆的交点之间
		    	   returnMap.put("x", line_x1);
		    	   returnMap.put("y", k*line_x1+b);
		       }else{
		    	   //直线与第三个圆的交点在前两个圆的交点之间
		    	   returnMap.put("x", line_x2);
		    	   returnMap.put("y", k*line_x2+b);
		       }
		   }
		  // System.out.println("gggg");
		  // System.out.println(returnMap);
		   return returnMap;
	}
	/**
	 * 
	 * 计算两个圆的交点
	 * r1,r2:两个圆的半径
	 * a1,b1:半径为r1的圆心坐标
	 * a2,b2:半径为r2的圆心坐标
	 * 返回两个圆交点的坐标
	 * @return
	 * 设w=(r1*r1-r2*r2-a1*a1+a2*a2-b1*b1+b2*b2+2*b1-2*b2)/(2*a2-2*a1)
	 * 则计算两个圆交点的方程转换为x = wy
	 * 带入方程得到(w*w+1)*y*y - (2*a1*e+2*b1)*y+(a1*a1+b1*b1-r1*r1)=0
	 * 然后根据一元二次方程的求解
	 */
	public List<Map<String,Float>> computeXYLocation(float r1,float a1,float b1,float r2,float a2,float b2){
		//System.out.println(r1+"||"+r2);
		float w = (r1*r1-r2*r2-a1*a1+a2*a2-b1*b1+b2*b2)/(2*a2-2*a1);
		float v = (2*b1-2*b2)/(2*a2-2*a1);
		//System.out.println(r1*r1-r2*r2-a1*a1+a2*a2-b1*b1+b2*b2+2*b1-2*b2);
		//System.out.println(2*a2-2*a1);
		//System.out.println(w);
		//System.out.println(r1+"||"+a1+"||"+b1);
		//float f = (2*a1*w+2*b1)*(2*a1*w+2*b1)-4*(w*w+1)*(a1*a1+b1*b1-r1*r1);
		float f = (2*w*v-2*a1*v-2*b1)*(2*w*v-2*a1*v-2*b1)-4*(v*v+1)*(w*w-2*w*a1+a1*a1+b1*b1-r1*r1);
		if(f < 0){
			  return null;
		}else{
			//System.out.println(r1+"||"+a1+"||"+b1+"||"+r2+"||"+a2+"||"+b2);
				//System.out.println(w +"||"+ v);
				//System.out.println("f"+(2*a1*w+2*b1)*(2*a1*w+2*b1));
				//第一个交点
				float y1 = (float) ((2*a1*v+2*b1-2*w*v+Math.sqrt(f))/(2*(v*v+1)));
				float x1 = w+v*y1;
				Map<String,Float> map1 = new HashMap<String,Float>();
				map1.put("x", x1);
				map1.put("y", y1);
				//第二个焦点
				float y2 = (float) ((2*a1*v+2*b1-2*w*v-Math.sqrt(f))/(2*(v*v+1)));
				float x2 = w+v*y2;
				Map<String,Float> map2 = new HashMap<String,Float>();
				map2.put("x", x2);
				map2.put("y", y2);
				//System.out.println(map1);
				//System.out.println(map2);
				List<Map<String,Float>> returnList = new ArrayList<Map<String,Float>>();
				returnList.add(map1);
				returnList.add(map2);
				return returnList;
		}
	}
	/**
	 * 计算两个坐标点的中心点
	 * 参数list中需要有两个map,每一个map中都存储一个坐标点的经纬度
	 * list长度最小必须为2，当长度大于2的时候，只考虑前两个元素
	 */
	public Map<String,Object> computeTwoPointCenter(List<Map<String,Object>> list){
		if(list == null || list.size() < 2 || list.get(0).get("x").equals("")||list.get(1).get("x").equals("")
				||list.get(0).get("y").equals("")||list.get(1).get("y").equals("")){
			//参数为空，或者列表长度小于2个，或者列表前两个元素中不包含x和y
			return null;
		}else{
			float x1 = Float.parseFloat(list.get(0).get("x").toString());
			float x2 = Float.parseFloat(list.get(1).get("x").toString());
			float y1 = Float.parseFloat(list.get(0).get("y").toString());
			float y2 = Float.parseFloat(list.get(1).get("y").toString());
			float center_x = (x1+x2)/2;
			float center_y = (y1+y2)/2;
			Map<String,Object> returnMap = new HashMap<String,Object>();
			returnMap.put("center_x", center_x);
			returnMap.put("center_y", center_y);
			return returnMap;
		}
	}
	/**
	 * 判断两个圆是否有交点
	 * r1,r2：两个圆的半径
	 * a1,b1:半径为r1的圆心坐标
	 * a2,b2:半径为r2的圆心坐标
	 */
	public boolean checkCircleHaveIntersection(float r1,float a1,float b1,float r2,float a2,float b2){
		//计算两个圆心之间的距离
		float distance = (float) Math.sqrt((a1-a2)*(a1-a2)+(b1-b2)*(b1-b2));
		//System.out.println(distance);
		//System.out.println(r1+"||"+r2);
		float min = Math.abs(r1-r2);
		float max = r1+r2;
		if(min <= distance && max >= distance){
			//有交点
			return true;
		}else{
			//无交点
			return false;
		}
	}
	/**
	 * 计算多个点的中心点
	 */
	public Map<String,Float> computeCenterNode(List<Map<String,Float>> list1,List<Map<String,Float>> list2,
			 List<Map<String,Float>> list3){
		float total_x = 0;
		float total_y = 0;
		int num = 0;
		if(list1 != null){
			if(list1.get(0).get("x") > 0 && list1.get(0).get("y") > 0&& list1.get(0).get("x") < 50
					&& list1.get(0).get("y") < 30){
				num++;
			   total_x = total_x+list1.get(0).get("x");
			   total_y = total_y+list1.get(0).get("y");
			}
			if(list1.get(1).get("x") > 0 && list1.get(1).get("y") > 0&& list1.get(1).get("x") < 50
					&& list1.get(1).get("y") < 30){
				num++;
				total_x = total_x+list1.get(1).get("x");
				total_y = total_y+list1.get(1).get("y");
			}
			
			
		}
		if(list2 != null){
			if(list2.get(0).get("x") > 0 && list2.get(0).get("y") > 0 && list2.get(0).get("x") < 50
					&& list2.get(0).get("y") < 30){
				num++;
				total_x = total_x+list2.get(0).get("x");
				total_y = total_y+list2.get(0).get("y");
			}
			if(list2.get(1).get("x") > 0 && list2.get(1).get("y") > 0 && list2.get(1).get("x") < 50
					&& list2.get(1).get("y") < 30){
				num++;
				total_x = total_x+list2.get(1).get("x");
				total_y = total_y+list2.get(1).get("y");
			}
			
			
		}
		if(list3 != null){
			if(list3.get(0).get("x") > 0 && list3.get(0).get("y") > 0&& list3.get(0).get("x") < 50
					&& list3.get(0).get("y") < 30){
				num++;
				total_x = total_x+list3.get(0).get("x");
				total_y = total_y+list3.get(0).get("y");
			}
			if(list3.get(1).get("x") > 0 && list3.get(1).get("y") > 0&& list3.get(1).get("x") < 50
					&& list3.get(1).get("y") < 30){
				num++;
				total_x = total_x+list3.get(1).get("x");
				total_y = total_y+list3.get(1).get("y");
			}
			
			
		}
		if(num > 0){
			float x = total_x/num;
			float y = total_y/num;
			Map<String,Float>  returnMap = new HashMap<String,Float>();
			returnMap.put("x", x);
			returnMap.put("y", y);
			return returnMap;
		}else{
			return null;
		}
		
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
    /**
     * 计算两个点之间的距离
     * @return
     */
    public float computeTwoPointDistance(float x1,float y1,float x2,float y2){
    	return (float) Math.sqrt((x1-x2)*(x1-x2)+(y1-y2)*(y1-y2));
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
