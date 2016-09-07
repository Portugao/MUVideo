'use strict';


/**
 * Override method of Scriptaculous auto completer method.
 * Purpose: better feedback if no results are found (#247).
 * See http://stackoverflow.com/questions/657839/scriptaculous-ajax-autocomplete-empty-response for more information.
 */
Ajax.Autocompleter.prototype.updateChoices = function (choices)
{
    if (!this.changed && this.hasFocus) {
        if (!choices || choices == '<ul></ul>') {
            this.stopIndicator();
            var idPrefix = this.options.indicator.replace('Indicator', '');
            if (null != $(idPrefix + 'NoResultsHint')) {
                $(idPrefix + 'NoResultsHint').removeClassName('z-hide');
            }
        } else {
            this.update.innerHTML = choices;
            Element.cleanWhitespace(this.update);
            Element.cleanWhitespace(this.update.down());

            if (this.update.firstChild && this.update.down().childNodes) {
                this.entryCount = this.update.down().childNodes.length;
                for (var i = 0; i < this.entryCount; i++) {
                    var entry = this.getEntry(i);
                    entry.autocompleteIndex = i;
                    this.addObservers(entry);
                }
            } else {
                this.entryCount = 0;
            }

            this.stopIndicator();
            this.index = 0;

            if (this.entryCount == 1 && this.options.autoSelect) {
                this.selectEntry();
                this.hide();
            } else {
                this.render();
            }
        }
    }
}

/**
 * Resets the value of an upload / file input field.
 */
function mUMUVideoResetUploadField(fieldName)
{
    if (null != $(fieldName)) {
        $(fieldName).setAttribute('type', 'input');
        $(fieldName).setAttribute('type', 'file');
    }
}

/**
 * Initialises the reset button for a certain upload input.
 */
function mUMUVideoInitUploadField(fieldName)
{
    var fieldNameCapitalised;

    fieldNameCapitalised = fieldName.charAt(0).toUpperCase() + fieldName.substring(1);
    if (null != $('reset' + fieldNameCapitalised + 'Val')) {
        $('reset' + fieldNameCapitalised + 'Val').observe('click', function (evt) {
            evt.preventDefault();
            mUMUVideoResetUploadField(fieldName);
        }).removeClassName('z-hide').setStyle({ display: 'block' });
    }
}

