var muvideomodule = function(quill, options) {
    setTimeout(function() {
        var button;

        button = jQuery('button[value=muvideomodule]');

        button
            .css('background', 'url(' + Zikula.Config.baseURL + Zikula.Config.baseURI + '/web/modules/muvideo/images/admin.png) no-repeat center center transparent')
            .css('background-size', '16px 16px')
            .attr('title', 'Video')
        ;

        button.click(function() {
            MUVideoModuleFinderOpenPopup(quill, 'quill');
        });
    }, 1000);
};
