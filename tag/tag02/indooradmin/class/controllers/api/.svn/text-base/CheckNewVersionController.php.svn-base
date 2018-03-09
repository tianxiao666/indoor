<?php
/**
 * 获取新版本接口
 */
class CheckNewVersionController extends ApiController {
	public function actionIndex() {
		$message = "";
		$jsonArrayObj = $_GET ["jsonArrayObj"];
		//测试数据数组
// 		$jsonArrayObj = json_encode ( array (
// 				"VersionName" => 1,
// 				"VersionCode" => 0 
// 		) );
		if (! empty ( $jsonArrayObj )) {
			try {
				$jsonArrayObj = json_decode ( $jsonArrayObj );
			} catch ( Exception $e ) {
				$message = "解析参数出错！";
				if (! empty ( $e )) {
					$message = $message . "catch error:" . $e->getMessage ();
				}
			}
			if (empty ( $message )) {
				$updateApkPath = SF_ROOT . "apk/IndoorMap.apk";
				$updateInfPath = SF_ROOT . "apk/IndoorMapUpdate.inf";
				if (file_exists ( $updateApkPath ) && file_exists ( $updateInfPath )) {//检测跟新文件是否存在
					$fp = fopen ( $updateInfPath, "rb" );
					$NewVersionInfo = fread ( $fp, filesize ( $updateInfPath ) );
					fclose ( $fp );
					//解析最新版本信息
					$NewVersionInfo = json_decode ( $NewVersionInfo );
					if ($NewVersionInfo->VersionCode > $jsonArrayObj->VersionCode) {//检测是否需要更新，
						$NewVersionInfo = get_object_vars ( $NewVersionInfo );
						$NewVersionInfo ["url"] = SF_BASE_URL . "apk/IndoorMap.apk";
						// $NewVersionInfo ["FileSize"] = filesize ( $updateApkPath );
						//生成返回数据
						$content = json_encode ( $NewVersionInfo );
					} else {
						$content = "{}";
					}
					$this->renderSuccessJson ( $content );
					return;
				} else {
					$message = "新版本不存在！";
				}
			}
		} else {
			$message = "参数为空！";
		}
		$this->renderErrorJson ( $message );
	}
}
?>