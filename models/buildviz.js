function buildVizModel(opts) {

	var self = this;		
	self.isnew			= ko.observable((opts.isnew === 1 || opts.isnew === true)? true : false);
	self.config			= ko.observable(opts.config);
	self.container		= ko.observable(opts.container);
	self.useAjax		= ko.observable( (opts.useajax === 1 || opts.useajax === true)? true : false);
	self.dataUrl		= ko.observable(opts.dataurl);
	self.remoteData		= ko.observable('The response will appear here.');
	self.data			= ko.observable(opts.data);
	self.search			= ko.observable(opts.search || 0);
	self.type			= ko.observable(opts.type);
	
	
	self.palettes		= ko.observableArray(opts.palettes);
	self.selectedPalette= ko.observable(opts.palette);
	
	self.configTabs		= ko.observableArray(opts.configtabs);	
	self.selectedTab	= ko.observable();	
	self.selectTab		= function(dta, e){
							var cmInst;
							jQuery.each(jQuery(e.target.hash).find('textarea'), function(i, el){
							
								cmInst = jQuery(el).data('CodeMirrorInstance');	
								
								jQuery( window ).resize();
								if(typeof(cmInst) !== 'undefined'){								
																	
									cmInst.focus();	
									cmInst.refresh();
														
								}								
							});
							 
							
							//return false;
						};				
	
	self.viz			= ko.observable();		
	self.testNewConfig	= function(){
							self.data(jQuery('form[name="save-update-viz"]').find('[name="data"]').val());
							self.config(jQuery('form[name="save-update-viz"]').find('[name="config"]').val());								
							return false;
						};
						
	self.testRemoteData	= function(){
							jQuery.ajax({
							 url: ajaxurl,
							 data: {action: 'viz_remotedata', dataurl: jQuery('form[name="save-update-viz"]').find('[name="dataurl"]').val()},
							 type: "POST",
							 success: function(res) {
								self.remoteData(res);
							 }
							 });								
							return false;
						};	
	self.vizSubmit	= function(form){
							//self.config(jQuery('form[name="save-update-viz"]').find('[name="config"]').val());
															
							var param = {};
							jQuery.map( jQuery(form).serializeArray(), function(par){
								param[par.name] = par.value
							});								
							
							jQuery.ajax({
							 url: ajaxurl,
							 data: param,
							 type: "POST",
							 success: function(res) {
								alert(res);
							 }
							 });							
							return false;
						};
						
	
	self.initViz		= function(){
									
							var IS_VALID_OPT = true, IS_VALID_DATA = true;
							try{
								var nconfig = (new Function("return " + self.config().replace(/(\r\n|\n|\r|\t|\s+)/gm," ")))();
							}
							catch(err){
								IS_VALID_OPT = false;
							}   

							try{
								var ndata = (new Function("return " + self.data().replace(/(\r\n|\n|\r|\t|\s+)/gm," ")))();							
							}
							catch(err) {
								IS_VALID_DATA = false;
							}  
										   
							if(IS_VALID_OPT === true && IS_VALID_DATA === true){
								var viz = new vizFrontEndModel({
												s			: '*',
												params		: [],							
												config		: nconfig,
												data		: ndata,
												palette		: self.selectedPalette(),
												type		: self.type(),
												container	: self.container(),							
											});								
									viz.config.subscribe(viz.init);					
									viz.data.subscribe(viz.init);	
									viz.s.subscribe(viz.init);					
									ko.cleanNode(jQuery(self.container())[0]); // clean it again
									ko.applyBindings(viz, jQuery(self.container())[0]);
									viz.init();
							}else{
								alert('Check the chart options as well as Data format');
							}

							
							
							//self.chart(ch);
						}
	
	
}

	