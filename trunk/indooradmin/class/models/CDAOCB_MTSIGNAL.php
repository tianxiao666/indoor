<?php
/**
 * 移动终端信号表操作类
 * @author chao.xj
 */
class CDAOCB_MTSIGNAL extends CCDAOCB_MTSIGNAL {
	function __construct() {
		parent::__construct ();
	}
	function getNextSeqId() {
		return ($this->DB ()->nextId ( $this->_cb_mt_signal_sequence));
	}
	/**
	 * 保存移动终端信号强度相关信息
	 * @param 键值对 $MtSignalInfo
	 * @return 序列ID|boolean
	 */
	function saveMtSignalData($MtSignalInfo) {
		$MtSignalInfo ['SIGNAL_ID'] = $this->DB ()->nextId ( $this->_cb_mt_signal_sequence );
		$MtSignalInfo ['MEA_DATE'] = date ( 'Y-m-d H:i:s' );
		
		$this->setFrom ( $MtSignalInfo );
		SF::log("front collection data ======".$MtSignalInfo);
		if ($this->Insert ()) {
			return $MtSignalInfo ['SIGNAL_ID'];
		}
		return false;
	}
	/**
	 * 获取移动终端信号强度分页数据
	 * @param string $whereSql
	 * @param number $pageNum
	 * @param number $pageSize
	 * @return 结果集
	 */
	function getMtSignalPageData($whereSql = '', $pageNum = 1, $pageSize = 10) {
		$sql = "SELECT
		REGION_NAME,BUILDING_NAME,FLOOR_NAME,DM_TOPIC,LONGITUDE,LATITUDE,PLANE_X,PLANE_Y,MEA_DATE,SIGNAL_TYPE,SIGNAL,DEVICE_ID
		FROM 
		(
		SELECT T2.REGION_NAME,T1.BUILDING_NAME,T1.FLOOR_NAME,T1.DM_TOPIC,T1.LONGITUDE,T1.LATITUDE,T1.SIGNAL,T1.PLANE_X,T1.PLANE_Y,T1.MEA_DATE,T1.SIGNAL_TYPE,T1.DEVICE_ID FROM (
SELECT 
       BU.CITY, 
       BU.BUILDING_NAME,
       FL.FLOOR_NAME,
       DM.DM_TOPIC,
       MT.LONGITUDE,
       MT.LATITUDE,
       MT.SIGNAL,
       MT.PLANE_X,
       MT.PLANE_Y,
       MT.MEA_DATE,
       MT.SIGNAL_TYPE,
       MT.DEVICE_ID
  FROM {$this->_table} MT, {$this->_bu_table} BU, {$this->_fl_table} FL, {$this->_dm_table} DM
 WHERE MT.BUILDING_ID = BU.BUILDING_ID
   AND MT.FLOOR_ID = FL.FLOOR_ID
   AND MT.DRAW_MAP_ID = DM.DRAW_MAP_ID
   ) T1 LEFT JOIN {$this->_re_table} T2 ON  T1.CITY = T2.REGION_ID		
		) 
		";
		if (! empty ( $whereSql )) {
			$sql = $sql . " WHERE " . $whereSql;
		}
		$result = $this->DB ()->PageExecute ( $sql, $pageSize, $pageNum );
		return ($result);
	}
	/**
	 * 获取移动终端图层栅格信号强度相关信息
	 * @param 键值对 $IdealApInfo
	 * @return 序列ID|boolean
	 */
	function getMtSignalData($MtSignalInfo) {
// 		$MtSignalInfo ['MEA_DATE'] = date ( 'Y-m-d H:i:s' );
		$startDate = $MtSignalInfo ['START_DATE'];
		$endDate = $MtSignalInfo ['END_DATE'];
		$building_id = $MtSignalInfo ['BUILDING_ID'];
		$floor_id = $MtSignalInfo ['FLOOR_ID'];
		$draw_map_id = $MtSignalInfo ['DRAW_MAP_ID'];
		$signal_type = $MtSignalInfo ['SIGNAL_TYPE'];
		$grid_ltx = $MtSignalInfo ['GRID_LTX'];
		$grid_lty = $MtSignalInfo ['GRID_LTY'];
		$grid_rbx = $MtSignalInfo ['GRID_RBX'];
		$grid_rby = $MtSignalInfo ['GRID_RBY'];
		
		$sql = '';
		$innersql = 'select to_char(mea_date,\'yyyy-mm-dd\') mea_date,signal from ' . $this->_table . ' where ';
		$whereSql = " 1=1";
		
		if (! empty ( $building_id )) {
			$whereSql = $whereSql . " AND BUILDING_ID = ".$building_id;
		}
		if (! empty ( $floor_id )) {
			$whereSql = $whereSql . " AND FLOOR_ID = ".$floor_id;
		}
		if (! empty ( $draw_map_id )) {
			$whereSql = $whereSql . " AND DRAW_MAP_ID = ".$draw_map_id;
		}
		if (! empty ( $signal_type )) {
			$whereSql = $whereSql . " AND SIGNAL_TYPE = '".$signal_type."'";
		}
		if (! empty ( $grid_ltx ) && ! empty ( $grid_lty ) && ! empty ( $grid_rbx ) && ! empty ( $grid_rby )) {
			$whereSql = $whereSql . " AND PLANE_X >= " .$grid_ltx . " AND PLANE_Y >= " .$grid_lty . " AND PLANE_X <= " .$grid_rbx . " AND PLANE_Y <= " .$grid_rby;
		}
		if (! empty ( $startDate ) && !empty( $endDate )) {
			$whereSql = $whereSql . " AND mea_date > = to_date('{$startDate} 00:00:00','yyyy-mm-dd hh24:mi:ss') and  mea_date < = to_date('{$endDate} 23:59:59','yyyy-mm-dd hh24:mi:ss')";
		}elseif (! empty ( $startDate ) && empty( $endDate )) {
			$whereSql = $whereSql . " AND mea_date > = to_date('{$startDate} 00:00:00','yyyy-mm-dd hh24:mi:ss')";
		}elseif ( empty ( $startDate ) && !empty( $endDate )) {
			$whereSql = $whereSql . " AND mea_date < = to_date('{$endDate} 23:59:59','yyyy-mm-dd hh24:mi:ss')";
		}
		$innersql = $innersql . $whereSql;
		
		$sql = 'select mea_date,sum(signal)/count(1) signal from ( '.$innersql.' ) group by mea_date';
		$sql = "select * from (".$sql.") ORDER BY mea_date asc";
		$result = $this->DB ()->getAll ( $sql );
		if (! empty ( $result )) {
			return $result;
		}
		return (array ());
	}
}
?>