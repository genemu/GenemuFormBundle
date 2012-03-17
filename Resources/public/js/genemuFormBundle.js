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





