<?php
define('SF_ROOT', dirname(__FILE__).'/'); 					//定义本项目的物理路径
define('SF_FRAMEWORK_PATH', SF_ROOT.'../sf/SF/'); 	//定义SF_FRAMEWORK_PATH 的路径
define('MEDIALIB_PATH', SF_ROOT.'medialib/'); 				//定义媒体库物理路径

define('SF_BASE_URL', 'http://www.mosns.com:9988/ewish/');	//定义本项目的基本URL
define('MEDIA_SERVER', SF_BASE_URL);						//定义媒体库的URL

/* 其它配置 */
// remove the following line when in production mode
defined('SF_DEBUG') or define('SF_DEBUG',true);
define('ROUT_VAR','r');

/* session 配置 */
define('SESS_KEY_USER', 'u');  		//定义保存用户信息的session 的key
define('SESS_KEY_ADMIN' ,'admin'); 	//定义保存后台用户信息的session 的key
define('SESS_SAVE_PATH', SF_ROOT.'session_file');

/** adodb 配置 **/
global $ADODB_ASSOC_CASE, $ADODB_ACTIVE_CACHESECS, $ADODB_CACHE_DIR;

$ADODB_ASSOC_CASE 		= 1;							// ative record uppercase
$ADODB_ACTIVE_CACHESECS = 8640000;						//set cache be true, and the cache time, 单位秒  100 days
$ADODB_CACHE_DIR 		= SF_ROOT.'adodb_cache';	// adodb 表结构缓存目录

?>