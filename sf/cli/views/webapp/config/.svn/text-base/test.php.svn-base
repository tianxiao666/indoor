<?php

require_once(dirname(__FILE__).'/main.php');
$g_app_default_config = CMap::mergeArray(
	$g_app_default_config,
    array( 
    	'import'=>array(
			'system.library.*',
			'system.test.*'
		),
		
		'components' => array( 
    		'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
		),
			
	)
);
