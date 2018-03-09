<?php
define('SF_ROOT', dirname(__FILE__).'/'); 					//定义本项目的物理路径
define('SF_FRAMEWORK_PATH', SF_ROOT.'../../sf/'); 	//定义SF_FRAMEWORK_PATH 的路径
define('MEDIALIB_PATH', SF_ROOT.'medialib/'); 				//定义媒体库物理路径

define('SF_BASE_URL', 'http://192.168.243.185:8818/indooradmin/');	//定义本项目的基本URL
define('SF_WEB_URL', 'http://www.indoor.com/');    //定义web项目的基本URL
define('WAP_URL', 'http://wap.indoor.com/'); 
define('MEDIA_SERVER', SF_BASE_URL);						//定义媒体库的URL

define('WEB_URL','http://192.168.243.185:8818/web');


define('MEDIALIB_PLANE_MAP_AUTO_PATH', MEDIALIB_PATH . 'indoor_plane_map_auto/'); //定义景区自动生成的手绘图目录


define ( "LOG_PATH", SF_ROOT . '/tmp/statics_logs/guanli/guanli_');
//最小图目录（头像是W:30px H:30px 其他的图片是W:120px H:90px，如果是3：4则反过来。）
define('MIN_PATH', 'min/'); 
//比较小图目录（头像是W:65px H:65px 其他的图片是W:200px H:150px，如果是3：4则反过来。）
define('PREVIEWS_PATH', 'previews/'); 

//中图目录（这里没头像图片，其他的图片是W:650px H:650px），新闻时产生，IPONE、ANDROID手机要高480x320
define('PREVIEWS_MEDIUMS_PATH',  'mediums/');

//即原图目录
define('PREVIEWS_ORG_PATH',  'original/');
//如果是大于W:1024px H:1024px则进行压缩为W:1024px H:1024px，保存在当前目录下。

//等比的图片目录，270X270,手机的缩略图
define('EQUAL_PATH', 'equal/'); 




if (!defined( "SF_CLASS_PATH" )) {
           define( "SF_CLASS_PATH", dirname(__FILE__)."/class/");
}
//googel map 密钥
define('GOOGEL_MAP_MIYAO','ABQIAAAACX7zE-gLA5uJ0wzHIXKjxRQWVmqTbDWoo5Fh840T99ND6ntZkRT_jgBvNy2TUnWUceq1e45iPV3SYw');
// user status active
define('USER_STATUS_ACTIVE','A');

/* 其它配置 */
// remove the following line when in production mode
defined('SF_DEBUG') or define('SF_DEBUG',true);
define('ROUT_VAR','r');

/* session 配置 */
define('SESS_KEY_USER', 'u');  		//定义保存用户信息的session 的key
define('SESS_KEY_ADMIN' ,'admin'); 	//定义保存后台用户信息的session 的key
define('SESS_SAVE_PATH',SF_ROOT.'tmp/session_file');

/** adodb 配置 **/
global $ADODB_ASSOC_CASE, $ADODB_ACTIVE_CACHESECS, $ADODB_CACHE_DIR;

$ADODB_ASSOC_CASE 		= 1;							// ative record uppercase
$ADODB_ACTIVE_CACHESECS = 8640000;						//set cache be true, and the cache time, 单位秒  100 days
$ADODB_CACHE_DIR 		= SF_ROOT.'../adodb_cache';	// adodb 表结构缓存目录

if (!defined( "SF_CLASS_PATH" )) {
           define( "SF_CLASS_PATH", dirname(__FILE__)."/class/");
}
define('SF_C3DES_IV',"7585203803093751");//处理的c3des iv值
define('INTERFACE_DEBUG',true);//是否需要验证(true为不验证)
define('INTERFACE_DATA_ENCRYPT',false);//返回数据是否需要加密
define('MEDIALIB_RELATIVE_PATH', 'medialib/');
define('CEL_PATH', 'cel/');

//定义WEB项目的基本URL
//define('SF_WEB_URL', 'http://www.tripdata.com');
//默认亚洲region_id
define('DEFAULT_ASIA_REGION_ID',3601);

//默认中国region_id
define('DEFAULT_CHINA_REGION_ID',1);

//JAVA应用的URL
define('JAVA_URL','http://search.tripdata.com/admin/');

define('FONT_FILE', SF_ROOT.'font/msyh.ttf');//字体样式文件
define('SEARCH_KML',MEDIALIB_PATH.'search_kml/');
define('GOOGLE_URL','http://search.tripdata.com/admin/');
//define('MEDIALIB_USERPHOTO_PATH', MEDIALIB_PATH . 'itravel_user_photo/'); //定义pengyou用户头像上传目录


?>

