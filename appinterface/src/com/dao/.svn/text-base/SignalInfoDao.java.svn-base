package com.dao;

import java.io.Serializable;
import java.util.List;
import java.util.Map;

import org.springframework.orm.hibernate3.HibernateTemplate;

import com.dao.common.BaseDao;




public class SignalInfoDao extends BaseDao{
	private HibernateTemplate hibernateTemplate;
	
	public Map<String,Object> getSignalInfoById(int signal_id){
		String sql = "select * from signal_info where signal_id="+signal_id;
		List<Map<String,Object>> returnList = this.executeSqlForListMap(sql,hibernateTemplate);
		if(returnList!=null){
			return returnList.get(0);
		}else{
			return null;
		}
	}
	
	public boolean addSignalInfo(String mac,String ssid,int intensity,String position,long time_flag){
		String sql = "insert into signal_info (signal_mac,signal_ssid,signal_intensity,pick_time,position_a,time_flag) values ('"+mac+"','"+ssid+"',"+
		   intensity+",sysdate,'"+position+"',"+time_flag+")";
		return this.executeSql(sql, hibernateTemplate);
	}
/**
 * 通过时间标志，得到当前的平均信号
 * @param time_flag
 * @return
 */
	public List<Map<String,Object>> getAverageSignalInfo(long time_flag){
		String sql = "select * from (select sum(signal_intensity)/count(*) average_signal,count(*) num,signal_mac,signal_ssid from signal_info where time_flag="+time_flag+" group by signal_mac,signal_ssid)" +
				" where num > 3";
		List<Map<String,Object>> returnList = this.executeSqlForListMap(sql,hibernateTemplate);
		return returnList;
	}
	
	public HibernateTemplate getHibernateTemplate() {
		return hibernateTemplate;
	}

	public void setHibernateTemplate(HibernateTemplate hibernateTemplate) {
		this.hibernateTemplate = hibernateTemplate;
	}
	
}
