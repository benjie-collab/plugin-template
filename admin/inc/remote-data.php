<?php
global $wpdb;
$table_name = $wpdb->prefix . self::$wppt_db_name;
$options = self::getop();	

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