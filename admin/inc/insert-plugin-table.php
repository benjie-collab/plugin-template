<?php
global $wpdb;
$table_name = $wpdb->prefix . self::$wppt_db_name;

$welcome_name = 'Mr. WordPres';
$welcome_text = 'Congratulations, you just completed the installation!';	
$wpdb->insert( 
	$table_name, 
	array( 			
		'time' => current_time( 'mysql' ), 
		'name' => $welcome_name,
		'type' => 'Bar',
		'palette' => 'Default',
		'config' => '{}',
		'data' => '[]',
		'useajax' => '0',
		'dataurl' => '',
		'search' => '0',
		'description' => $welcome_text
	) 
);