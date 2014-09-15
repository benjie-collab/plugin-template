<?php
global $wpdb;
$table_name = $wpdb->prefix . self::$wppt_db_name;
$options = self::getop();		


$wpdb->get_results( 'SELECT COUNT(*) FROM ' .  $table_name . ' WHERE ID = ' . $_POST['id'] );
if($wpdb->num_rows > 0){
	
	$wpdb->update( 
		$table_name, 
		array( 
			'config' => stripslashes($_POST['config']),	
			'data' => stripslashes($_POST['data']),
			'description' => stripslashes($_POST['description']),	
			'name' => stripslashes($_POST['name']),	
			'type' => stripslashes($_POST['type']),
			'palette' => stripslashes($_POST['palette']),
			'useajax' => ((stripslashes($_POST['useajax'])=== 'on')? true : false),
			'search' => ((stripslashes($_POST['search'])=== 'on')? true : false)
		), 
		array( 'id' => $_POST['id'] ), 
		array( 
			'%s',	
			'%s',	
			'%s',	
			'%s',	
			'%s',	
			'%s',
			'%s',
			'%s'
		), 
		array( '%d' ) 
	);
	echo 'Success! Visualization saved...';
}else{
	if ( ( strtolower($_POST['isnew']) === 'true'? true: false) === true){
		$wpdb->insert( 
			$table_name, 
			array( 
				'time' => date('Y-m-d H:i:s'),
				'config' => stripslashes($_POST['config']),	
				'data' => stripslashes($_POST['data']),
				'description' => stripslashes($_POST['description']),	
				'name' => stripslashes($_POST['name']),	
				'type' => stripslashes($_POST['type']),
				'dataurl' => stripslashes($_POST['dataurl']),
				'palette' => stripslashes($_POST['palette']),
				'useajax' => ((stripslashes($_POST['useajax'])=== 'on')? true : false),
				'search' => ((stripslashes($_POST['search'])=== 'on')? true : false)					
			), 
			array( 
				'%s', 
				'%s', 
				'%s', 				
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s', 
				'%s'					
			) 
		);
	echo  'Success! New visualization added!';
	}else
	echo  'Error! Invalid Request!';
}