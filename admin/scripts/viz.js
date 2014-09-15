jQuery(document).ready(function($){	
	var vizopts = viz_opts;	
	function visualModel() {
	//var vizModel = {				
		var self = this;
        self.detailsEnabled	= ko.observable(false);		
		self.tp				= ko.observable();
		self.palettes		= ko.observable(vizopts.palettes);
		self.types			= ko.observable(vizopts.types);
		self.vizchoices		= ko.observableArray();
		self.viz			= ko.observableArray(vizopts.viz);
		self.previewViz	= null;
		self.nViz			= null;
			
		self.chooseViz	=  function(index, element) { 
								var t = $(index)[0].name || null;
								var a = ajaxurl + '?id=' + $(index)[0].id + '&action=viz_selecttype&width=full&height=full'
								var g = false;
								tb_show(t, a ,g);								
								return false;
							};
							
		self.initViz		= function(element, dta) {
							var cont = $(element).filter(".viz-block");
							$(cont)	.delay(500)  
									.animate({ top: '-10px' }, 10)
									.animate({ top: '0' }, 400);
									
							
							var IS_VALID_OPT = true, IS_VALID_DATA = true;
							try{
								var nconfig = (new Function("return " + dta.config.replace(/(\r\n|\n|\r|\t|\s+)/gm," ")))();
							}
							catch(err){
								IS_VALID_OPT = false;
							}   

							try{
								var ndata = (new Function("return " + dta.data.replace(/(\r\n|\n|\r|\t|\s+)/gm," ")))();							
							}
							catch(err) {
								IS_VALID_DATA = false;
							}  
							
							var wrap = $(cont).find('.viz-container');
										   
							if(IS_VALID_OPT === true && IS_VALID_DATA === true){
								var vz = new vizFrontEndModel({
												s			: '*',
												params		: [],							
												config		: nconfig,
												data		: ndata,
												palette		: dta.palette,
												type		: dta.type,
												container	: wrap,							
											});								
									vz.config.subscribe(vz.init);					
									vz.data.subscribe(vz.init);	
									vz.s.subscribe(vz.init);					
									ko.cleanNode(jQuery(wrap)[0]); // clean it again
									ko.applyBindings(vz, jQuery(wrap)[0]);
									vz.init();
							}else{
								alert('Check the viz options as well as Data format');
							}
								
							return false;
							
						};		
    };
	
	
	var visual = new visualModel();
	visual.vizchoices(vizopts.data);	
	ko.applyBindings(visual);
	
	
	
});