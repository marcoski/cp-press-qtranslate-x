(function($){
	var qtx = qTranslateConfig.js.get_qtx();
	
	var cpPressTrasnlatableFields = [
		'[wtitle]', '[text]', '[linkbuttontext]', '[form]', '[body]', '[subject]', '[submit]', '[linktext]', '[slides][content]', '[slides][title]',
		'[items][caption]', '[markers][content]', '[ajaxbutton]', '[alttext]'
	];
	
	var cpPressRepeaterFields = {
		'slides': ['content', 'title'],
		'items': ['caption'],
		'markers': ['content']
	};
	
	var fieldRegexp = /\[([a-zA-Z0-9]*)\]/; 

	$(document).on('widget.presetdata', function(e, data){
		$.each(cpPressTrasnlatableFields, function(index, field){
			var fieldObj = {};
			if((m = fieldRegexp.exec(field)) === null){
				return;
			}
			var fieldName = m[1];
			var $field = $('#widget_form').find('[name*="'+field+'"]');
			if($field.is('[data-values]')){
				fieldObj = JSON.parse($field.attr('data-values'));
			}
			if(data.hasOwnProperty(fieldName) && data[fieldName] instanceof Object){
				$.each(cpPressRepeaterFields[fieldName], function(key, subFieldName){
					if(field === '['+fieldName+']['+subFieldName+']'){
						if(data[fieldName].hasOwnProperty(subFieldName)){
							var d = data[fieldName][subFieldName];
							for(var i = 0; i<d.length; i++){
								fieldObj[qtx.getActiveLanguage()] = d[i];
								d[i] = _.clone(fieldObj);
 							}
						}	
					}
				});
			}else if(data.hasOwnProperty(fieldName)){
				fieldObj[qtx.getActiveLanguage()] = data[fieldName];
				data[fieldName] = fieldObj;
			}
		});
	});
	
	$(document).on('widget.loaded repeater.add', function(){
		$.each(cpPressTrasnlatableFields, function(index, field){
			var $field = $('#widget_form').find('[name*="'+field+'"]');
			if($field.hasClass('wp-editor-area')) {
                $field.on('editor.init', function () {
                    $field.siblings('.mce-panel').addClass('cppress-translatable');
                });
            }else if($field.hasClass('.code-editor')){
                $field.siblings('.ace_editor').addClass('cppress-translatable');
			}else{
				$field.addClass('cppress-translatable');
			}
		});
	});
	
	$(document).on('widget.premodalopen repeater.preadd', function(e, args){
		if(args.hasOwnProperty('widget')){
			args.widget['lang'] = qtx.getActiveLanguage();
		}else{
			args['widget'] = {'lang': qtx.getActiveLanguage()};
		}
	});
	
	$(document).on('widget.pregetdata', function(e, data, attr){
		if(data.hasOwnProperty(attr)){
			if(data[attr] instanceof Object){
				var value = data[attr];
				value = value[qtx.getActiveLanguage()];
				data[attr] = value;
			}
		}
	});
	
	qtx.addLanguageSwitchAfterListener(function(){
		$.cpPageBuilder.refresh();
	});
	
	
})(jQuery);