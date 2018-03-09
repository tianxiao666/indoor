<?php
/**
 * ��ȡ¥������
 * 
 */
class GetFloorTypeListController extends ApiController {
	function actionIndex() {
		$message = "";
		//ȡ��̨�����¥������
		$cdict = new CDict ();
		$FloorType = $cdict->FLOOR_TYPE;
		$FloorTypeList = array ();
		if (! empty ( $FloorType )) {
			//���ɷ�������
			foreach ( $FloorType as $k => $v ) {
				$nextType = array (
						"FLOOR_TYPE" => $k,
						"FLOOR_TYPE_NAME" =>$v
				);
				array_push ( $FloorTypeList, $nextType );
			}
			//���ɷ�������
			$content = $this->arrayToJson ( array (
					"FloorTypeList" => $this->arrayToJson ( $FloorTypeList ) 
			) );
		} else {
			$content = "{}";
		}
		$this->renderSuccessJson ( $content );
		return;
	}
}
?>