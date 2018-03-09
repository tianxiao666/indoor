
<?php
/**
 * 理想AP测量数据采集接口
 * @author chao.xj
 */
class IdealApCollectionController extends ApiController {
	
	
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
				if($jsonArrayObj->AP_LEVELS){
					$data ['AP_LEVELS']  = $jsonArrayObj->AP_LEVELS;
					SF::log("jsonArrayObj->AP_LEVELS===".$jsonArrayObj->AP_LEVELS);
				}else {
					$message = "AP_LEVELS属性值为空！";
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
				if($jsonArrayObj->LONGITUDE){
					$data ['LONGITUDE']  = $jsonArrayObj->LONGITUDE;
					SF::log("jsonArrayObj->LONGITUDE===".$jsonArrayObj->LONGITUDE);
				}else {
					$message = "LONGITUDE属性值为空！";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->LATITUDE){
					$data ['LATITUDE']  = $jsonArrayObj->LATITUDE;
					SF::log("jsonArrayObj->LATITUDE===".$jsonArrayObj->LATITUDE);
				}else {
					$message = "LATITUDE属性值为空！";
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
				if($jsonArrayObj->Derection){
					$data ['PHONE_DIRECTION']  = $jsonArrayObj->Derection;
					SF::log("jsonArrayObj->Derection===".$jsonArrayObj->Derection);
				}else {
					$message = "Derection属性值为空！";
					$this->renderErrorJson($message);
				}
			}
			try {
				$cdaocb_idealap	= new CDAOCB_IDEALAP();
				$cdaocb_idealap->saveIdealApData($data);
				$message = "采集成功";
				$this->renderSuccessJson($message);
			} catch (Exception $e) {
				SF::log($e);
				$message = "理想AP测量数据录入数据库出错......！";
				$this->renderErrorJson($message);
			}
		
		
		} else {
			$message = "参数为空！";
		}
		$this->renderErrorJson ( $message );
	}
}
?>