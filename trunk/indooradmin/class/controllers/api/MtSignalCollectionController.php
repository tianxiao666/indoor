
<?php
/**
 * �ƶ��ն��ź�ǿ�Ȳ������ݲɼ��ӿ�
 * @author chao.xj
 */
class MtSignalCollectionController extends ApiController {
	
	function actionIndex() {
		$message = "";
		$data = array();
		$jsonArrayObj = $_GET ["jsonArrayObj"];
		
// 		$jsonArrayObj = $_POST["jsonArrayObj"];
		//������������
		/* $jsonArrayObj = json_encode ( array (
				"BUILDING_ID" => 1,
				"FLOOR_ID" => 11111 
		) ); */
		if (! empty ( $jsonArrayObj )) {
			try {
				$jsonArrayObj = json_decode ( $jsonArrayObj );
				$msg = $jsonArrayObj;
				SF::log($msg);
			} catch ( Exception $e ) {
				$message = "������������";
				if (! empty ( $e )) {
					$message = $message . "catch error:" . $e->getMessage ();
				}
			}
			if (empty ( $message )) {
				//���ݳ���ID��¥��ID����ȡ¥��ƽ��ͼ��Ϣ
				if($jsonArrayObj->BUILDING_ID){
					$data ['BUILDING_ID'] = $jsonArrayObj->BUILDING_ID;
					
					SF::log("jsonArrayObj->BUILDING_ID===".$jsonArrayObj->BUILDING_ID);
				}else {
					$message = "BUILDING_ID����ֵΪ�գ�";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->SIGNAL){
					$data ['SIGNAL']  = $jsonArrayObj->SIGNAL;
					SF::log("jsonArrayObj->SIGNAL===".$jsonArrayObj->SIGNAL);
				}else {
					$message = "SIGNAL����ֵΪ�գ�";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->FLOOR_ID){
					$data ['FLOOR_ID']  = $jsonArrayObj->FLOOR_ID;
					SF::log("jsonArrayObj->FLOOR_ID===".$jsonArrayObj->FLOOR_ID);
				}else {
					$message = "FLOOR_ID����ֵΪ�գ�";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->DRAW_MAP_ID){
					$data ['DRAW_MAP_ID']  = $jsonArrayObj->DRAW_MAP_ID;
					SF::log("jsonArrayObj->DRAW_MAP_ID===".$jsonArrayObj->DRAW_MAP_ID);
				}else {
					$message = "DRAW_MAP_ID����ֵΪ�գ�";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->LONGITUDE){
					$data ['LONGITUDE']  = $jsonArrayObj->LONGITUDE;
					SF::log("jsonArrayObj->LONGITUDE===".$jsonArrayObj->LONGITUDE);
				}else {
					$message = "LONGITUDE����ֵΪ�գ�";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->LATITUDE){
					$data ['LATITUDE']  = $jsonArrayObj->LATITUDE;
					SF::log("jsonArrayObj->LATITUDE===".$jsonArrayObj->LATITUDE);
				}else {
					$message = "LATITUDE����ֵΪ�գ�";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->PLANE_X){
					$data ['PLANE_X']  = $jsonArrayObj->PLANE_X;
					SF::log("jsonArrayObj->PLANE_X===".$jsonArrayObj->PLANE_X);
				}else {
					$message = "PLANE_X����ֵΪ�գ�";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->PLANE_Y){
					$data ['PLANE_Y']  = $jsonArrayObj->PLANE_Y;
					SF::log("jsonArrayObj->PLANE_Y===".$jsonArrayObj->PLANE_Y);
				}else {
					$message = "PLANE_Y����ֵΪ�գ�";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->SIGNAL_TYPE){
					$data ['SIGNAL_TYPE']  = $jsonArrayObj->SIGNAL_TYPE;
					SF::log("jsonArrayObj->SIGNAL_TYPE===".$jsonArrayObj->SIGNAL_TYPE);
				}else {
					$message = "SIGNAL_TYPE����ֵΪ�գ�";
					$this->renderErrorJson($message);
				}if($jsonArrayObj->DEVICE_ID){
					//�ѹؼ���ת��GBK��ʽ�����ݿⱣ��ΪGBK��ʽ
					$DEVICE_ID = iconv ( 'UTF-8', 'GB2312', ($jsonArrayObj->DEVICE_ID) );
					$data ['DEVICE_ID']  = $DEVICE_ID;
					SF::log("jsonArrayObj->DEVICE_ID===".$jsonArrayObj->DEVICE_ID);
				}else {
					$message = "DEVICE_ID����ֵΪ�գ�";
					$this->renderErrorJson($message);
				}
			}
			try {
				$cdaocb_mtsignal	= new CDAOCB_MTSIGNAL();
				$cdaocb_mtsignal->saveMtSignalData($data);
				$message = "�ɼ��ɹ�";
				$this->renderSuccessJson($message);
			} catch (Exception $e) {
				SF::log($e);
				$message = "�ƶ��ն��źŲ�������¼�����ݿ����......��";
				$this->renderErrorJson($message);
			}
		
		
		} else {
			$message = "����Ϊ�գ�";
		}
		$this->renderErrorJson ( $message );
	}
}
?>