'use strict';

var currentMUVideoModuleEditor = null;
var currentMUVideoModuleInput = null;

/**
 * Returns the attributes used for the popup window. 
 * @return {String}
 */
function getMUVideoModulePopupAttributes()
{
    var pWidth, pHeight;

    pWidth = screen.width * 0.75;
    pHeight = screen.height * 0.66;

    return 'width=' + pWidth + ',height=' + pHeight + ',scrollbars,resizable';
}

/**
 * Open a popup window with the finder triggered by a CKEditor button.
 */
function MUVideoModuleFinderCKEditor(editor, videoUrl)
{
    // Save editor for access in selector window
    currentMUVideoModuleEditor = editor;

    editor.popup(
        Routing.generate('muvideomodule_external_finder', { objectType: 'collection', editor: 'ckeditor' }),
        /*width*/ '80%', /*height*/ '70%',
        'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes'
    );
}


var mUVideoModule = {};

mUVideoModule.finder = {};

mUVideoModule.finder.onLoad = function (baseId, selectedId)
{
    var imageModeEnabled;

    imageModeEnabled = jQuery("[id$='onlyImages']").prop('checked');
    if (!imageModeEnabled) {
        jQuery('#imageFieldRow').addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=6]").addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=7]").addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=8]").addClass('hidden');
        jQuery("[id$='pasteAs'] option[value=9]").addClass('hidden');
    } else {
        jQuery('#searchTermRow').addClass('hidden');
    }

    jQuery('input[type="checkbox"]').click(mUVideoModule.finder.onParamChanged);
    jQuery('select').not("[id$='pasteAs']").change(mUVideoModule.finder.onParamChanged);
    
    jQuery('.btn-default').click(mUVideoModule.finder.handleCancel);

    var selectedItems = jQuery('#muvideomoduleItemContainer a');
    selectedItems.bind('click keypress', function (event) {
        event.preventDefault();
        mUVideoModule.finder.selectItem(jQuery(this).data('itemid'));
    });
};

mUVideoModule.finder.onParamChanged = function ()
{
    jQuery('#mUVideoModuleSelectorForm').submit();
};

mUVideoModule.finder.handleCancel = function (event)
{
    var editor;

    event.preventDefault();
    editor = jQuery("[id$='editor']").first().val();
    if ('tinymce' === editor) {
        mUVideoClosePopup();
    } else if ('ckeditor' === editor) {
        mUVideoClosePopup();
    } else {
        alert('Close Editor: ' + editor);
    }
};


function mUVideoGetPasteSnippet(mode, itemId)
{
    var quoteFinder;
    var itemPath;
    var itemUrl;
    var itemTitle;
    var itemDescription;
    var imagePath;
    var pasteMode;

    quoteFinder = new RegExp('"', 'g');
    itemPath = jQuery('#path' + itemId).val().replace(quoteFinder, '');
    itemUrl = jQuery('#url' + itemId).val().replace(quoteFinder, '');
    itemTitle = jQuery('#title' + itemId).val().replace(quoteFinder, '').trim();
    itemDescription = jQuery('#desc' + itemId).val().replace(quoteFinder, '').trim();
    imagePath = jQuery('#imagePath' + itemId).length > 0 ? jQuery('#imagePath' + itemId).val().replace(quoteFinder, '') : '';
    pasteMode = jQuery("[id$='pasteAs']").first().val();

    // item ID
    if (pasteMode === '3') {
        return '' + itemId;
    }

    // relative link to detail page
    if (pasteMode === '1') {
        return mode === 'url' ? itemPath : '<a href="' + itemPath + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
    // absolute url to detail page
    if (pasteMode === '2') {
        return mode === 'url' ? itemUrl : '<a href="' + itemUrl + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }

    if (pasteMode === '6') {
        // relative link to image file
        return mode === 'url' ? imagePath : '<a href="' + imagePath + '" title="' + itemDescription + '">' + itemTitle + '</a>';
    }
    if (pasteMode === '7') {
        // image tag
        return '<img src="' + imagePath + '" alt="' + itemTitle + '" width="300" />';
    }
    if (pasteMode === '8') {
        // image tag with relative link to detail page
        return mode === 'url' ? itemPath : '<a href="' + itemPath + '" title="' + itemTitle + '"><img src="' + imagePath + '" alt="' + itemTitle + '" width="300" /></a>';
    }
    if (pasteMode === '9') {
        // image tag with absolute url to detail page
        return mode === 'url' ? itemUrl : '<a href="' + itemUrl + '" title="' + itemTitle + '"><img src="' + imagePath + '" alt="' + itemTitle + '" width="300" /></a>';
    }


    return '';
}


// User clicks on "select item" button
mUVideoModule.finder.selectItem = function (itemId)
{
    var editor, html;

    editor = jQuery("[id$='editor']").first().val();
    if ('tinymce' === editor) {
        html = mUVideoGetPasteSnippet('html', itemId);
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, html);
        // other tinymce commands: mceImage, mceInsertLink, mceReplaceContent, see http://www.tinymce.com/wiki.php/Command_identifiers
    } else if ('ckeditor' === editor) {
        if (null !== window.opener.currentMUVideoModuleEditor) {
            html = mUVideoGetPasteSnippet('html', itemId);

            window.opener.currentMUVideoModuleEditor.insertHtml(html);
        }
    } else {
        alert('Insert into Editor: ' + editor);
    }
    mUVideoClosePopup();
};

function mUVideoClosePopup()
{
    window.opener.focus();
    window.close();
}




//=============================================================================
// MUVideoModule item selector for Forms
//=============================================================================

mUVideoModule.itemSelector = {};
mUVideoModule.itemSelector.items = {};
mUVideoModule.itemSelector.baseId = 0;
mUVideoModule.itemSelector.selectedId = 0;

mUVideoModule.itemSelector.onLoad = function (baseId, selectedId)
{
    mUVideoModule.itemSelector.baseId = baseId;
    mUVideoModule.itemSelector.selectedId = selectedId;

    // required as a changed object type requires a new instance of the item selector plugin
    jQuery('#mUVideoModuleObjectType').change(mUVideoModule.itemSelector.onParamChanged);

    jQuery('#' + baseId + '_catidMain').change(mUVideoModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + '_catidsMain').change(mUVideoModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'Id').change(mUVideoModule.itemSelector.onItemChanged);
    jQuery('#' + baseId + 'Sort').change(mUVideoModule.itemSelector.onParamChanged);
    jQuery('#' + baseId + 'SortDir').change(mUVideoModule.itemSelector.onParamChanged);
    jQuery('#mUVideoModuleSearchGo').click(mUVideoModule.itemSelector.onParamChanged);
    jQuery('#mUVideoModuleSearchGo').keypress(mUVideoModule.itemSelector.onParamChanged);

    mUVideoModule.itemSelector.getItemList();
};

mUVideoModule.itemSelector.onParamChanged = function ()
{
    jQuery('#ajaxIndicator').removeClass('hidden');

    mUVideoModule.itemSelector.getItemList();
};

mUVideoModule.itemSelector.getItemList = function ()
{
    var baseId;
    var params;

    baseId = mUVideoModule.itemSelector.baseId;
    params = {
        ot: baseId,
        sort: jQuery('#' + baseId + 'Sort').val(),
        sortdir: jQuery('#' + baseId + 'SortDir').val(),
        q: jQuery('#' + baseId + 'SearchTerm').val()
    }
    if (jQuery('#' + baseId + '_catidMain').length > 0) {
        params[catidMain] = jQuery('#' + baseId + '_catidMain').val();
    } else if (jQuery('#' + baseId + '_catidsMain').length > 0) {
        params[catidsMain] = jQuery('#' + baseId + '_catidsMain').val();
    }

    jQuery.getJSON(Routing.generate('muvideomodule_ajax_getitemlistfinder'), params, function( data ) {
        var baseId;

        baseId = mUVideoModule.itemSelector.baseId;
        mUVideoModule.itemSelector.items[baseId] = data;
        jQuery('#ajaxIndicator').addClass('hidden');
        mUVideoModule.itemSelector.updateItemDropdownEntries();
        mUVideoModule.itemSelector.updatePreview();
    });
};

mUVideoModule.itemSelector.updateItemDropdownEntries = function ()
{
    var baseId, itemSelector, items, i, item;

    baseId = mUVideoModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id');
    itemSelector.length = 0;

    items = mUVideoModule.itemSelector.items[baseId];
    for (i = 0; i < items.length; ++i) {
        item = items[i];
        itemSelector.get(0).options[i] = new Option(item.title, item.id, false);
    }

    if (mUVideoModule.itemSelector.selectedId > 0) {
        jQuery('#' + baseId + 'Id').val(mUVideoModule.itemSelector.selectedId);
    }
};

mUVideoModule.itemSelector.updatePreview = function ()
{
    var baseId, items, selectedElement, i;

    baseId = mUVideoModule.itemSelector.baseId;
    items = mUVideoModule.itemSelector.items[baseId];

    jQuery('#' + baseId + 'PreviewContainer').addClass('hidden');

    if (items.length === 0) {
        return;
    }

    selectedElement = items[0];
    if (mUVideoModule.itemSelector.selectedId > 0) {
        for (var i = 0; i < items.length; ++i) {
            if (items[i].id == mUVideoModule.itemSelector.selectedId) {
                selectedElement = items[i];
                break;
            }
        }
    }

    if (null !== selectedElement) {
        jQuery('#' + baseId + 'PreviewContainer')
            .html(window.atob(selectedElement.previewInfo))
            .removeClass('hidden');
        mUVideoInitImageViewer();
    }
};

mUVideoModule.itemSelector.onItemChanged = function ()
{
    var baseId, itemSelector, preview;

    baseId = mUVideoModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id').get(0);
    preview = window.atob(mUVideoModule.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    jQuery('#' + baseId + 'PreviewContainer').html(preview);
    mUVideoModule.itemSelector.selectedId = jQuery('#' + baseId + 'Id').val();
    mUVideoInitImageViewer();
};
