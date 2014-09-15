<?php
global $wpdb;
$table_name = $wpdb->prefix . self::$wppt_db_name;
$options = self::getop();

$viz = array();	
if(isset($_GET["id"]) && trim($_GET["id"]) !== ''){
	$viz = $wpdb->get_results( "SELECT * FROM " .  $table_name . ' WHERE ID = ' . $_GET['id']); 
	$viz = reset($viz);
}else{
	//Get default chart config if ID is not present	
	$viz =  (object) $options['data'][strtolower($_GET['new-type'])];
}

$index = 0;


echo '<div class="p-t-10 p-b-10 bootstrapped" id="viz-preview-container">' . 			
		'<form name="save-update-viz" data-bind="submit: vizSubmit">' .
		'<div class="row">' ;	

echo '<div class="col-md-7 col-sm-6" id="">' .
		'<div id="viz-preview">' .
		'</div>' .
	'</div>' ;
	
echo '<div class="col-md-5 col-sm-6" id="viz-chconfig-tabbed">' .	
		'<ul class=" nav nav-tabs" data-bind="foreach: {data: configTabs}">' .			
			'<li data-bind="css: {\'active\' : $index() == 0}"><a class="" data-toggle="tab" data-bind="attr: { href: \'#viz-chconfig-tab-\' + ($index()+1)},  text: $data.name, click: $parent.selectTab">Tab Name</a></li>' .
		'</ul>' .				
		'<div class="tab-content">' .			
		'<div id="viz-chconfig-tab-2" class="tab-pane ">' .
			'<div class="row">' . 
			'<div class="col-md-12"><br/>' . 				
			'<div class="form-group">' .		
				'<h3 class="m-0">Options <button class="align-right btn btn-sm btn-info " data-bind="click: testNewConfig">Test Changes</button></h3>' . 
				'<textarea name="config" data-bind="value: config, initCodeMirror: { \'height\': \'auto\', \'lineNumbers\': true, \'lineWrapping\': true, \'matchBrackets\': true, \'mode\': \'text/typescript\' }"></textarea>' .	
			'</div>' .				
			'<br/>' .
			'<div class="form-group">' .		
				'<h3 class="">Palette</h3>' . 
				//'<input type="text" class="form-control" name="palette" value="'. $viz->palette . '"/>' .
				'<select class="form-control" data-bind="options: palettes, value: selectedPalette" name="palette"></select>' .
			'</div>' .					
			'</div>' .
			'</div>' .				
		'</div>' .	

		'<div id="viz-chconfig-tab-3" class="tab-pane ">' .
			'<div class="row">' . 
			'<div class="col-md-12"><br/>' .
			'<div class="form-group">' .		
				'<h3 class="m-0">Data <button class="align-right btn btn-sm btn-info " data-bind="click: testNewConfig">Test Changes</button></h3>' .
			'</div>' .	
			'<div class="form-group">' .		
				'<div class="checkbox"><label><input type="checkbox" '. (($viz->useajax === '1')? 'checked' : '').' data-bind="checked: useAjax" name="useajax" /> Use remote source</label></div>' .
				'<div data-bind="visible: useAjax">' .
				'<input type="text" placeholder="http://" class="form-control" name="dataurl" value="'. $viz->dataurl . '"/>' .
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
		
		'<div id="viz-chconfig-tab-1" class="tab-pane active">' .
			'<br/>' .	
			'<h3>Basic</h3>' . 
			'<div class="form-group">' .		
				'<label>Name</label>' .
				'<input type="text" placeholder="" class="form-control" name="name" value="'. $viz->name . '" />' .					
			'</div>' .
			'<div class="form-group">' .		
				'<label>Description</label>' .
				'<textarea name="description" rows="5" class="form-control">' .$viz->description. '</textarea>' .					
			'</div>' .
			'<hr/>' .
			
			'<h3>Advanced</h3>' . 				
			'<div class="form-group">' .
			'<div class="checkbox"><label class="selectit"><input type="checkbox" '. (($viz->search === '1')? 'checked' : '').' name="search" /> Include to Search</label></div>' .
			'</div>' .
		'</div>' .
		
		'</div>' .
		
	'</div>' ;	

echo '<div id="TB_ajaxWindowFooter">' .
	'<button class="btn btn-md btn-default align-left viz-ajax" title="' . ((isset($_GET['title']) && $_GET['title'] !=='' ) ? $_GET['title'] : $viz->name) . '" href="admin-ajax.php?id=' . $viz->id . '&action=viz_selecttype&width=full&height=full">Back</button>'.	
	'<button class="btn btn-md btn-success align-right" data-bind="visible: !isnew()" title="' . $viz->name . '" href="admin-ajax.php?id=' . $viz->id . '&action=vizsubmit&width=960&height=450">Save</button>' .
	'<button class="btn btn-md btn-success align-right" data-bind="visible: isnew" title="' . $viz->name . '" href="admin-ajax.php?action=vizsubmit&width=960&height=450">Submit Viz</button>' .
	
	'</div>' .
	'<input type="hidden" value="' . $viz->id . '"  name="id"/>' .
	'<input type="hidden" data-bind="value: isnew"  name="isnew"/>' .
	'<input type="hidden" value="' . $_GET['new-type'] . '"  name="type"/>' .
	'<input type="hidden" value="vizsubmit"  name="action"/>' .
	
	
	'</form>' .
	'</div>';	
	
	
	?>			
		
		<script>				
			var viz = new buildVizModel({
							isnew: <?php echo ((isset($viz->id) && $viz->id !== '')? '0' : '1'); ?>,
							config: <?php echo JSON_encode($viz->config)?>,
							type: '<?php echo $_GET['new-type'] ?>',
							dataurl: '<?php echo $viz->dataurl ?>',
							data: <?php echo JSON_encode($viz->data) ?>,
							useajax: <?php echo $viz->useajax ?>,
							search: <?php echo $viz->search ?>,	
							palettes: <?php echo JSON_encode($options['palettes']) ?>,
							palette: '<?php echo $viz->palette ?>',	
							container: '#viz-preview',
							configtabs: <?php echo JSON_encode($options['configtabs']) ?>,								
						});								
				viz.config.subscribe(viz.initViz);					
				viz.data.subscribe(viz.initViz);		
				viz.selectedPalette.subscribe(viz.initViz);
				ko.cleanNode(jQuery('#viz-preview-container')[0]); // clean it again
				ko.applyBindings(viz, jQuery('#viz-preview-container')[0]);
				viz.initViz();				
		</script>
		
		<?php