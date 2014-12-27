CKEDITOR.plugins.add('muvideo', {
    requires: 'popup',
    lang: 'en,nl,de',
    init: function (editor) {
        editor.addCommand('insertMUVideo', {
            exec: function (editor) {
                var url = Zikula.Config.baseURL + Zikula.Config.entrypoint + '?module=MUVideo&type=external&func=finder&editor=ckeditor';
                // call method in MUVideo_finder.js and provide current editor
                MUVideoFinderCKEditor(editor, url);
            }
        });
        editor.ui.addButton('muvideo', {
            label: editor.lang.muvideo.title,
            command: 'insertMUVideo',
         // icon: this.path + 'images/ed_muvideo.png'
            icon: '/modules/MUVideo/images/muvideo.png'
        });
    }
});
