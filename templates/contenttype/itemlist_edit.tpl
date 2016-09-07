{* Purpose of this template: edit view of generic item list content type *}
<div class="z-formrow">
    {gt text='Object type' domain='module_muvideo' assign='objectTypeSelectorLabel'}
    {formlabel for='mUVideoObjectType' text=$objectTypeSelectorLabel}
        {muvideoObjectTypeSelector assign='allObjectTypes'}
        {formdropdownlist id='mUVideoOjectType' dataField='objectType' group='data' mandatory=true items=$allObjectTypes}
        <span class="z-sub z-formnote">{gt text='If you change this please save the element once to reload the parameters below.' domain='module_muvideo'}</span>
</div>

{formvolatile}
{if $properties ne null && is_array($properties)}
    {nocache}
    {foreach key='registryId' item='registryCid' from=$registries}
        {assign var='propName' value=''}
        {foreach key='propertyName' item='propertyId' from=$properties}
            {if $propertyId eq $registryId}
                {assign var='propName' value=$propertyName}
            {/if}
        {/foreach}
        <div class="z-formrow">
            {modapifunc modname='MUVideo' type='category' func='hasMultipleSelection' ot=$objectType registry=$propertyName assign='hasMultiSelection'}
            {gt text='Category' domain='module_muvideo' assign='categorySelectorLabel'}
            {assign var='selectionMode' value='single'}
            {if $hasMultiSelection eq true}
                {gt text='Categories' domain='module_muvideo' assign='categorySelectorLabel'}
                {assign var='selectionMode' value='multiple'}
            {/if}
            {formlabel for="mUVideoCatIds`$propertyName`" text=$categorySelectorLabel}
                {formdropdownlist id="mUVideoCatIds`$propName`" items=$categories.$propName dataField="catids`$propName`" group='data' selectionMode=$selectionMode}
                <span class="z-sub z-formnote">{gt text='This is an optional filter.' domain='module_muvideo'}</span>
        </div>
    {/foreach}
    {/nocache}
{/if}
{/formvolatile}

<div class="z-formrow">
    {gt text='Sorting' domain='module_muvideo' assign='sortingLabel'}
    {formlabel text=$sortingLabel}
    <div>
        {formradiobutton id='mUVideoSortRandom' value='random' dataField='sorting' group='data' mandatory=true}
        {gt text='Random' domain='module_muvideo' assign='sortingRandomLabel'}
        {formlabel for='mUVideoSortRandom' text=$sortingRandomLabel}
        {formradiobutton id='mUVideoSortNewest' value='newest' dataField='sorting' group='data' mandatory=true}
        {gt text='Newest' domain='module_muvideo' assign='sortingNewestLabel'}
        {formlabel for='mUVideoSortNewest' text=$sortingNewestLabel}
        {formradiobutton id='mUVideoSortDefault' value='default' dataField='sorting' group='data' mandatory=true}
        {gt text='Default' domain='module_muvideo' assign='sortingDefaultLabel'}
        {formlabel for='mUVideoSortDefault' text=$sortingDefaultLabel}
    </div>
</div>

<div class="z-formrow">
    {gt text='Amount' domain='module_muvideo' assign='amountLabel'}
    {formlabel for='mUVideoAmount' text=$amountLabel}
        {formintinput id='mUVideoAmount' dataField='amount' group='data' mandatory=true maxLength=2}
</div>

<div class="z-formrow">
    {gt text='Template' domain='module_muvideo' assign='templateLabel'}
    {formlabel for='mUVideoTemplate' text=$templateLabel}
        {muvideoTemplateSelector assign='allTemplates'}
        {formdropdownlist id='mUVideoTemplate' dataField='template' group='data' mandatory=true items=$allTemplates}
</div>

<div id="customTemplateArea" class="z-formrow z-hide">
    {gt text='Custom template' domain='module_muvideo' assign='customTemplateLabel'}
    {formlabel for='mUVideoCustomTemplate' text=$customTemplateLabel}
        {formtextinput id='mUVideoCustomTemplate' dataField='customTemplate' group='data' mandatory=false maxLength=80}
        <span class="z-sub z-formnote">{gt text='Example' domain='module_muvideo'}: <em>itemlist_[objectType]_display.tpl</em></span>
</div>

<div class="z-formrow z-hide">
    {gt text='Filter (expert option)' domain='module_muvideo' assign='filterLabel'}
    {formlabel for='mUVideoFilter' text=$filterLabel}
        {formtextinput id='mUVideoFilter' dataField='filter' group='data' mandatory=false maxLength=255}
        <span class="z-sub z-formnote">
            ({gt text='Syntax examples' domain='module_muvideo'}: <kbd>name:like:foobar</kbd> {gt text='or'} <kbd>status:ne:3</kbd>)
        </span>
</div>

{pageaddvar name='javascript' value='prototype'}
<script type="text/javascript">
/* <![CDATA[ */
    function mUMUVideoToggleCustomTemplate() {
        if ($F('mUVideoTemplate') == 'custom') {
            $('customTemplateArea').removeClassName('z-hide');
        } else {
            $('customTemplateArea').addClassName('z-hide');
        }
    }

    document.observe('dom:loaded', function() {
        mUMUVideoToggleCustomTemplate();
        $('mUVideoTemplate').observe('change', function(e) {
            mUMUVideoToggleCustomTemplate();
        });
    });
/* ]]> */
</script>
