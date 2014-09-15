<?php

/********************************
* Preview Created Viz: bxtviz_remote_data
********************************/

add_action("wp_ajax_nopriv_bxtviz_remote_data", "bxtviz_remotedata");
add_action('wp_ajax_bxtviz_remote_data', 'bxtviz_remotedata');

function bxtviz_remotedata(){
 
	global $bxtvizopts;	
	global $wpdb;
	global $bxtviz_db_name;
	$options = $bxtvizopts;
	
	
	
	$charts = array();	
	
	if(isset($_GET["id"]) && trim($_GET["id"]) !== ''){
		$charts = $wpdb->get_results( "SELECT * FROM " .  $wpdb->prefix . $bxtviz_db_name . ' WHERE ID = ' . $_GET['id']); 	
		$charts = reset($charts);
	}else{
		//Get default chart config if ID is not precent	
		$charts =  (object) $options['data'][strtolower($_GET['new-type'])];
	}
	
		
	
	$ch = curl_init();
	$skipper = "luxury assault recreational vehicle";
	$fields = array( 'penguins' => $skipper, 'bestpony' => 'rainbowdash');
	$postvars = '';
	foreach($fields as $key=>$value) {
		$postvars .= $key . "=" . $value . "&";
	}
	$url = $_POST['dataurl'];
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_POST,count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS,$postvars);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
	curl_setopt($ch,CURLOPT_TIMEOUT, 20);
	
	
	
	if(curl_exec($ch) === false)
	{
		echo 'Curl error: ' . curl_error($ch);
	}
	else
	{
		$response = curl_exec($ch);
		echo $response;
	}
	
	curl_close ($ch);
	die();
	
}



