<?php
defined( 'ABSPATH' ) OR exit;
ob_start();
?>



<div class="wrap bootstrapped" id="viz-option-page">				
	<h2>DXViz Visualizations 
		<a class="button-primary thickbox" title="Add New Visualization" href="admin-ajax.php?id=&action=viz_selecttype">Add New</a>
	</h2>
	
	<section class="wide-content" id="abc">
		<div class="row">	
			<div class="grid_12 p-0">
			<ul class="p-l-15 p-r-15">
				<li style="display: inline-block;"><a class="button active">All</a></li>
				<!-- ko foreach: { data: types } -->
				<li style="display: inline-block;"><a data-bind="text: $data, attr: { href: '#?type=' + $data}" class="button"></a></li>
				<!-- /ko -->
			</ul>
			</div>
		</div>	
		<!-- ko if(viz().length ===0) -->
		<div class="row">	
			<div class="col-md-3 col-sm-4 viz-block m-b-10">
				<div class="white-blocks text-center">
					<div class=""><h1><a class="btn btn-xl btn-warning thickbox" title="Add New Visualization" href="admin-ajax.php?id=&action=viz_selecttype">Add New</a></h1></div>
					<p>No Visualizations Yet Click Add New</p>
				</div>
				
			</div>			
		</div>
		<!-- /ko -->
		
		<div class="row" data-bind=" foreach: { data: viz, afterRender: initViz } ">	
			<div class="col-md-3 col-sm-4 viz-block m-b-10">
				<div class="white-blocks">
					<div class="viz-container" data-bind="attr: { id: 'viz-' + $data.id}"></div>
					<p data-bind="text: $data.description"></p>
					<h3 class="first-header">
						<span data-bind="text: $data.name">Brazil!</span>
						<span class="align-right">
							<a class="" data-bind="click: $parent.chooseViz"><i class="fa fa-cog"></i></a> &nbsp;
							<a class="" href="#"><i class="fa fa-copy"></i></a> &nbsp;
							<a class="" href="#"><i class="fa fa-ban"></i></a>
						</span>				
					</h3>
					<p data-bind="text: '[vizual id=' + $data.id + ']'"></p>
				</div>
			</div>			
		</div>
	</section>
	
	
	
	
</div>







<?php echo  ob_get_clean();
