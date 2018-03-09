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
		'application.common.*',
		'application.controllers.Controller',
		'system.models.*',
		'system.utils.*',
	),
 
	// application components
	'components'=>array(
		'log'=>array(
			//'class'	=>'CLogRouter',
			'class'	=>'CLogAppCompo',
			'routes'=>array(
				array(
					'class'	=>'CFileLogRoute',
					'levels'=>'error, warning, info'
				),
			),

		),
        //php������־�����ÿ��Ӧ�ö���������
        'log_config'=>array(
            'class'    =>'CFileLogRoute',
            'configs'=>array(
                array(
                    'log_name'    =>'application',//php��־�������ղ�����־��Ϊapplication_20111120.log
                    'err_name'    =>'error',        //������־��
                    'isSplit'    =>1                //��־�ָ��,0Ϊ�أ�1Ϊ��
                ),
            ),
        ),

		//'cache'=>array(
         //    'class'	=>'CMemCache',
          //    'servers'	=>array(
           //       array(
            //          'host'	=> '127.0.0.1',
             //         'port'	=> 11211,
              //        'weight'	=> 100
               //   )
             // )
          //),
        'session'=>array(
             //'class'		=>'CCacheHttpSession',
             'class'		=> 'CHttpSession',
             'sessionName'	=> 'traveldata',
          	 'sessionDomain'=> '/',
          	 'autoStart'	=> false
          ),
	//���ݿ���������	
	 'db'=>array(
			   'class'=>'CADOModel',
		  'dsn'=>'oci8://indoor:123456@192.168.243.185/tripdata?charset=ZHS16GBK',
          //  'isDebug' => true, 		// �������ݿ������Ϣ���Ƿ��ӡ��SQL
			   /*'memCacheHost'=>array(	// �������ݿ��memcache����
					array(
	                 	'host'	=> '192.168.0.123',
	                    'port'	=> 11211,
	                    'weight'=> 100
					)
				)	*/
			   
			 ),
		 'viewRenderer'=>array(
			   'class'		 =>'CSmartyViewRenderer',
			   'cache_dir'	 =>SF_ROOT.'/tmp/cache',
			   'template_dir'=>SF_ROOT.'templates/admin/',
			   'compile_dir' =>SF_ROOT.'/tmp/templateCompile',
			   'tmpl_suffix' =>'html',
			   'debug' =>'ture', //����ģʽ
   			   'zip' =>'false', //zipѹ��ģʽ 
			 ),
		 'urlManager'=>array(
			'urlFormat'=>'get',
			),
		  'image'=>array(
            	  	'class'=>'CImageComponent',
            // GD or ImageMagick
           		 'driver'=>'GD',
            // ImageMagick setup path
         		 'params'=>array('directory'=>'/usr/local/bin/'),
        	),
		'sms'=>array(
           	 	'class'=>'CSms',
            		'smskey'=>'1234567890',
            		'dsn'=>'http://219.136.249.236:8082/sms/', 
       		 ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	
       	 'params'=>array(
                // this is used in contact page
                'adminEmail'=>'webmaster@example.com',
                'encoding'      =>'gbk',
				'img_server' =>'http://file.tripdata.com/',
                'mail'=>array(
                         'mail_host' => 'mail.tripdata.com',
                         'mail_port' => '25',
						 'mail_user' => 'no-reply',
			             'mail_passwd' => 'n0-reply_pengy0u',
						 'mail_from' => 'no-reply@tripdata.com',
                 ),
        )
);
