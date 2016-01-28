'use strict';

var currentMUVideoEditor = null;
var currentMUVideoInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getMUVideoPopupAttributes()
{
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',scrollbars,resizable';
}

/**
 * Open a popup window with the finder triggered by a Xinha button.
 */
function MUVideoFinderXinha(editor, muvideoUrl)
{
    var popupAttributes;

    // Save editor for access in selector window
    currentMUVideoEditor = editor;

    popupAttributes = getMUVideoPopupAttributes();
    window.open(muvideoUrl, '', popupAttributes);
}

/**
 * Open a popup window with the finder triggered by a CKEditor button.
 */
function MUVideoFinderCKEditor(editor, muvideoUrl)
{
    // Save editor for access in selector window
    currentMUVideoEditor = editor;

    editor.popup(
        Zikula.Config.baseURL + Zikula.Config.entrypoint + '?module=MUVideo&type=external&func=finder&editor=ckeditor',
        /*width*/ '80%', /*height*/ '70%',
        'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes'
    );
}


var mUVideo = {};

mUVideo.finder = {};

mUVideo.finder.onLoad = function (baseId, selectedId)
{
    $$('div.categoryselector select').invoke('observe', 'change', mUVideo.finder.onParamChanged);
    $('mUVideoSort').observe('change', mUVideo.finder.onParamChanged);
    $('mUVideoSortDir').observe('change', mUVideo.finder.onParamChanged);
    $('mUVideoPageSize').observe('change', mUVideo.finder.onParamChanged);
    $('mUVideoSearchGo').observe('click', mUVideo.finder.onParamChanged);
    $('mUVideoSearchGo').observe('keypress', mUVideo.finder.onParamChanged);
    $('mUVideoSubmit').addClassName('z-hide');
    $('mUVideoCancel').observe('click', mUVideo.finder.handleCancel);
};

mUVideo.finder.onParamChanged = function ()
{
    $('mUVideoSelectorForm').submit();
};

mUVideo.finder.handleCancel = function ()
{
    var editor, w;

    editor = $F('editorName');
    if (editor === 'xinha') {
        w = parent.window;
        window.close();
        w.focus();
    } else if (editor === 'tinymce') {
        mUMUVideoClosePopup();
    } else if (editor === 'ckeditor') {
        mUMUVideoClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function mUMUVideoGetPasteSnippet(mode, itemId)
{
    var quoteFinder, itemUrl, itemTitle, itemDescription, pasteMode;

    quoteFinder = new RegExp('"', 'g');
    itemUrl = $F('url' + itemId).replace(quoteFinder, '');
    itemTitle = $F('title' + itemId).replace(quoteFinder, '');
    itemDescription = $F('desc' + itemId).replace(quoteFinder, '');
    pasteMode = $F('mUVideoPasteAs');
    
    if (pasteMode === '4') {
    	return 'YOUTUBE[' + itemId + ']';
    }

    if (pasteMode === '2' || pasteMode !== '1') {
        return itemId;
    }

    // return link to item
    if (mode === 'url') {
        // plugin mode
        return itemUrl;
    }

    // editor mode
    return '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
}


// User clicks on "select item" button
mUVideo.finder.selectItem = function (itemId)
{
    var editor, html;

    editor = $F('editorName');
    if (editor === 'xinha') {
        if (window.opener.currentMUVideoEditor !== null) {
            html = mUMUVideoGetPasteSnippet('html', itemId);

            window.opener.currentMUVideoEditor.focusEditor();
            window.opener.currentMUVideoEditor.insertHTML(html);
        } else {
            html = mUMUVideoGetPasteSnippet('url', itemId);
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
        html = mUMUVideoGetPasteSnippet('html', itemId);
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
        // other tinymce commands: mceImage, mceInsertLink, mceReplaceContent, see http://www.tinymce.com/wiki.php/Command_identifiers
    } else if (editor === 'ckeditor') {
        if (window.opener.currentMUVideoEditor !== null) {
            html = mUMUVideoGetPasteSnippet('html', itemId);

            window.opener.currentMUVideoEditor.insertHtml(html);
        }
    } else {
        alert('Insert into Editor: ' + editor);
    }
    mUMUVideoClosePopup();
};


function mUMUVideoClosePopup()
{
    window.opener.focus();
    window.close();
}




//=============================================================================
// MUVideo item selector for Forms
//=============================================================================

mUVideo.itemSelector = {};
mUVideo.itemSelector.items = {};
mUVideo.itemSelector.baseId = 0;
mUVideo.itemSelector.selectedId = 0;

mUVideo.itemSelector.onLoad = function (baseId, selectedId)
{
    mUVideo.itemSelector.baseId = baseId;
    mUVideo.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    $('mUVideoObjectType').observe('change', mUVideo.itemSelector.onParamChanged);

    if ($(baseId + '_catidMain') != undefined) {
        $(baseId + '_catidMain').observe('change', mUVideo.itemSelector.onParamChanged);
    } else if ($(baseId + '_catidsMain') != undefined) {
        $(baseId + '_catidsMain').observe('change', mUVideo.itemSelector.onParamChanged);
    }
    $(baseId + 'Id').observe('change', mUVideo.itemSelector.onItemChanged);
    $(baseId + 'Sort').observe('change', mUVideo.itemSelector.onParamChanged);
    $(baseId + 'SortDir').observe('change', mUVideo.itemSelector.onParamChanged);
    $('mUVideoSearchGo').observe('click', mUVideo.itemSelector.onParamChanged);
    $('mUVideoSearchGo').observe('keypress', mUVideo.itemSelector.onParamChanged);

    mUVideo.itemSelector.getItemList();
};

mUVideo.itemSelector.onParamChanged = function ()
{
    $('ajax_indicator').removeClassName('z-hide');

    mUVideo.itemSelector.getItemList();
};

mUVideo.itemSelector.getItemList = function ()
{
    var baseId, params, request;

    baseId = muvideo.itemSelector.baseId;
    params = 'ot=' + baseId + '&';
    if ($(baseId + '_catidMain') != undefined) {
        params += 'catidMain=' + $F(baseId + '_catidMain') + '&';
    } else if ($(baseId + '_catidsMain') != undefined) {
        params += 'catidsMain=' + $F(baseId + '_catidsMain') + '&';
    }
    params += 'sort=' + $F(baseId + 'Sort') + '&' +
              'sortdir=' + $F(baseId + 'SortDir') + '&' +
              'q=' + $F(baseId + 'SearchTerm');

    request = new Zikula.Ajax.Request(
        Zikula.Config.baseURL + 'ajax.php?module=MUVideo&func=getItemListFinder',
        {
            method: 'post',
            parameters: params,
            onFailure: function(req) {
                Zikula.showajaxerror(req.getMessage());
            },
            onSuccess: function(req) {
                var baseId;
                baseId = mUVideo.itemSelector.baseId;
                mUVideo.itemSelector.items[baseId] = req.getData();
                $('ajax_indicator').addClassName('z-hide');
                mUVideo.itemSelector.updateItemDropdownEntries();
                mUVideo.itemSelector.updatePreview();
            }
        }
    );
};

mUVideo.itemSelector.updateItemDropdownEntries = function ()
{
    var baseId, itemSelector, items, i, item;

    baseId = mUVideo.itemSelector.baseId;
    itemSelector = $(baseId + 'Id');
    itemSelector.length = 0;

    items = mUVideo.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.options[i] = new Option(item.title, item.id, false);
    }

    if (mUVideo.itemSelector.selectedId > 0) {
        $(baseId + 'Id').value = mUVideo.itemSelector.selectedId;
    }
};

mUVideo.itemSelector.updatePreview = function ()
{
    var baseId, items, selectedElement, i;

    baseId = mUVideo.itemSelector.baseId;
    items = mUVideo.itemSelector.items[baseId];

    $(baseId + 'PreviewContainer').addClassName('z-hide');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (mUVideo.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id === mUVideo.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (selectedElement !== null) {
        $(baseId + 'PreviewContainer')
            .update(window.atob(selectedElement.previewInfo))
            .removeClassName('z-hide');
    }
};

mUVideo.itemSelector.onItemChanged = function ()
{
    var baseId, itemSelector, preview;

    baseId = mUVideo.itemSelector.baseId;
    itemSelector = $(baseId + 'Id');
    preview = window.atob(mUVideo.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    $(baseId + 'PreviewContainer').update(preview);
    mUVideo.itemSelector.selectedId = $F(baseId + 'Id');
};
