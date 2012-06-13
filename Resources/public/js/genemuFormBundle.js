function genemuFormBundleChosenEnable (id) {
	var configHolder = eval('genemuFormBundleChosenConfigs_'+id);
	var $field = $('#' + id);

    $field.chosen({
        no_results_text			: configHolder['empty_value'],
        allow_single_deselect	: configHolder['allow_single_deselect']
    });

    var path_route_name = configHolder['path_route_name'];
    
    if (typeof(path_route_name) != 'undefined') {
    
		var $input = $field.next('.chzn-container').find('li.search-field input');
	
		var AJAXOPTS	= {
			method	: 'GET',
			url		: path_route_name,
			dataType: 'json'
		};
	
	    var KEY 		= {ESC: 27, RETURN: 13, TAB: 9, BS: 8, DEL: 46, UP: 38, DOWN: 40};
	    var DKEY		= {TTO: "typingTimeout", SKY: "suppressKey"};
	
	    var HANDLERS 	= {
	    	data_transform			: configHolder['json_transform_func'],
	
	    	start_typing_timeout	: function() {
	            $.data($input, DKEY.TTO, window.setTimeout(function() {
	            	$input.triggerHandler("ajaxchosen");
	    		}, configHolder['typing_timeout']));
	    	},
	
	    	key_up_down				: function(e) {
	    		var k 	   = (e.which || e.keycode);
	            var t_out  = $.data($input, DKEY.TTO);
	
	            if ((k == KEY.UP || k == KEY.DOWN) && !t_out) {
	    			HANDLERS.start_typing_timeout();
	            } else if (k == KEY.BS || k == KEY.DEL) {
	               	if (t_out)
	               		window.clearInterval(t_out);
	
	               	HANDLERS.start_typing_timeout();
	             }
	    	},
	
	    	key_press				: function(e) {
	    		var k 	   = (e.keyCode || e.which || e.keycode);
	    		var t_out  = $.data($input, DKEY.TTO);
	
	            if (t_out)
	            	window.clearInterval(t_out);
	
	            if($.data(document.body, DKEY.SKY))
	            	return $.data(document.body, DKEY.SKY, false);
	
	    		if (k == KEY.BS || k == KEY.DEL || k > 32)
	            	HANDLERS.start_typing_timeout();
	    	},
	
	    	ajax_chosen				: function(e) {
	    		var val = $.trim($input.val());
	
	    		if (val.length < 3 || val === $input.data('prevVal')) {
	    			if (!val.length) {
	    				$field.find('option').each(function(i, item) {
	                        var $i = $(item);
	        				if (!$i.is(":selected"))
	            				$i.remove();
	    				});
	
	    				$input.data('prevVal', val);
	    				$field.trigger("liszt:updated");
	        		}
	
	    			return false;
	            }
	
	    		$input.data('prevVal', val);
	    		
	    		var query_param_name = configHolder['query_param_name'];
	
	            AJAXOPTS.data 		= {query_param_name: val};
	            AJAXOPTS.success 	= function(data) {
	
	                if (!(data != null))
	                	return;
	
	    			var items  			 = HANDLERS.data_transform(data),
	    				current_opts_ids = [];
	
	                $field.find('option').each(function(i, item) {
	                    var $i = $(item);
	    				if (!$i.is(":selected")) {
	        				$i.remove();
	    				} else {
	    					current_opts_ids.push($i.val());
	    				}
	                });
	
	                $.each(items, function(value, text) {
	    				if($.inArray(value, current_opts_ids) == -1) {
	    					$("<option />").attr('value', value).html(text).appendTo($field);
	                     	current_opts_ids.push(value);
	                   	}
	                });
	
					$field.trigger("liszt:updated");
	    		};
	
	            return $.ajax(AJAXOPTS);
	    	},
	
	    	reset_cur_searchval			: function(e) {
	        	$input.val($input.data('prevVal'));
	        }
	    }
	
	    $field	.bind('change', HANDLERS.reset_cur_searchval)
	    		.bind('liszt:updated', HANDLERS.reset_cur_searchval);
	
	    $input 	.keydown(HANDLERS.key_up_down)
	          	.keyup(HANDLERS.key_up_down)
	          	.keypress(HANDLERS.key_press)
	          	.bind('ajaxchosen', HANDLERS.ajax_chosen);

    }
}

function genemuFormBundleFileEnable (id) {
	var configHolder = eval('genemuFormBundleFileConfigs_'+id);  
	config = configHolder[0];
	auto = configHolder[1];
	var $field = $('#' + id);
    var $form = $field.closest('form');
    var $queue = $('#' + id + '_queue');
    var $nbQueue = 0;

    var $configs = $.extend(config, {
    	onComplete: function(event, queueID, fileObj, response, data) {
            var $response = eval('(' + response + ')');

            if ($response.result == '1') {
                var $current = $field.data('files') ? $field.data('files') : [];

                $current.push($response.file);
                $field.data('files', $current);

                $nbQueue--;

                if (typeof genemu_file_addCallback === 'function') {
                    genemu_file_addCallback($field, $queue, $nbQueue, $response);
                }
            } else {
                alert('Error');
            }
        },
        onSelect: function(event, ID, fileObj) {
            $nbQueue++;
        },
        onError: function() {
            //alert('error');
        }
    });
    
    if (auto) {
    	$configs.onAllComplete = function(event, data) {
            $form.submit();
        };
        
        $form.submit(function(event) {
            if (0 === $nbQueue) {
                return $joinFiles();
            } else {
                $field.uploadifyUpload();
                event.preventDefault();
            }
        });
        
    } else {
    	$form.submit(function(event) {
            return $joinFiles();
        });
    }

    var $joinFiles = function() {
        if ($files = $field.data('files')) {
            $field.val($files.join(','));
        }

        return true;
    };

    $field.uploadify($configs);
}





