<?php
define('SF_ROOT', dirname(__FILE__).'/'); 					//���屾��Ŀ������·��
define('SF_FRAMEWORK_PATH', SF_ROOT.'../../sf/'); 	//����SF_FRAMEWORK_PATH ��·��
define('MEDIALIB_PATH', SF_ROOT.'medialib/'); 				//����ý�������·��

define('SF_BASE_URL', 'http://192.168.243.185:8818/indooradmin/');	//���屾��Ŀ�Ļ���URL
define('SF_WEB_URL', 'http://www.indoor.com/');    //����web��Ŀ�Ļ���URL
define('WAP_URL', 'http://wap.indoor.com/'); 
define('MEDIA_SERVER', SF_BASE_URL);						//����ý����URL

define('WEB_URL','http://192.168.243.185:8818/web');


define('MEDIALIB_PLANE_MAP_AUTO_PATH', MEDIALIB_PATH . 'indoor_plane_map_auto/'); //���徰���Զ����ɵ��ֻ�ͼĿ¼


define ( "LOG_PATH", SF_ROOT . '/tmp/statics_logs/guanli/guanli_');
//��СͼĿ¼��ͷ����W:30px H:30px ������ͼƬ��W:120px H:90px�������3��4�򷴹�������
define('MIN_PATH', 'min/'); 
//�Ƚ�СͼĿ¼��ͷ����W:65px H:65px ������ͼƬ��W:200px H:150px�������3��4�򷴹�������
define('PREVIEWS_PATH', 'previews/'); 

//��ͼĿ¼������ûͷ��ͼƬ��������ͼƬ��W:650px H:650px��������ʱ������IPONE��ANDROID�ֻ�Ҫ��480x320
define('PREVIEWS_MEDIUMS_PATH',  'mediums/');

//��ԭͼĿ¼
define('PREVIEWS_ORG_PATH',  'original/');
//����Ǵ���W:1024px H:1024px�����ѹ��ΪW:1024px H:1024px�������ڵ�ǰĿ¼�¡�

//�ȱȵ�ͼƬĿ¼��270X270,�ֻ�������ͼ
define('EQUAL_PATH', 'equal/'); 




if (!defined( "SF_CLASS_PATH" )) {
           define( "SF_CLASS_PATH", dirname(__FILE__)."/class/");
}
//googel map ��Կ
define('GOOGEL_MAP_MIYAO','ABQIAAAACX7zE-gLA5uJ0wzHIXKjxRQWVmqTbDWoo5Fh840T99ND6ntZkRT_jgBvNy2TUnWUceq1e45iPV3SYw');
// user status active
define('USER_STATUS_ACTIVE','A');

/* �������� */
// remove the following line when in production mode
defined('SF_DEBUG') or define('SF_DEBUG',true);
define('ROUT_VAR','r');

/* session ���� */
define('SESS_KEY_USER', 'u');  		//���屣���û���Ϣ��session ��key
define('SESS_KEY_ADMIN' ,'admin'); 	//���屣���̨�û���Ϣ��session ��key
define('SESS_SAVE_PATH',SF_ROOT.'tmp/session_file');

/** adodb ���� **/
global $ADODB_ASSOC_CASE, $ADODB_ACTIVE_CACHESECS, $ADODB_CACHE_DIR;

$ADODB_ASSOC_CASE 		= 1;							// ative record uppercase
$ADODB_ACTIVE_CACHESECS = 8640000;						//set cache be true, and the cache time, ��λ��  100 days
$ADODB_CACHE_DIR 		= SF_ROOT.'../adodb_cache';	// adodb ��ṹ����Ŀ¼

if (!defined( "SF_CLASS_PATH" )) {
           define( "SF_CLASS_PATH", dirname(__FILE__)."/class/");
}
define('SF_C3DES_IV',"7585203803093751");//�����c3des ivֵ
define('INTERFACE_DEBUG',true);//�Ƿ���Ҫ��֤(trueΪ����֤)
define('INTERFACE_DATA_ENCRYPT',false);//���������Ƿ���Ҫ����
define('MEDIALIB_RELATIVE_PATH', 'medialib/');
define('CEL_PATH', 'cel/');

//����WEB��Ŀ�Ļ���URL
//define('SF_WEB_URL', 'http://www.tripdata.com');
//Ĭ������region_id
define('DEFAULT_ASIA_REGION_ID',3601);

//Ĭ���й�region_id
define('DEFAULT_CHINA_REGION_ID',1);

//JAVAӦ�õ�URL
define('JAVA_URL','http://search.tripdata.com/admin/');

define('FONT_FILE', SF_ROOT.'font/msyh.ttf');//������ʽ�ļ�
define('SEARCH_KML',MEDIALIB_PATH.'search_kml/');
define('GOOGLE_URL','http://search.tripdata.com/admin/');
//define('MEDIALIB_USERPHOTO_PATH', MEDIALIB_PATH . 'itravel_user_photo/'); //����pengyou�û�ͷ���ϴ�Ŀ¼


?>

