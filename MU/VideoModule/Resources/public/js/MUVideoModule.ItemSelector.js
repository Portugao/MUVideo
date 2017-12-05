'use strict';

var mUVideoModule = {};

mUVideoModule.itemSelector = {};
mUVideoModule.itemSelector.items = {};
mUVideoModule.itemSelector.baseId = 0;
mUVideoModule.itemSelector.selectedId = 0;

mUVideoModule.itemSelector.onLoad = function (baseId, selectedId) {
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

mUVideoModule.itemSelector.onParamChanged = function () {
    jQuery('#ajaxIndicator').removeClass('hidden');

    mUVideoModule.itemSelector.getItemList();
};

mUVideoModule.itemSelector.getItemList = function () {
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

    jQuery.getJSON(Routing.generate('muvideomodule_ajax_getitemlistfinder'), params, function (data) {
        var baseId;

        baseId = mUVideoModule.itemSelector.baseId;
        mUVideoModule.itemSelector.items[baseId] = data;
        jQuery('#ajaxIndicator').addClass('hidden');
        mUVideoModule.itemSelector.updateItemDropdownEntries();
        mUVideoModule.itemSelector.updatePreview();
    });
};

mUVideoModule.itemSelector.updateItemDropdownEntries = function () {
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

mUVideoModule.itemSelector.updatePreview = function () {
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

mUVideoModule.itemSelector.onItemChanged = function () {
    var baseId, itemSelector, preview;

    baseId = mUVideoModule.itemSelector.baseId;
    itemSelector = jQuery('#' + baseId + 'Id').get(0);
    preview = window.atob(mUVideoModule.itemSelector.items[baseId][itemSelector.selectedIndex].previewInfo);

    jQuery('#' + baseId + 'PreviewContainer').html(preview);
    mUVideoModule.itemSelector.selectedId = jQuery('#' + baseId + 'Id').val();
    mUVideoInitImageViewer();
};

jQuery(document).ready(function () {
    var infoElem;

    infoElem = jQuery('#itemSelectorInfo');
    if (infoElem.length == 0) {
        return;
    }

    mUVideoModule.itemSelector.onLoad(infoElem.data('base-id'), infoElem.data('selected-id'));
});
