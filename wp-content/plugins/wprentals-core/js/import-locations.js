jQuery(document).ready(function($){
    var frame;
    $('#wprentals-upload-csv').on('click', function(e){
        e.preventDefault();
        if(frame){ frame.open(); return; }
        frame = wp.media({
            title: wprentalsImportLocations.uploadTitle,
            button: { text: wprentalsImportLocations.choose },
            library: { type: 'text/csv' },
            multiple: false
        });
        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            $('#wprentals-import-file').val(attachment.url);
        });
        frame.open();
    });

    $('#wprentals-run-import').on('click', function(e){
        e.preventDefault();
        var file = $('#wprentals-import-file').val();
        var $status = $('#wprentals-import-status');
        $status.removeClass('success error').text('');
        if(!file){
            $status.addClass('error').text(wprentalsImportLocations.noFile);
            return;
        }
        $.post(ajaxurl, {
            action: 'wprentals_import_locations',
            nonce: wprentalsImportLocations.nonce,
            file: file
        }, function(response){
            if(response.success){
                $status.addClass('success').text(response.data);
            }else{
                $status.addClass('error').text(response.data);
            }
        });
    });
});
