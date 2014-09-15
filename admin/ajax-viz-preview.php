<?php

/********************************
* Preview Created Viz: bxtviz_preview_chart
********************************/

add_action("wp_ajax_nopriv_bxtviz_preview_chart", "bxtviz_previewchart");
add_action('wp_ajax_bxtviz_preview_chart', 'bxtviz_previewchart');

function bxtviz_previewchart(){
 
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
	
	
	
	
	echo '<div class="p-t-10 p-b-10 bootstrapped" id="add-chart-preview-container">' . 			
			'<form name="save-update-chart" data-bind="submit: chartSubmit">' .
			'<div class="row">' ;	
	
	echo '<div class="col-md-7 col-sm-6" id="">' .
			'<div id="add-chart-preview">' .
			'</div>' .
		'</div>' ;
		
	echo '<div class="col-md-5 col-sm-6" id="bxtviz-chconfig-tabbed">' .	
			'<ul class=" nav nav-tabs" data-bind="foreach: {data: configTabs}">' .			
				'<li data-bind="css: {\'active\' : $index() == 0}"><a class="" data-toggle="tab" data-bind="attr: { href: \'#bxtviz-chconfig-tab-\' + ($index()+1)},  text: $data.name, click: $parent.selectTab">Tab Name</a></li>' .
			'</ul>' .				
			'<div class="tab-content">' .			
			'<div id="bxtviz-chconfig-tab-2" class="tab-pane ">' .
				'<div class="row">' . 
				'<div class="col-md-12"><br/>' . 				
				'<div class="form-group">' .		
					'<h3 class="m-0">Options <button class="align-right btn btn-sm btn-info " data-bind="click: testNewConfig">Test Changes</button></h3>' . 
					'<textarea name="config" data-bind="value: config, initCodeMirror: { \'height\': \'auto\', \'lineNumbers\': true, \'lineWrapping\': true, \'matchBrackets\': true, \'mode\': \'text/typescript\' }"></textarea>' .	
				'</div>' .				
				'<br/>' .
				'<div class="form-group">' .		
					'<h3 class="">Palette</h3>' . 
					//'<input type="text" class="form-control" name="palette" value="'. $charts->palette . '"/>' .
					'<select class="form-control" data-bind="options: palettes, value: selectedPalette" name="palette"></select>' .
				'</div>' .					
				'</div>' .
				'</div>' .				
			'</div>' .	

			'<div id="bxtviz-chconfig-tab-3" class="tab-pane ">' .
				'<div class="row">' . 
				'<div class="col-md-12"><br/>' .
				'<div class="form-group">' .		
					'<h3 class="m-0">Data <button class="align-right btn btn-sm btn-info " data-bind="click: testNewConfig">Test Changes</button></h3>' .
				'</div>' .	
				'<div class="form-group">' .		
					'<div class="checkbox"><label><input type="checkbox" '. (($charts->useajax === '1')? 'checked' : '').' data-bind="checked: useAjax" name="useajax" /> Use remote source</label></div>' .
					'<div data-bind="visible: useAjax">' .
					'<input type="text" placeholder="http://" class="form-control" name="dataurl" value="'. $charts->dataurl . '"/>' .
					'<button class="btn btn-sm btn-warning align-right m-t-5" data-bind="click: testRemoteData" name="dataurl">Test Connection</button>' .				
					'<p class="help-block" data-bind="text: remoteData"></p>' .
					'</div>' .
				'</div>' .				
				'<div class="form-group" >' .	
				'<label>This Data will be used:</label>' . 
				'<textarea class="form-control" name="data" data-bind="value: data, initCodeMirror: { \'height\': \'auto\', \'lineNumbers\': true, \'lineWrapping\': true, \'matchBrackets\': true, \'mode\': \'text/typescript\' }"></textarea>' .					
				'</div>' .					
				'</div>' .
				'</div>' .				
			'</div>' .
			
			'<div id="bxtviz-chconfig-tab-1" class="tab-pane active">' .
				'<br/>' .	
				'<h3>Basic</h3>' . 
				'<div class="form-group">' .		
					'<label>Name</label>' .
					'<input type="text" placeholder="" class="form-control" name="name" value="'. $charts->name . '" />' .					
				'</div>' .
				'<div class="form-group">' .		
					'<label>Description</label>' .
					'<textarea name="description" rows="5" class="form-control">' .$charts->description. '</textarea>' .					
				'</div>' .
				'<hr/>' .
				
				'<h3>Advanced</h3>' . 				
				'<div class="form-group">' .
				'<div class="checkbox"><label class="selectit"><input type="checkbox" '. (($charts->search === '1')? 'checked' : '').' name="search" /> Include to Search</label></div>' .
				'</div>' .
			'</div>' .
			
			'</div>' .
			
		'</div>' ;	
	
	echo '<div id="TB_ajaxWindowFooter">' .
		'<button class="btn btn-md btn-default align-left add-chart-ajax" title="' . ((isset($_GET['title']) && $_GET['title'] !=='' ) ? $_GET['title'] : $charts->name) . '" href="admin-ajax.php?id=' . $charts->id . '&action=bxtviz_select_chart_type&width=full&height=full">Back</button>'.	
		'<button class="btn btn-md btn-success align-right" data-bind="visible: !isnew()" title="' . $charts->name . '" href="admin-ajax.php?id=' . $charts->id . '&action=bxtviz_new_chart&width=960&height=450">Save and Close</button>' .
		'<button class="btn btn-md btn-success align-right" data-bind="visible: isnew" title="' . $charts->name . '" href="admin-ajax.php?action=bxtviz_new_chart&width=960&height=450">Submit Viz</button>' .
		
		'</div>' .
		'<input type="hidden" value="' . $charts->id . '"  name="id"/>' .
		'<input type="hidden" data-bind="value: isnew"  name="isnew"/>' .
		'<input type="hidden" value="' . $_GET['new-type'] . '"  name="type"/>' .
		'<input type="hidden" value="bxtviz_chart_submit"  name="action"/>' .
		
		
		'</form>' .
		'</div>';	
		
		
		?>			
			
			<script>				
				var viz = new buildChartModel({
								isnew: <?php echo ((isset($charts->id) && $charts->id !== '')? '0' : '1'); ?>,
								config: <?php echo JSON_encode($charts->config)?>,
								type: '<?php echo $_GET['new-type'] ?>',
								dataurl: '<?php echo $charts->dataurl ?>',
								data: <?php echo JSON_encode($charts->data) ?>,
								useajax: <?php echo $charts->useajax ?>,
								search: <?php echo $charts->search ?>,	
								palettes: <?php echo JSON_encode($options['palettes']) ?>,
								palette: '<?php echo $charts->palette ?>',	
								container: '#add-chart-preview',
								configtabs: <?php echo JSON_encode($options['configtabs']) ?>,								
							});								
					viz.config.subscribe(viz.initChart);					
					viz.data.subscribe(viz.initChart);		
					viz.selectedPalette.subscribe(viz.init);
					ko.cleanNode(jQuery('#add-chart-preview-container')[0]); // clean it again
					ko.applyBindings(viz, jQuery('#add-chart-preview-container')[0]);
					viz.initChart();				
			</script>
			
			<?php
			
	
	exit();
	
}



