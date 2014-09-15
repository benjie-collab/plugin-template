jQuery(document).ready(function($){	
	
	$(document).on('click', '.viz-ajax', function(){	
		var t = $(this).attr('title') || null;
		var a = $(this).attr('href') || null;
		var g = false;		
		tb_show(t, a ,g);			
		return false;
	});		
	
	$(document).on('submit', '.viz-ajax-form', function(){
		var t = ($(this).find('[name="id"]').val()==='')? 'Add New Visualization': $(this).find('[name="title"]').val();
		var a = $(this).serialize() ;
		var g = false;	
		tb_show(t, ajaxurl + '?' + a , g);
		return false;
	});	
	
});