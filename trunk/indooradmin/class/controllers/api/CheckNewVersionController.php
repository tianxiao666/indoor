<?php
/**
 * ��ȡ�°汾�ӿ�
 */
class CheckNewVersionController extends ApiController {
	public function actionIndex() {
		$message = "";
		$jsonArrayObj = $_GET ["jsonArrayObj"];
		// ������������
		// $jsonArrayObj = json_encode ( array (
		// "VersionName" => 1,
		// "VersionCode" => 0
		// ) );
		if (! empty ( $jsonArrayObj )) {
			try {
				$jsonArrayObj = json_decode ( $jsonArrayObj );
			} catch ( Exception $e ) {
				$message = "������������";
				if (! empty ( $e )) {
					$message = $message . "catch error:" . $e->getMessage ();
				}
			}
			if (empty ( $message )) {
				$updateApkPath = SF_ROOT . "apk/IndoorMap.apk";
				$updateInfPath = SF_ROOT . "apk/IndoorMapUpdate.ini";
				if (file_exists ( $updateApkPath ) && file_exists ( $updateInfPath )) { // �������ļ��Ƿ����
					$fp = fopen ( $updateInfPath, "rb" );
					$NewVersionInfo = fread ( $fp, filesize ( $updateInfPath ) );
					fclose ( $fp );
					$NewVersionInfoList = explode ( "\r\n", $NewVersionInfo );
					// �������°汾��Ϣ
					$NewVersionInfo = array ();
					if ($NewVersionInfoList != null) {
						foreach ( $NewVersionInfoList as $v ) {
							$pair = trim ( $v );
							$pairlist = explode ( "=", $pair );
							if ((! empty ( $pairlist )) && is_array ( $pairlist ) && (count ( $pairlist ) == 2)) {
								$NewVersionInfo [trim ( $pairlist [0] )] = trim ( $pairlist [1] );
							}
						}
					}
					$NewVersionInfo = json_decode ( json_encode ( $NewVersionInfo ) );
					if ($NewVersionInfo->VersionCode > $jsonArrayObj->VersionCode) { // ����Ƿ���Ҫ���£�
						$NewVersionInfo = get_object_vars ( $NewVersionInfo );
						$NewVersionInfo ["url"] = SF_BASE_URL . "apk/IndoorMap.apk";
						// $NewVersionInfo ["FileSize"] = filesize ( $updateApkPath );
						// ���ɷ�������
						$content = json_encode ( $NewVersionInfo );
					} else {
						$content = "{}";
					}
					$this->renderSuccessJson ( $content );
					return;
				} else {
					$message = "�°汾�����ڣ�";
				}
			}
		} else {
			$message = "����Ϊ�գ�";
		}
		$this->renderErrorJson ( $message );
	}
}
?>