jQuery(document).ready(function($){
    $('.wprentals-plugin-table').on('click', '.wprentals-install-plugin', function(e){
        e.preventDefault();
        var $button = $(this);
        var slug = $button.data('slug');
        var source = $button.data('source');
        if(!slug || !source){
            return;
        }
        $button.prop('disabled', true).text(wprentalsInstallPlugin.installing);
        $.post(ajaxurl, {
            action: 'wprentals_install_plugin',
            nonce: wprentalsInstallPlugin.nonce,
            slug: slug,
            source: source
        }, function(response){
            if(response.success){
                location.reload();
            }else{
                alert(response.data || wprentalsInstallPlugin.error);
                location.reload();
            }
        });
    });
});
