{* Purpose of this template: edit view of generic item list content type *}
<div class="form-group">
    {gt text='Object type' domain='muvideomodule' assign='objectTypeSelectorLabel'}
    {formlabel for='mUVideoModuleObjectType' text=$objectTypeSelectorLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {muvideomoduleObjectTypeSelector assign='allObjectTypes'}
        {formdropdownlist id='mUVideoModuleObjectType' dataField='objectType' group='data' mandatory=true items=$allObjectTypes cssClass='form-control'}
        <span class="help-block">{gt text='If you change this please save the element once to reload the parameters below.' domain='muvideomodule'}</span>
    </div>
</div>

{if $featureActivationHelper->isEnabled(constant('MU\\VideoModule\\Helper\\FeatureActivationHelper::CATEGORIES'), $objectType)}
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
        <div class="form-group">
            {assign var='hasMultiSelection' value=$categoryHelper->hasMultipleSelection($objectType, $propertyName)}
            {gt text='Category' domain='muvideomodule' assign='categorySelectorLabel'}
            {assign var='selectionMode' value='single'}
            {if $hasMultiSelection eq true}
                {gt text='Categories' domain='muvideomodule' assign='categorySelectorLabel'}
                {assign var='selectionMode' value='multiple'}
            {/if}
            {formlabel for="mUVideoModuleCatIds`$propertyName`" text=$categorySelectorLabel cssClass='col-sm-3 control-label'}
            <div class="col-sm-9">
                {formdropdownlist id="mUVideoModuleCatIds`$propName`" items=$categories.$propName dataField="catids`$propName`" group='data' selectionMode=$selectionMode cssClass='form-control'}
                <span class="help-block">{gt text='This is an optional filter.' domain='muvideomodule'}</span>
            </div>
        </div>
    {/foreach}
    {/nocache}
{/if}
{/formvolatile}
{/if}

<div class="form-group">
    {gt text='Sorting' domain='muvideomodule' assign='sortingLabel'}
    {formlabel text=$sortingLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formradiobutton id='mUVideoModuleSortRandom' value='random' dataField='sorting' group='data' mandatory=true}
        {gt text='Random' domain='muvideomodule' assign='sortingRandomLabel'}
        {formlabel for='mUVideoModuleSortRandom' text=$sortingRandomLabel}
        {formradiobutton id='mUVideoModuleSortNewest' value='newest' dataField='sorting' group='data' mandatory=true}
        {gt text='Newest' domain='muvideomodule' assign='sortingNewestLabel'}
        {formlabel for='mUVideoModuleSortNewest' text=$sortingNewestLabel}
        {formradiobutton id='mUVideoModuleSortDefault' value='default' dataField='sorting' group='data' mandatory=true}
        {gt text='Default' domain='muvideomodule' assign='sortingDefaultLabel'}
        {formlabel for='mUVideoModuleSortDefault' text=$sortingDefaultLabel}
    </div>
</div>

<div class="form-group">
    {gt text='Amount' domain='muvideomodule' assign='amountLabel'}
    {formlabel for='mUVideoModuleAmount' text=$amountLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formintinput id='mUVideoModuleAmount' dataField='amount' group='data' mandatory=true maxLength=2 cssClass='form-control'}
    </div>
</div>

<div class="form-group">
    {gt text='Template' domain='muvideomodule' assign='templateLabel'}
    {formlabel for='mUVideoModuleTemplate' text=$templateLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {muvideomoduleTemplateSelector assign='allTemplates'}
        {formdropdownlist id='mUVideoModuleTemplate' dataField='template' group='data' mandatory=true items=$allTemplates cssClass='form-control'}
    </div>
</div>

<div id="customTemplateArea" class="form-group"{* data-switch="mUVideoModuleTemplate" data-switch-value="custom"*}>
    {gt text='Custom template' domain='muvideomodule' assign='customTemplateLabel'}
    {formlabel for='mUVideoModuleCustomTemplate' text=$customTemplateLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formtextinput id='mUVideoModuleCustomTemplate' dataField='customTemplate' group='data' mandatory=false maxLength=80 cssClass='form-control'}
        <span class="help-block">{gt text='Example' domain='muvideomodule'}: <em>itemlist_[objectType]_display.html.twig</em></span>
    </div>
</div>

<div class="form-group">
    {gt text='Filter (expert option)' domain='muvideomodule' assign='filterLabel'}
    {formlabel for='mUVideoModuleFilter' text=$filterLabel cssClass='col-sm-3 control-label'}
    <div class="col-sm-9">
        {formtextinput id='mUVideoModuleFilter' dataField='filter' group='data' mandatory=false maxLength=255 cssClass='form-control'}
        <span class="help-block">{gt text='Example' domain='muvideomodule'}: <em>tbl.age >= 18</em></span>
    </div>
</div>

<script type="text/javascript">
    (function($) {
    	$('#mUVideoModuleTemplate').change(function() {
    	    $('#customTemplateArea').toggleClass('hidden', $(this).val() != 'custom');
	    }).trigger('change');
    })(jQuery)
</script>
