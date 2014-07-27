CKEDITOR.plugins.add('MUVideo', {
    requires: 'popup',
    lang: 'en,nl,de',
    init: function (editor) {
        editor.addCommand('insertMUVideo', {
            exec: function (editor) {
                var url = Zikula.Config.baseURL + Zikula.Config.entrypoint + '?module=MUVideo&type=external&func=finder&editor=ckeditor';
                // call method in MUVideo_Finder.js and also give current editor
                MUVideoFinderCKEditor(editor, url);
            }
        });
        editor.ui.addButton('muvideo', {
            label: 'Insert MUVideo object',
            command: 'insertMUVideo',
         // icon: this.path + 'images/ed_muvideo.png'
            icon: '/images/icons/extrasmall/favorites.png'
        });
    }
});
