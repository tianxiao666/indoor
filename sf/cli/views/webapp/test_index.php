<?php
//引入公用配置
require_once('define.php'); 
require_once(SF_FRAMEWORK_PATH."SF.php");

$config	= SF_ROOT.'config/test.php';
$app 	= SF::createWebApplication($config);
$app->run();

?>
