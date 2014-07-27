'use strict';

var currentMUVideoEditor = null;
var currentMUVideoInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getPopupAttributes()
{
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',scrollbars,resizable';
}

/**
 * Open a popup window with the finder triggered by a Xinha button.
 */
function MUVideoFinderXinha(editor, muvideoURL)
{
    var popupAttributes;

    // Save editor for access in selector window
    currentMUVideoEditor = editor;

    popupAttributes = getPopupAttributes();
    window.open(muvideoURL, '', popupAttributes);
}

/**
 * Open a popup window with the finder triggered by a CKEditor button.
 */
function MUVideoFinderCKEditor(editor, muvideoURL)
{
    // Save editor for access in selector window
    currentMUVideoEditor = editor;

    editor.popup(
        Zikula.Config.baseURL + Zikula.Config.entrypoint + '?module=MUVideo&type=external&func=finder&editor=ckeditor',
        /*width*/ '80%', /*height*/ '70%',
        'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes'
    );
}



var muvideo = {};

muvideo.finder = {};

muvideo.finder.onLoad = function (baseId, selectedId)
{
    $$('div.categoryselector select').invoke('observe', 'change', muvideo.finder.onParamChanged);
    $('mUVideoSort').observe('change', muvideo.finder.onParamChanged);
    $('mUVideoSortDir').observe('change', muvideo.finder.onParamChanged);
    $('mUVideoPageSize').observe('change', muvideo.finder.onParamChanged);
    $('mUVideoSearchGo').observe('click', muvideo.finder.onParamChanged);
    $('mUVideoSearchGo').observe('keypress', muvideo.finder.onParamChanged);
    $('mUVideoSubmit').addClassName('z-hide');
    $('mUVideoCancel').observe('click', muvideo.finder.handleCancel);
};

muvideo.finder.onParamChanged = function ()
{
    $('mUVideoSelectorForm').submit();
};

muvideo.finder.handleCancel = function ()
{
    var editor, w;

    editor = $F('editorName');
    if (editor === 'xinha') {
        w = parent.window;
        window.close();
        w.focus();
    } else if (editor === 'tinymce') {
        muvideoClosePopup();
    } else if (editor === 'ckeditor') {
        muvideoClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function getPasteSnippet(mode, itemId)
{
    var itemUrl, itemTitle, itemDescription, pasteMode;

    itemUrl = $F('url' + itemId);
    itemTitle = $F('title' + itemId);
    itemDescription = $F('desc' + itemId);
    pasteMode = $F('mUVideoPasteAs');

    if (pasteMode === '2' || pasteMode !== '1') {
        return itemId;
    }

    // return link to item
    if (mode === 'url') {
        // plugin mode
        return itemUrl;
    } else {
        // editor mode
        return '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
}


// User clicks on "select item" button
muvideo.finder.selectItem = function (itemId)
{
    var editor, html;

    editor = $F('editorName');
    if (editor === 'xinha') {
        if (window.opener.currentMUVideoEditor !== null) {
            html = getPasteSnippet('html', itemId);

            window.opener.currentMUVideoEditor.focusEditor();
            window.opener.currentMUVideoEditor.insertHTML(html);
        } else {
            html = getPasteSnippet('url', itemId);
            var currentInput = window.opener.currentMUVideoInput;

            if (currentInput.tagName === 'INPUT') {
                // Simply overwrite value of input elements
                currentInput.value = html;
            } else if (currentInput.tagName === 'TEXTAREA') {
                // Try to paste into textarea - technique depends on environment
                if (typeof document.selection !== 'undefined') {
                    // IE: Move focus to textarea (which fortunately keeps its current selection) and overwrite selection
                    currentInput.focus();
                    window.opener.document.selection.createRange().text = html;
                } else if (typeof currentInput.selectionStart !== 'undefined') {
                    // Firefox: Get start and end points of selection and create new value based on old value
                    var startPos = currentInput.selectionStart;
                    var endPos = currentInput.selectionEnd;
                    currentInput.value = currentInput.value.substring(0, startPos)
                                        + html
                                        + currentInput.value.substring(endPos, currentInput.value.length);
                } else {
                    // Others: just append to the current value
                    currentInput.value += html;
                }
            }
        }
    } else if (editor === 'tinymce') {
        html = getPasteSnippet('html', itemId);
        window.opener.tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
        // other tinymce commands: mceImage, mceInsertLink, mceReplaceContent, see http://www.tinymce.com/wiki.php/Command_identifiers
    } else if (editor === 'ckeditor') {
        /** to be done*/
    } else {
        alert('Insert into Editor: ' + editor);
    }
    muvideoClosePopup();
};


function muvideoClosePopup()
{
    window.opener.focus();
    window.close();
}




//=============================================================================
// MUVideo item selector for Forms
//=============================================================================

muvideo.itemSelector = {};
muvideo.itemSelector.items = {};
muvideo.itemSelector.baseId = 0;
muvideo.itemSelector.selectedId = 0;

muvideo.itemSelector.onLoad = function (baseId, selectedId)
{
    muvideo.itemSelector.baseId = baseId;
    muvideo.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    $('mUVideoObjectType').observe('change', muvideo.itemSelector.onParamChanged);

    if ($(baseId + '_catidMain') != undefined) {
        $(baseId + '_catidMain').observe('change', muvideo.itemSelector.onParamChanged);
    } else if ($(baseId + '_catidsMain') != undefined) {
        $(baseId + '_catidsMain').observe('change', muvideo.itemSelector.onParamChanged);
    }
    $(baseId + 'Id').observe('change', muvideo.itemSelector.onItemChanged);
    $(baseId + 'Sort').observe('change', muvideo.itemSelector.onParamChanged);
    $(baseId + 'SortDir').observe('change', muvideo.itemSelector.onParamChanged);
    $('mUVideoSearchGo').observe('click', muvideo.itemSelector.onParamChanged);
    $('mUVideoSearchGo').observe('keypress', muvideo.itemSelector.onParamChanged);

    muvideo.itemSelector.getItemList();
};

muvideo.itemSelector.onParamChanged = function ()
{
    $('ajax_indicator').removeClassName('z-hide');

    muvideo.itemSelector.getItemList();
};

muvideo.itemSelector.getItemList = function ()
{
    var baseId, pars, request;

    baseId = muvideo.itemSelector.baseId;
    pars = 'ot=' + baseId + '&';
    if ($(baseId + '_catidMain') != undefined) {
        pars += 'catidMain=' + $F(baseId + '_catidMain') + '&';
    } else if ($(baseId + '_catidsMain') != undefined) {
        pars += 'catidsMain=' + $F(baseId + '_catidsMain') + '&';
    }
    pars += 'sort=' + $F(baseId + 'Sort') + '&' +
            'sortdir=' + $F(baseId + 'SortDir') + '&' +
            'searchterm=' + $F(baseId + 'SearchTerm');

    request = new Zikula.Ajax.Request(
        Zikula.Config.baseURL + 'ajax.php?module=MUVideo&func=getItemListFinder',
        {
            method: 'post',
            parameters: pars,
            onFailure: function(req) {
                Zikula.showajaxerror(req.getMessage());
            },
            onSuccess: function(req) {
                var baseId;
                baseId = muvideo.itemSelector.baseId;
                muvideo.itemSelector.items[baseId] = req.getData();
                $('ajax_indicator').addClassName('z-hide');
                muvideo.itemSelector.updateItemDropdownEntries();
                muvideo.itemSelector.updatePreview();
            }
        }
    );
};

muvideo.itemSelector.updateItemDropdownEntries = function ()
{
    var baseId, itemSelector, items, i, item;

    baseId = muvideo.itemSelector.baseId;
    itemSelector = $(baseId + 'Id');
    itemSelector.length = 0;

    items = muvideo.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.options[i] = new Option(item.title, item.id, false);
    }

    if (muvideo.itemSelector.selectedId > 0) {
        $(baseId + 'Id').value = muvideo.itemSelector.selectedId;
    }
};

muvideo.itemSelector.updatePreview = function ()
{
    var baseId, items, selectedElement, i;

    baseId = muvideo.itemSelector.baseId;
    items = muvideo.itemSelector.items[baseId];

    $(baseId + 'PreviewContainer').addClassName('z-hide');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (muvideo.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id === muvideo.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (selectedElement !== null) {
        $(baseId + 'PreviewContainer').update(window.atob(selectedElement.previewInfo))
                                      .removeClassName('z-hide');
    }
};

muvideo.itemSelector.onItemChanged = function ()
{
    var baseId, itemSelector, preview;

    baseId = muvideo.itemSelector.baseId;
    itemSelector = $(baseId + 'Id');
    preview = window.atob(muvideo.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    $(baseId + 'PreviewContainer').update(preview);
    muvideo.itemSelector.selectedId = $F(baseId + 'Id');
};
