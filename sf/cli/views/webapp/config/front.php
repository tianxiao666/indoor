<?php

//extend blog Application configure.
require_once(dirname(__FILE__).'/main.php');
$g_app_default_config = CMap::mergeArray(
	$g_app_default_config,
    array( 
		'components' => array( 
			'viewRenderer' => array( 
				'template_dir'=> SF_ROOT.'templates/front/',
				),
				
			'errorHandler'=>array(
				// use 'site/error' action to display errors
            	'errorAction'=>'front/error',
        	),
		),
			
			'import' => array(
				'application.controllers.front.FrontController'
			),
			
		)
);
