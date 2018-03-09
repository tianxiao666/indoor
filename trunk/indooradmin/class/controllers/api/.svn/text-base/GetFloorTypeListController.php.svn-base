<?php
/**
 * 获取楼层类型
 * 
 */
class GetFloorTypeListController extends ApiController {
	function actionIndex() {
		$message = "";
		//取后台定义的楼层类型
		$cdict = new CDict ();
		$FloorType = $cdict->FLOOR_TYPE;
		$FloorTypeList = array ();
		if (! empty ( $FloorType )) {
			//生成返回数组
			foreach ( $FloorType as $k => $v ) {
				$nextType = array (
						"FLOOR_TYPE" => $k,
						"FLOOR_TYPE_NAME" =>$v
				);
				array_push ( $FloorTypeList, $nextType );
			}
			//生成返回数据
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