<?php
/**
 * 获取场所类型
 * 
 */
class GetBuildingTypeListController extends ApiController {
	function actionIndex() {
		$message = "";
		//取后台定义的场所类型
		$cdict = new CDict ();
		$BuildingType = $cdict->BUILD_TYPE;
		$BuildingTypeList = array ();
		if (! empty ( $BuildingType )) {
			//生成返回数据数组
			foreach ( $BuildingType as $k => $v ) {
				$nextType = array (
						"BUILD_TYPE" => $k,
						"BUILD_TYPE_NAME" => $v 
				);
				array_push ( $BuildingTypeList, $nextType );
			}
			//生成返回数据
			$content = $this->arrayToJson ( array (
					"BuildingTypeList" => $this->arrayToJson ( $BuildingTypeList ) 
			) );
		} else {
			$content = "{}";
		}
		$this->renderSuccessJson ( $content );
		return;
	}
}
?>