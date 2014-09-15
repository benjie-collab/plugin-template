function vizFrontEndModel(opts) {	
	
	var self = this;
	self.s			= ko.observable(opts.s);
	self.params		= ko.observableArray(opts.params);
	
	self.config		= ko.observable(opts.config);
	self.data		= ko.observableArray(opts.data);
	self.palette	= ko.observable(opts.palette);
	self.type		= opts.type;
	self.container	= opts.container;
						
	
	self.init		= function(){								
						
						var options = self.config();
						options['palette'] = self.palette();
						
							if(self.type.toLowerCase() === 'pie'){	
								options['dataSource'] = self.data();			
								chart = jQuery(self.container)
								.dxPieChart(options);
							}else if(self.type.toLowerCase() === 'bar')	{	
								options['dataSource'] = self.data();
								chart = jQuery(self.container)
								.dxChart(options);
							}else if(self.type.toLowerCase() === 'range selector')	{	
								options['dataSource'] = self.data();
								chart = jQuery(self.container)
								.dxRangeSelector(options);
							}else if(self.type.toLowerCase() === 'circular gauges')	{	
								options['rangeContainer']['ranges'] = self.data();
								options['rangeContainer']['palette'] = self.palette();
								chart = jQuery(self.container)
								.dxCircularGauge(options);
							}else if(self.type.toLowerCase() === 'linear gauges')	{	
								options['rangeContainer']['ranges'] = self.data();
								options['rangeContainer']['palette'] = self.palette();
								chart = jQuery(self.container)
								.dxLinearGauge(options);
							}else if(self.type.toLowerCase() === 'bar gauge')	{	
								options['values'] = self.data();
								chart = jQuery(self.container)
								.dxBarGauge(options);
							}else if(self.type.toLowerCase() === 'sparklines')	{	
								options['dataSource'] = self.data();
								chart = jQuery(self.container)
								.dxSparkline(options);
							}else if(self.type.toLowerCase() === 'vector map')	{	
								options['markers'] = self.data();
								chart = jQuery(self.container)
								.dxVectorMap(options);
							}
							
							
							
							
						
						//return chart;
							
						}
}