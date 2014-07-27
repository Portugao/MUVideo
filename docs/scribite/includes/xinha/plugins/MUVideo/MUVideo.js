// MUVideo plugin for Xinha
// developed by Michael Ueberschaer
//
// requires MUVideo module (http://webdesign-in-bremen.com)
//
// Distributed under the same terms as xinha itself.
// This notice MUST stay intact for use (see license.txt).

'use strict';

function MUVideo(editor) {
    var cfg, self;

    this.editor = editor;
    cfg = editor.config;
    self = this;

    cfg.registerButton({
        id       : 'MUVideo',
        tooltip  : 'Insert MUVideo object',
     // image    : _editor_url + 'plugins/MUVideo/img/ed_MUVideo.gif',
        image    : '/images/icons/extrasmall/favorites.png',
        textMode : false,
        action   : function (editor) {
            var url = Zikula.Config.baseURL + 'index.php'/*Zikula.Config.entrypoint*/ + '?module=MUVideo&type=external&func=finder&editor=xinha';
            MUVideoFinderXinha(editor, url);
        }
    });
    cfg.addToolbarElement('MUVideo', 'insertimage', 1);
}

MUVideo._pluginInfo = {
    name          : 'MUVideo for xinha',
    version       : '1.0.0',
    developer     : 'Michael Ueberschaer',
    developer_url : 'http://webdesign-in-bremen.com',
    sponsor       : 'ModuleStudio 0.6.2',
    sponsor_url   : 'http://modulestudio.de',
    license       : 'htmlArea'
};
