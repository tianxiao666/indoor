package com.dao;

import java.util.List;
import java.util.Map;

import org.springframework.orm.hibernate3.HibernateTemplate;

import com.dao.common.BaseDao;




public class SignalRegionCenterDao extends BaseDao{
	private HibernateTemplate hibernateTemplate;
	/**
	 * 通过区域标识得到区域信息
	 * @param region_id
	 * @return
	 */
	public Map<String,Object> getRegionInfoById(int region_id){
		String sql = "select * from signal_region_center where region_id="+region_id;
		List<Map<String,Object>> returnList = this.executeSqlForListMap(sql,hibernateTemplate);
		if(returnList!=null){
			return returnList.get(0);
		}else{
			return null;
		}
	}
	
	
	public HibernateTemplate getHibernateTemplate() {
		return hibernateTemplate;
	}

	public void setHibernateTemplate(HibernateTemplate hibernateTemplate) {
		this.hibernateTemplate = hibernateTemplate;
	}
	
}
