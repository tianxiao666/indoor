package com.dao.common;

import java.sql.ResultSet;
import java.sql.ResultSetMetaData;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.hibernate.HibernateException;
import org.hibernate.SQLQuery;
import org.hibernate.Session;
import org.hibernate.transform.Transformers;
import org.springframework.orm.hibernate3.HibernateCallback;
import org.springframework.orm.hibernate3.HibernateTemplate;

public class BaseDao {
	/**
	 * @author du
	 * 执行查询数据库
	 */
	public List<Map<String,Object>> executeSqlForListMap ( final String sqlString,HibernateTemplate hibernateTemplate ) {
		List<Map<String, Object>> list = hibernateTemplate.executeFind( new HibernateCallback<List<Map<String,Object>>>() {
			public List<Map<String, Object>> doInHibernate(Session session)
					throws HibernateException, SQLException {
				SQLQuery query = session.createSQLQuery(sqlString);
				query.setResultTransformer(Transformers.ALIAS_TO_ENTITY_MAP);
				List<Map<String, Object>> find = query.list();
				return find;
			}
		});
		return list;
	}
	/**
	 * 执行更新、插入、删除等sql
	 * @param sql
	 * @return true or false
	 */
	public Boolean executeSql ( final String sql,HibernateTemplate hibernateTemplate) {
		Boolean execute = hibernateTemplate.execute( new HibernateCallback<Boolean>() {
			public Boolean doInHibernate(Session session) throws HibernateException,
					SQLException {
				SQLQuery query = session.createSQLQuery(sql);
				int executeUpdate = query.executeUpdate();
				boolean flag = executeUpdate > 0 ;
				return flag;
			}
		});
		return execute;
	} 
	/**
	 * @author du
	 * 执行存储过程
	 * proc_name:存储过程名称，包括参数
	 * parList:存储过程参数值(包括值的顺序，值，值类型---分别对应的key值为key,value,type)
	 */
	public List<Map<String, Object>> executeProc (HibernateTemplate hibernateTemplate,final String proc_name,
			final List<Map<String,Object>> parList) {
		List<Map<String, Object>> list = hibernateTemplate.executeFind( new HibernateCallback<List<Map<String,Object>>>() {
			public List<Map<String, Object>> doInHibernate(Session session)
					throws HibernateException, SQLException {
				List<Map<String, Object>> returnList = new ArrayList<Map<String,Object>>();//存储返回的数据
				
				java.sql.Connection connection = session.connection();
				java.sql.CallableStatement prepareCall = connection.prepareCall("{call "+proc_name+"}");
				if(parList != null && parList.size() > 0){
					 for(int i=0;i<parList.size();i++){
						  Map<String,Object> parMap = parList.get(i);
						  int order = parMap.get("order") == null?-1:Integer.parseInt(parMap.get("order").toString());
						  if(order != -1 && parMap.get("type").equals("int")){
							  prepareCall.setInt(order+1, Integer.parseInt(parMap.get("value").toString()));
						  }else if(order != -1 && parMap.get("type").equals("long")){
							  prepareCall.setLong(order+1, Long.parseLong(parMap.get("value").toString()));
						  }else if(order != -1 && parMap.get("type").equals("float")){
							  prepareCall.setFloat(order+1, Float.parseFloat(parMap.get("value").toString()));
						  }else if(order != -1 && parMap.get("type").equals("string")){
							  prepareCall.setString(order+1, parMap.get("value").toString());
						  }
					 }
				}
			  ResultSet rs = prepareCall.executeQuery(); 
				
			   ResultSetMetaData metaData = null;
			   if(rs == null){
				   return null;
			   }else{
				   metaData = rs.getMetaData(); 
			   }

			   int columnCount = metaData.getColumnCount(); 
			   while (rs.next()) {
				   Map<String, Object> map = new HashMap<String, Object>(); 
			       for (int i = 1; i <= columnCount; i++) { 
			    	   map.put(metaData.getColumnName(i), rs.getObject(i)); 
			       }
			       returnList.add(map); 
              } 
			  return returnList;
			}
		});
		return list;
	}
	
	
	
}
