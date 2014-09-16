<?php

/********************************
* New or Update Viz Submission: bxtviz_chart_submit
* Looking for parameter ID
* no ID then its New Viz
********************************/

add_action("wp_ajax_nopriv_bxtviz_chart_submit", "bxtviz_chartsubmit");
add_action('wp_ajax_bxtviz_chart_submit', 'bxtviz_chartsubmit');

function bxtviz_chartsubmit(){
 
	global $bxtvizopts;	
	global $wpdb;
	global $bxtviz_db_name;
	$options = $bxtvizopts;
	
		
	
	
	$wpdb->get_results( 'SELECT COUNT(*) FROM ' .  $wpdb->prefix . $bxtviz_db_name . ' WHERE ID = ' . $_POST['id'] );
	if($wpdb->num_rows > 0){
		
		$wpdb->update( 
			$wpdb->prefix . $bxtviz_db_name, 
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
				$wpdb->prefix . $bxtviz_db_name, 
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
		echo  'Adding New Viz';
		}else
		echo  'Error! Invalid Request!';
	}
	die();	
}




