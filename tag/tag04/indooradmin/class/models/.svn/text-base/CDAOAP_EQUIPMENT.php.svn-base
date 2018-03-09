<?php

class CDAOAP_EQUIPMENT extends CCDAODM_DRAW_MAP
{
	var $_table = 'AP_EQUIPMENT';	//associated database table
	
	
	
 	/*
   *@author:mary
   *@create_time:2013-07-31
   *通过楼层取得所有 AP
   *
   */
    function getApListByFloorId($floor_id){
        $floor_id = $floor_id?intval($floor_id):0;
        if ($floor_id>0){
        	$sql = "select * from {$this->_table} where floor_id = {$floor_id} ";
        	return $this->DB()->getAll($sql);
        	
        }
       
        
        return empty($result)?array():$result;
     }
	
}


?>
