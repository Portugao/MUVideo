'use strict';

function videoToggleShrinkSettings(fieldName) {
    var idSuffix = fieldName.replace('muvideomodule_appsettings_', '');
    jQuery('#shrinkDetails' + idSuffix).toggleClass('hidden', !jQuery('#muvideomodule_appsettings_enableShrinkingFor' + idSuffix).prop('checked'));
}

jQuery(document).ready(function() {
    jQuery('.shrink-enabler').each(function (index) {
        jQuery(this).bind('click keyup', function (event) {
            videoToggleShrinkSettings(jQuery(this).attr('id').replace('enableShrinkingFor', ''));
        });
        videoToggleShrinkSettings(jQuery(this).attr('id').replace('enableShrinkingFor', ''));
    });
});
