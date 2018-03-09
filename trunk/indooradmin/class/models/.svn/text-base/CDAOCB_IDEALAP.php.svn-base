<?php
/**
 * 理想AP表操作类
 * @author chao.xj
 */
class CDAOCB_IDEALAP extends CCDAOCB_IDEALAP {
	function __construct() {
		parent::__construct ();
	}
	function getNextSeqId() {
		return ($this->DB ()->nextId ( $this->_cb_ideal_ap_sequence ));
	}
	/**
	 * 保存理想ap信号强度相关信息
	 * @param 键值对 $IdealApInfo
	 * @return 序列ID|boolean
	 */
	function saveIdealApData($IdealApInfo) {
		$IdealApInfo ['IDEAL_AP_ID'] = $this->DB ()->nextId ( $this->_cb_ideal_ap_sequence );
		$IdealApInfo ['MEA_DATE'] = date ( 'Y-m-d H:i:s' );
		$this->setFrom ( $IdealApInfo );
		if ($this->Insert ()) {
			return $IdealApInfo ['IDEAL_AP_ID'];
		}
		return false;
	}
	/**
	 * 获取理想AP分页数据
	 * @param string $whereSql
	 * @param number $pageNum
	 * @param number $pageSize
	 * @return 结果集
	 */
	function getIdeaApPageData($whereSql = '', $pageNum = 1, $pageSize = 10) {
		$sql = "SELECT
		REGION_NAME,BUILDING_NAME,FLOOR_NAME,DM_TOPIC,LONGITUDE,LATITUDE,AP_LEVELS,PLANE_X,PLANE_Y,MEA_DATE,PHONE_DIRECTION
		FROM 
		(
		SELECT T2.REGION_NAME,T1.BUILDING_NAME,T1.FLOOR_NAME,T1.DM_TOPIC,T1.LONGITUDE,T1.LATITUDE,T1.AP_LEVELS,T1.PLANE_X,T1.PLANE_Y,T1.MEA_DATE,T1.PHONE_DIRECTION FROM (
SELECT 
       BU.CITY, 
       BU.BUILDING_NAME,
       FL.FLOOR_NAME,
       DM.DM_TOPIC,
       AP.LONGITUDE,
       AP.LATITUDE,
       AP.AP_LEVELS,
       AP.PLANE_X,
       AP.PLANE_Y,
       AP.MEA_DATE,
       AP.PHONE_DIRECTION
  FROM {$this->_table} AP, {$this->_bu_table} BU, {$this->_fl_table} FL, {$this->_dm_table} DM
 WHERE AP.BUILDING_ID = BU.BUILDING_ID
   AND AP.FLOOR_ID = FL.FLOOR_ID
   AND AP.DRAW_MAP_ID = DM.DRAW_MAP_ID
   ) T1 LEFT JOIN {$this->_re_table} T2 ON  T1.CITY = T2.REGION_ID		
		) 
		";
		if (! empty ( $whereSql )) {
			$sql = $sql . " WHERE " . $whereSql;
		}
		$result = $this->DB ()->PageExecute ( $sql, $pageSize, $pageNum );
		return ($result);
	}
}
?>