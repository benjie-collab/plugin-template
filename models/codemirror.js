ko.bindingHandlers.initCodeMirror = {
	init: function(element, valueAccessor, allBindingsAccessor){	
		var typed = false;
		var options =  jQuery.extend(valueAccessor(), {
							onChange: function (cm) {
								typed = true;
								allBindingsAccessor().value(cm.getValue());
								typed = false;
							}
						});
		
		var editor = CodeMirror.fromTextArea(element, options);
		element.editor = editor;
		editor.setValue(allBindingsAccessor().value());
		editor.setSize('100%', (options.height || 200));
		editor.refresh();
					
		editor.on("change", function() {
			editor.save();				
		});
		
		
		var tabid = jQuery(element).closest('.tab-pane').attr('id');
		if(typeof(tabid)!== 'undefined')
		jQuery(document).find('a[data-toggle="tab"][href="#' + tabid + '"]')
			.on('shown.bs.tab' , function (e) {
			e.preventDefault();
			editor.refresh();
		})
		
		
		//store instance
		jQuery(element).data('CodeMirrorInstance', editor);
								
		var wrapperElement = jQuery(editor.getWrapperElement());
		
		ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
			wrapperElement.remove();
		});

		allBindingsAccessor().value.subscribe(function (newValue) {
			if (!typed) {
				editor.setValue(newValue);
				editor.refresh();
			}
		});
	},
	 update: function(element, valueAccessor, allBindings) {
		// Leave as before			
	}
}