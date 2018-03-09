<?php

// This is the configuration for SFc console application.
// Any writable CConsoleApplication properties can be configured here.

require_once(dirname(__FILE__).'/main.php');
$g_app_default_config = CMap::mergeArray(
	$g_app_default_config,
    array( 
		'components' => array( 
			),
			
		)
);
