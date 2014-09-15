<?php
global $wpdb;
$table_name = $wpdb->prefix . self::$wppt_db_name;
$options = self::getop();

$viz = array();	
if(isset($_GET["id"]) && trim($_GET["id"]) !== ''){
	$viz = $wpdb->get_results( "SELECT * FROM " .  $table_name . ' WHERE ID = ' . $_GET['id']); 			
}
$viz = reset($viz);
$index = 0;

echo '<div class="p-t-10 p-b-10">' . 
		'<form class="viz-ajax-form">' .
		'<div class="bootstrapped">' .
		'<div class="row">' ;
foreach ($options['data'] as $key => $opt):	
echo					
	'<div class="col-md-3 viz-chart-block m-b-10">' .
		'<label>' .
		'<div class="white-blocks">' .
			'<div class="" >' .
				'<img class="img-responsive" src="'. PLGIN_URL .'/admin/thumb/' . $opt['thumb'] . '" />' .
			'</div>' .
			'<p>' . $opt['description'] . '</p>' .
			'<h3 class="first-header">' .
				'<span>' . $opt['name'] . '</span>' .	
				'<span class="align-right">';
					
				if(isset($_GET["id"]) && trim($_GET["id"]) !== '')
					echo '<input type="radio" value="' . $opt['name'] . '"  name="new-type" ' . ((strtolower($viz->type) === strtolower($opt['name']))? 'checked' : '') . '/>' ;
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
		'<input type="hidden" value="' . $viz->id . '"  name="id"/>' .		
		'<input type="hidden" value="' . $viz->name . '"  name="name"/>' .	
		'<input type="hidden" value="' . ((isset($viz->name) && $viz->name!= '')? $viz->name : 'Add New Visualization') . '"  name="title"/>' .
		'<input type="hidden" value="vizpreview"  name="action"/>' .				
		'<button type="submit" class="btn btn-md btn-default align-right" >Next Step</button>' .
		'</div>' .
		'</div>' .
		'</form>' .
		'</div>';	
		