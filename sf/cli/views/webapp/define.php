<?php
define('SF_ROOT', dirname(__FILE__).'/'); 					//���屾��Ŀ������·��
define('SF_FRAMEWORK_PATH', SF_ROOT.'../sf/SF/'); 	//����SF_FRAMEWORK_PATH ��·��
define('MEDIALIB_PATH', SF_ROOT.'medialib/'); 				//����ý�������·��

define('SF_BASE_URL', 'http://www.mosns.com:9988/ewish/');	//���屾��Ŀ�Ļ���URL
define('MEDIA_SERVER', SF_BASE_URL);						//����ý����URL

/* �������� */
// remove the following line when in production mode
defined('SF_DEBUG') or define('SF_DEBUG',true);
define('ROUT_VAR','r');

/* session ���� */
define('SESS_KEY_USER', 'u');  		//���屣���û���Ϣ��session ��key
define('SESS_KEY_ADMIN' ,'admin'); 	//���屣���̨�û���Ϣ��session ��key
define('SESS_SAVE_PATH', SF_ROOT.'session_file');

/** adodb ���� **/
global $ADODB_ASSOC_CASE, $ADODB_ACTIVE_CACHESECS, $ADODB_CACHE_DIR;

$ADODB_ASSOC_CASE 		= 1;							// ative record uppercase
$ADODB_ACTIVE_CACHESECS = 8640000;						//set cache be true, and the cache time, ��λ��  100 days
$ADODB_CACHE_DIR 		= SF_ROOT.'adodb_cache';	// adodb ��ṹ����Ŀ¼

?>