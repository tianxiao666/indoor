package com.dao;


import java.util.List;
import java.util.Map;
import org.springframework.orm.hibernate3.HibernateTemplate;
import com.dao.common.BaseDao;
public class SignalDao extends BaseDao{
	private HibernateTemplate hibernateTemplate;
	
	/**
	 * 通过楼层得到楼层的所有区域
	 * @return
	 */
	public List<Map<String,Object>> getRegionListByFloor(String floor){
		String sql = "select * from signal where floor='"+floor+"'";
		List<Map<String,Object>> returnList = this.executeSqlForListMap(sql,hibernateTemplate);
		return returnList;
	}
	/**
	 * 通过楼层和区域得到关联的信号源
	 * @return
	 */
	public List<Map<String,Object>> getSignalListByFloorAndRegion(String floor,int region_id){
		String sql = "select * from signal where floor='"+floor+"' and region_id='"+region_id+"'";
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
