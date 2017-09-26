CKEDITOR.plugins.add('muvideomodule', {
    requires: 'popup',
    init: function (editor) {
        editor.addCommand('insertMUVideoModule', {
            exec: function (editor) {
                MUVideoModuleFinderOpenPopup(editor, 'ckeditor');
            }
        });
        editor.ui.addButton('muvideomodule', {
            label: 'Video',
            command: 'insertMUVideoModule',
            icon: this.path.replace('scribite/CKEditor/muvideomodule', 'images') + 'admin.png'
        });
    }
});
