<?php

//extend blog Application configure.
require_once(dirname(__FILE__).'/main.php');
$g_app_default_config = CMap::mergeArray(
	$g_app_default_config,
    array( 
		'components'=>array( 
			'viewRenderer'=>array( 
				'template_dir'=>SF_ROOT.'templates/admin',
				),
				
			'session'=>array(
             'class'		=> 'CHttpSession',
             'sessionName'	=> 'traveldata',
          	 'sessionDomain'=> '/',
			 'autoStart'	=> false
          	),
          	
          	'errorHandler'=>array(
				// use 'site/error' action to display errors
            //	'errorAction'=>'admin/error',
        	),
		),
			
		'import'=>array(
			'application.controllers.admin.AdminController',
			'application.controllers.interface.CheckLoginController'
			),
		)
);

?>
