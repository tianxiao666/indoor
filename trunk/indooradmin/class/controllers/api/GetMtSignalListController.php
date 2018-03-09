
<?php
/**
 * ��ȡ�ƶ��ն��ź�����դ�����ڼ������ݽӿ�
 * @author chao.xj
 */
class GetMtSignalListController extends ApiController {
	
	
	function actionIndex() {
		$message = "";
		$data = array();
		$jsonArrayObj = $_GET ["jsonArrayObj"];
		//������������
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
				}
				if($jsonArrayObj->START_DATE){
					$data ['START_DATE']  = $jsonArrayObj->START_DATE;
					SF::log("jsonArrayObj->START_DATE===".$jsonArrayObj->START_DATE);
				}else {
					$message = "START_DATE����ֵΪ�գ�";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->END_DATE){
					$data ['END_DATE']  = $jsonArrayObj->END_DATE;
					SF::log("jsonArrayObj->END_DATE===".$jsonArrayObj->END_DATE);
				}else {
					$message = "END_DATE����ֵΪ�գ�";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->WIDTH){
					$data ['WIDTH']  = $jsonArrayObj->WIDTH;
					SF::log("jsonArrayObj->WIDTH===".$jsonArrayObj->WIDTH);
				}else {
					$message = "WIDTH����ֵΪ�գ�";
					$this->renderErrorJson($message);
				}
				if($jsonArrayObj->HEIGHT){
					$data ['HEIGHT']  = $jsonArrayObj->HEIGHT;
					SF::log("jsonArrayObj->HEIGHT===".$jsonArrayObj->HEIGHT);
				}else {
					$message = "HEIGHT����ֵΪ�գ�";
					$this->renderErrorJson($message);
				}
			}
			try {
				//��ȷ�������������������
				$x = $jsonArrayObj->PLANE_X;
				$y = $jsonArrayObj->PLANE_Y;
				$length = $jsonArrayObj->WIDTH;
				$width = $jsonArrayObj->HEIGHT;
				//����10��
				$m = 10;
				//���10��
				$n = 10;
				//��Ԫ����
				$grid_len = $length/$m;
				//��Ԫ�����
				$grid_wid = $width/$n;
				//ȷ������դ����
				$col = floor($x/$grid_len);
				//ȷ������դ����
				$row = floor($y/$grid_wid);
				//����
				$grid_ltx = $grid_len*$col;
				$grid_lty = $grid_wid*$row;
				//����
				$grid_rbx = $grid_len*($col+1);
				$grid_rby = $grid_wid*($row+1);
				//����ȷ������
				$data ['GRID_LTX'] = $grid_ltx;
				$data ['GRID_LTY'] = $grid_lty;
				$data ['GRID_RBX'] = $grid_rbx;
				$data ['GRID_RBY'] = $grid_rby;
				SF::log("��������$grid_ltx  ��  $grid_lty  -------��������$grid_rbx ��  $grid_rby");
				$cdaocb_mtsignal = new CDAOCB_MTSIGNAL();
				$result = $cdaocb_mtsignal->getMtSignalData($data);
				
				$this->renderSuccessJson ( $this->arrayToJson ( $result ) );
			} catch (Exception $e) {
				SF::log($e);
				$message = "�ƶ��ն�ͼ��դ���źŲ�������������ݿ����......��";
				$this->renderErrorJson($message);
			}
		
		
		} else {
			$message = "����Ϊ�գ�";
		}
		$this->renderErrorJson ( $message );
	}
	
   
}
?>