<?php

// uncomment the following to define a path alias

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

global $g_app_default_config ;
$g_app_default_config = array(
	'basePath'		=> dirname(__FILE__).DIRECTORY_SEPARATOR.'../class',
	'runtimePath'	=> dirname(__FILE__).DIRECTORY_SEPARATOR.'../tmp/',
	'name'			=> 'Base Web Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	// application :path relate basePath;
	// webroot :path relate index.php
	'import'=>array(
		'application.models.*',
		'application.service.*',
		'application.utils.*',
		'application.controllers.Controller'
	),
 
	// application components
	'components'=>array(
		'log'=>array(
			'class'	=>'CLogRouter',
			'routes'=>array(
				array(
					'class'	=>'CFileLogRoute',
					'levels'=>'error, warning, info'
				),
			),
		),
		'cache'=>array(
             'class'	=>'CMemCache',
              'servers'	=>array(
                  array(
                      'host'	=> '127.0.0.1',
                      'port'	=> 11211,
                      'weight'	=> 100
                  )
              )
          ),
        'session'=>array(
             //'class'		=>'CCacheHttpSession',
             'class'		=> 'CHttpSession',
             'sessionName'	=> 'session name',
          	 'sessionDomain'=> 'session domain',
          	 'autoStart'	=> false
          ),
		 'db'=>array(
			   'class'=>'CADOModel',
//			   'dsn'=>'oci8://myehome_ora:myehome_ora@192.168.2.6/ehome?charset=ZHS16GBK',
//			   'dsn'=>'oci8://myehome2:myehome2@192.168.2.6/ehome?charset=ZHS16GBK'
//			   'dsn'=> 'mysql://haka:haka@192.168.2.6:6666/ewishhome?charset=ZHS16GBK'
			   'dsn'=>'oci8://act_stage:act_stage@192.168.2.6/ehome?persist',
          	   'isDebug' => FALSE, 		// 开启数据库调试信息，是否打印出SQL
			   'memCacheHost'=>array(	// 开启数据库的memcache缓存
					array(
	                 	'host'	=> '127.0.0.1',
	                    'port'	=> 11211,
	                    'weight'=> 100
					)
				)	
			   
			 ),
		 'viewRenderer'=>array(
			   'class'		 =>'CSmartyViewRenderer',
			   'cache_dir'	 =>SF_ROOT.'/tmp/cache',
			   'template_dir'=>SF_ROOT.'templates/front/',
			   'compile_dir' =>SF_ROOT.'/tmp/templateCompile',
			   'tmpl_suffix' =>'html',
			 ),
		 'urlManager'=>array(
			'urlFormat'=>'get',
			),

	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'encoding'	=>'gbk'
	)
	
);
