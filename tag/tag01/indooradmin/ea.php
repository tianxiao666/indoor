<?php
//���빫������
//print_r($_SERVER);die();
//echo $_SERVER['HTTP_REFERER'];die();
//phpinfo();die();
//print_r($_SERVER);die();
require_once('define.php'); 
require_once(SF_FRAMEWORK_PATH."SF.php");

$config	= SF_ROOT.'config/admin.php';
$app 	= SF::createWebApplication($config);

// ����session�ļ����·��
//$app->getSession()->setSavePath('2;'.SESS_SAVE_PATH);
$app->getSession()->setSavePath(SESS_SAVE_PATH);
$app->getSession()->open();




$app->defaultController = 'BuildingMgr';
$app->setControllerPath(SF_ROOT.'class/controllers/admin/');
$app->run();
?>
