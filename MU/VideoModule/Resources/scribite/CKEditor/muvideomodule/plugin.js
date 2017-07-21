CKEDITOR.plugins.add('muvideomodule', {
    requires: 'popup',
    lang: 'en,nl,de',
    init: function (editor) {
        editor.addCommand('insertMUVideoModule', {
            exec: function (editor) {
                var url = Routing.generate('muvideomodule_external_finder', { objectType: 'collection', editor: 'ckeditor' });
                // call method in MUVideoModule.Finder.js and provide current editor
                MUVideoModuleFinderCKEditor(editor, url);
            }
        });
        editor.ui.addButton('muvideomodule', {
            label: editor.lang.muvideomodule.title,
            command: 'insertMUVideoModule',
            icon: this.path.replace('scribite/CKEditor/muvideomodule', 'public/images') + 'admin.png'
        });
    }
});
