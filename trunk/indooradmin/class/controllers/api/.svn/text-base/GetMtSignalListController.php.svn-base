
<?php
/**
 * 获取移动终端信号坐标栅格周期集合数据接口
 * @author chao.xj
 */
class GetMtSignalListController extends ApiController {
	
	
	function actionIndex() {
		$message = "";
		$data = array();
		$jsonArrayObj = $_GET ["jsonArrayObj"];
		//测试数据数组
// 		$jsonArrayObj = json_encode ( array (
// 				"BUILDING_ID" => 1,
// 				"FLOOR_ID" => 11111 
// 		) );
		if (! empty ( $jsonArrayObj )) {
			try {
				$jsonArrayObj = json_decode ( $jsonArrayObj );
				$msg = $jsonArrayObj;
				SF::log($msg);
			} catch ( Exception $e ) {
				$message = "解析参数出错！";
				if (! empty ( $e )) {
					$message = $message . "catch error:" . $e->getMessage ();
				}
			}
			if (empty ( $message )) {
				//根据场所ID和楼层ID来获取楼层平面图信息
				if($jsonArrayObj->BUILDING_ID){
					$data ['BUILDING_ID'] = $jsonArrayObj->BUILDING_ID;
					
					SF::log("jsonArrayObj->BUILDING_ID===".$jsonArrayObj->BUILDING_ID);
				}else {
					$message = "BUILDING_ID属性值为空！";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->FLOOR_ID){
					$data ['FLOOR_ID']  = $jsonArrayObj->FLOOR_ID;
					SF::log("jsonArrayObj->FLOOR_ID===".$jsonArrayObj->FLOOR_ID);
				}else {
					$message = "FLOOR_ID属性值为空！";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->DRAW_MAP_ID){
					$data ['DRAW_MAP_ID']  = $jsonArrayObj->DRAW_MAP_ID;
					SF::log("jsonArrayObj->DRAW_MAP_ID===".$jsonArrayObj->DRAW_MAP_ID);
				}else {
					$message = "DRAW_MAP_ID属性值为空！";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->PLANE_X){
					$data ['PLANE_X']  = $jsonArrayObj->PLANE_X;
					SF::log("jsonArrayObj->PLANE_X===".$jsonArrayObj->PLANE_X);
				}else {
					$message = "PLANE_X属性值为空！";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->PLANE_Y){
					$data ['PLANE_Y']  = $jsonArrayObj->PLANE_Y;
					SF::log("jsonArrayObj->PLANE_Y===".$jsonArrayObj->PLANE_Y);
				}else {
					$message = "PLANE_Y属性值为空！";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->SIGNAL_TYPE){
					$data ['SIGNAL_TYPE']  = $jsonArrayObj->SIGNAL_TYPE;
					SF::log("jsonArrayObj->SIGNAL_TYPE===".$jsonArrayObj->SIGNAL_TYPE);
				}else {
					$message = "SIGNAL_TYPE属性值为空！";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->START_DATE){
					$data ['START_DATE']  = $jsonArrayObj->START_DATE;
					SF::log("jsonArrayObj->START_DATE===".$jsonArrayObj->START_DATE);
				}else {
					$message = "START_DATE属性值为空！";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->END_DATE){
					$data ['END_DATE']  = $jsonArrayObj->END_DATE;
					SF::log("jsonArrayObj->END_DATE===".$jsonArrayObj->END_DATE);
				}else {
					$message = "END_DATE属性值为空！";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->WIDTH){
					$data ['WIDTH']  = $jsonArrayObj->WIDTH;
					SF::log("jsonArrayObj->WIDTH===".$jsonArrayObj->WIDTH);
				}else {
					$message = "WIDTH属性值为空！";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->HEIGHT){
					$data ['HEIGHT']  = $jsonArrayObj->HEIGHT;
					SF::log("jsonArrayObj->HEIGHT===".$jsonArrayObj->HEIGHT);
				}else {
					$message = "HEIGHT属性值为空！";
					$this->renderErrorJson($message);
				}
			}
			try {
				//先确定点坐标归属方格区域
				$x = $jsonArrayObj->PLANE_X;
				$y = $jsonArrayObj->PLANE_Y;
				$length = $jsonArrayObj->WIDTH;
				$width = $jsonArrayObj->HEIGHT;
				//长分10格
				$m = 10;
				//宽分10格
				$n = 10;
				//单元方格长
				$grid_len = $length/$m;
				//单元方格宽
				$grid_wid = $width/$n;
				//确定矩阵栅格列
				$col = floor($x/$grid_len);
				//确定矩阵栅格行
				$row = floor($y/$grid_wid);
				//左上
				$grid_ltx = $grid_len*$col;
				$grid_lty = $grid_wid*$row;
				//右下
				$grid_rbx = $grid_len*($col+1);
				$grid_rby = $grid_wid*($row+1);
				//区域确定结束
				$data ['GRID_LTX'] = $grid_ltx;
				$data ['GRID_LTY'] = $grid_lty;
				$data ['GRID_RBX'] = $grid_rbx;
				$data ['GRID_RBY'] = $grid_rby;
				SF::log("左上区域$grid_ltx  和  $grid_lty  -------右下区域$grid_rbx 和  $grid_rby");
				$cdaocb_mtsignal = new CDAOCB_MTSIGNAL();
				$result = $cdaocb_mtsignal->getMtSignalData($data);
				
				$this->renderSuccessJson ( $this->arrayToJson ( $result ) );
			} catch (Exception $e) {
				SF::log($e);
				$message = "移动终端图层栅格化信号测量数据输出数据库出错......！";
				$this->renderErrorJson($message);
			}
		
		
		} else {
			$message = "参数为空！";
		}
		$this->renderErrorJson ( $message );
	}
	
   
}
?>