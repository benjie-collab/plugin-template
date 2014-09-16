<?php

/********************************
* Select Viz Type Form: viz_select_chart_type
********************************/

add_action("wp_ajax_nopriv_viz_select_chart_type", "viz_select_charttype");
add_action('wp_ajax_viz_select_chart_type', 'viz_select_charttype');

function viz_select_charttype(){
 
	global $bxtvizopts;	
	global $wpdb;
	global $bxtviz_db_name;
	$options = $bxtvizopts;
	
	$charts = array();	
	if(isset($_GET["id"]) && trim($_GET["id"]) !== ''){
		$charts = $wpdb->get_results( "SELECT * FROM " .  $wpdb->prefix . $bxtviz_db_name . ' WHERE ID = ' . $_GET['id']); 			
	}
	$charts = reset($charts);
	$index = 0;
	
	echo '<div class="p-t-10 p-b-10">' . 
			'<form class="add-chart-ajax-form">' .
			'<div class="bootstrapped">' .
			'<div class="row">' ;
	foreach ($options['data'] as $key => $opt):	
	echo					
		'<div class="col-md-3 bxtviz-chart-block m-b-10">' .
			'<label>' .
			'<div class="white-blocks">' .
				'<div class="" >' .
					'<img class="img-responsive" src="'. BXT_VIZ_URL .'/img/' . $opt['thumb'] . '" />' .
				'</div>' .
				'<p>' . $opt['description'] . '</p>' .
				'<h3 class="first-header">' .
					'<span>' . $opt['name'] . '</span>' .	
					'<span class="align-right">';
						
					if(isset($_GET["id"]) && trim($_GET["id"]) !== '')
						echo '<input type="radio" value="' . $opt['name'] . '"  name="new-type" ' . ((strtolower($charts->type) === strtolower($opt['name']))? 'checked' : '') . '/>' ;
					else{
						echo '<input type="radio" value="' . $opt['name'] . '"  name="new-type" ' . (($index === 0)? 'checked' : '') . '/>' ;
					}						
						
	echo			'</span>' .
				'</h3>' .
			'</div>' .
			'</label>' .
		'</div>' ;	
	$index++;
	endforeach;	
	echo '</div>' .
			'<div id="TB_ajaxWindowFooter">' .
			'<input type="hidden" value="' . $charts->id . '"  name="id"/>' .		
			'<input type="hidden" value="' . $charts->name . '"  name="name"/>' .	
			'<input type="hidden" value="' . ((isset($charts->name) && $charts->name!= '')? $charts->name : 'Add New Visualization') . '"  name="title"/>' .
			'<input type="hidden" value="bxtviz_preview_chart"  name="action"/>' .				
			'<button type="submit" class="btn btn-md btn-default align-right" >Next Step</button>' .
			'</div>' .
			'</div>' .
			'</form>' .
			'</div>';
			
	die();
	
}


