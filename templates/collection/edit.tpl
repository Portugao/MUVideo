{* purpose of this template: build the form to edit an instance of collection *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{include file="`$lct`/header.tpl"}
{pageaddvar name='javascript' value='modules/MUVideo/javascript/MUVideo_editFunctions.js'}
{pageaddvar name='javascript' value='modules/MUVideo/javascript/MUVideo_validation.js'}

{if $mode ne 'create'}
    {gt text='Edit collection' assign='templateTitle'}
    {if $lct eq 'admin'}
        {assign var='adminPageIcon' value='edit'}
    {/if}
{elseif $mode eq 'create'}
    {gt text='Create collection' assign='templateTitle'}
    {if $lct eq 'admin'}
        {assign var='adminPageIcon' value='new'}
    {/if}
{/if}
<div class="muvideo-collection muvideo-edit">
    {pagesetvar name='title' value=$templateTitle}
    {if $lct eq 'admin'}
        <div class="z-admin-content-pagetitle">
            {icon type=$adminPageIcon size='small' alt=$templateTitle}
            <h3>{$templateTitle}</h3>
        </div>
    {else}
        <h2>{$templateTitle}</h2>
    {/if}
{form cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {muvideoFormFrame}
        {formsetinitialfocus inputId='title'}

    <div id="mUVideoPanel" class="z-panels">
        <h3 id="z-panel-header-fields" class="z-panel-header z-panel-indicator z-pointer">{gt text='Fields'}</h3>
        <div class="z-panel-content z-panel-active" style="overflow: visible">
            {formvolatile}
                {assign var='useOnlyCurrentLanguage' value=true}
                {if $modvars.ZConfig.multilingual}
                    {if is_array($supportedLanguages) && count($supportedLanguages) gt 1}
                        {assign var='useOnlyCurrentLanguage' value=false}
                        {nocache}
                        {lang assign='currentLanguage'}
                        {foreach item='language' from=$supportedLanguages}
                            {if $language eq $currentLanguage}
                                <fieldset>
                                    <legend>{$language|getlanguagename|safehtml}</legend>
                                    
                                    <div class="z-formrow">
                                        {formlabel for='title' __text='Title' mandatorysym='1' cssClass=''}
                                        {formtextinput group='collection' id='title' mandatory=true readOnly=false __title='Enter the title of the collection' textMode='singleline' maxLength=255 cssClass='required'}
                                        {muvideoValidationError id='title' class='required'}
                                    </div>
                                    
                                    <div class="z-formrow">
                                        {formlabel for='description' __text='Description' cssClass=''}
                                        {formtextinput group='collection' id='description' mandatory=false __title='Enter the description of the collection' textMode='multiline' rows='6' cols='50' cssClass=''}
                                    </div>
                                </fieldset>
                            {/if}
                        {/foreach}
                        {foreach item='language' from=$supportedLanguages}
                            {if $language ne $currentLanguage}
                                <fieldset>
                                    <legend>{$language|getlanguagename|safehtml}</legend>
                                    
                                    <div class="z-formrow">
                                        {formlabel for="title`$language`" __text='Title' mandatorysym='1' cssClass=''}
                                        {formtextinput group="collection`$language`" id="title`$language`" mandatory=true readOnly=false __title='Enter the title of the collection' textMode='singleline' maxLength=255 cssClass='required'}
                                        {muvideoValidationError id="title`$language`" class='required'}
                                    </div>
                                    
                                    <div class="z-formrow">
                                        {formlabel for="description`$language`" __text='Description' cssClass=''}
                                        {formtextinput group="collection`$language`" id="description`$language`" mandatory=false __title='Enter the description of the collection' textMode='multiline' rows='6' cols='50' cssClass=''}
                                    </div>
                                </fieldset>
                            {/if}
                        {/foreach}
                        {/nocache}
                    {/if}
                {/if}
                {if $useOnlyCurrentLanguage eq true}
                    {lang assign='language'}
                    <fieldset>
                        <legend>{$language|getlanguagename|safehtml}</legend>
                        
                        <div class="z-formrow">
                            {formlabel for='title' __text='Title' mandatorysym='1' cssClass=''}
                            {formtextinput group='collection' id='title' mandatory=true readOnly=false __title='Enter the title of the collection' textMode='singleline' maxLength=255 cssClass='required'}
                            {muvideoValidationError id='title' class='required'}
                        </div>
                        
                        <div class="z-formrow">
                            {formlabel for='description' __text='Description' cssClass=''}
                            {formtextinput group='collection' id='description' mandatory=false __title='Enter the description of the collection' textMode='multiline' rows='6' cols='50' cssClass=''}
                        </div>
                    </fieldset>
                {/if}
            {/formvolatile}
        </div>
        
        {include file='helper/includeCategoriesEdit.tpl' obj=$collection groupName='collectionObj' panel=true}
        {if $mode ne 'create'}
            {include file='helper/includeStandardFieldsEdit.tpl' obj=$collection panel=true}
        {/if}
        
        {* include display hooks *}
        {if $mode ne 'create'}
            {assign var='hookId' value=$collection.id}
            {notifydisplayhooks eventname='muvideo.ui_hooks.collections.form_edit' id=$hookId assign='hooks'}
        {else}
            {notifydisplayhooks eventname='muvideo.ui_hooks.collections.form_edit' id=null assign='hooks'}
        {/if}
        {if is_array($hooks) && count($hooks)}
            {foreach name='hookLoop' key='providerArea' item='hook' from=$hooks}
                {if $providerArea ne 'provider.scribite.ui_hooks.editor'}{* fix for #664 *}
                    <h3 class="hook z-panel-header z-panel-indicator z-pointer">{$providerArea}</h3>
                    <fieldset class="hook z-panel-content" style="display: none">
                        {$hook}
                    </fieldset>
                {/if}
            {/foreach}
        {/if}
        
        
        {* include return control *}
        {if $mode eq 'create'}
            <fieldset>
                <legend>{gt text='Return control'}</legend>
                <div class="z-formrow">
                    {formlabel for='repeatCreation' __text='Create another item after save'}
                    {formcheckbox group='collection' id='repeatCreation' readOnly=false}
                </div>
            </fieldset>
        {/if}
        
        {* include possible submit actions *}
        <div class="z-buttons z-formbuttons">
            {foreach item='action' from=$actions}
                {assign var='actionIdCapital' value=$action.id|@ucfirst}
                {gt text=$action.title assign='actionTitle'}
                {*gt text=$action.description assign='actionDescription'*}{* TODO: formbutton could support title attributes *}
                {if $action.id eq 'delete'}
                    {gt text='Really delete this collection?' assign='deleteConfirmMsg'}
                    {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass confirmMessage=$deleteConfirmMsg}
                {else}
                    {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass}
                {/if}
            {/foreach}
            {formbutton id='btnCancel' commandName='cancel' __text='Cancel' class='z-bt-cancel' formnovalidate='formnovalidate'}
        </div>
    </div>
    {/muvideoFormFrame}
{/form}
</div>
{include file="`$lct`/footer.tpl"}

{icon type='edit' size='extrasmall' assign='editImageArray'}
{icon type='delete' size='extrasmall' assign='removeImageArray'}


<script type="text/javascript">
/* <![CDATA[ */
    
    var formButtons, formValidator;
    
    function handleFormButton (event) {
        var result = formValidator.validate();
        if (!result) {
            // validation error, abort form submit
            Event.stop(event);
        } else {
            // hide form buttons to prevent double submits by accident
            formButtons.each(function (btn) {
                btn.addClassName('z-hide');
            });
        }
    
        return result;
    }
    
    document.observe('dom:loaded', function() {
    
        mUMUVideoAddCommonValidationRules('collection', '{{if $mode ne 'create'}}{{$collection.id}}{{/if}}');
        {{* observe validation on button events instead of form submit to exclude the cancel command *}}
        formValidator = new Validation('{{$__formid}}', {onSubmit: false, immediate: true, focusOnError: false});
        {{if $mode ne 'create'}}
            var result = formValidator.validate();
        {{/if}}
    
        formButtons = $('{{$__formid}}').select('div.z-formbuttons input');
    
        formButtons.each(function (elem) {
            if (elem.id != 'btnCancel') {
                elem.observe('click', handleFormButton);
            }
        });
    
        var panel = new Zikula.UI.Panels('mUVideoPanel', {
            headerSelector: 'h3',
            headerClassName: 'z-panel-header z-panel-indicator',
            contentClassName: 'z-panel-content',
            active: $('z-panel-header-fields')
        });
    
        Zikula.UI.Tooltips($$('.muvideo-form-tooltips'));
    });
/* ]]> */
</script>
