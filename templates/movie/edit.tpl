{* purpose of this template: build the Form to edit an instance of movie *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{include file="`$lct`/header.tpl"}
{pageaddvar name='javascript' value='modules/MUVideo/javascript/MUVideo_editFunctions.js'}
{pageaddvar name='javascript' value='modules/MUVideo/javascript/MUVideo_validation.js'}

{if $mode eq 'edit'}
    {gt text='Edit movie' assign='templateTitle'}
    {if $lcq eq 'admin'}
        {assign var='adminPageIcon' value='edit'}
    {/if}
{elseif $mode eq 'create'}
    {gt text='Create movie' assign='templateTitle'}
    {if $lcq eq 'admin'}
        {assign var='adminPageIcon' value='new'}
    {/if}
{else}
    {gt text='Edit movie' assign='templateTitle'}
    {if $lcq eq 'admin'}
        {assign var='adminPageIcon' value='edit'}
    {/if}
{/if}
<div class="muvideo-movie muvideo-edit">
    {pagesetvar name='title' value=$templateTitle}
    {if $lcq eq 'admin'}
        <div class="z-admin-content-pagetitle">
            {icon type=$adminPageIcon size='small' alt=$templateTitle}
            <h3>{$templateTitle}</h3>
        </div>
    {else}
        <h2>{$templateTitle}</h2>
    {/if}
{form enctype='multipart/form-data' cssClass='z-form'}
    {* add validation summary and a <div> element for styling the form *}
    {muvideoFormFrame}
    {formsetinitialfocus inputId='title'}

    <fieldset>
        <legend>{gt text='Content'}</legend>
        
        <div class="z-formrow">
            {formlabel for='title' __text='Title' mandatorysym='1' cssClass=''}
            {formtextinput group='movie' id='title' mandatory=true readOnly=false __title='Enter the title of the movie' textMode='singleline' maxLength=255 cssClass='required' }
            {muvideoValidationError id='title' class='required'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='description' __text='Description' cssClass=''}
            {formtextinput group='movie' id='description' mandatory=false __title='Enter the description of the movie' textMode='multiline' rows='6' cols='50' cssClass='' }
        </div>
        
        <div class="z-formrow">
            {formlabel for='uploadOfMovie' __text='Upload of movie' cssClass=''}<br />{* break required for Google Chrome *}
            {formuploadinput group='movie' id='uploadOfMovie' mandatory=false readOnly=false cssClass=' validate-upload' }
            <span class="z-formnote"><a id="resetUploadOfMovieVal" href="javascript:void(0);" class="z-hide" style="clear:left;">{gt text='Reset to empty value'}</a></span>
            
                <span class="z-formnote">{gt text='Allowed file extensions:'} <span id="uploadOfMovieFileExtensions">mp4</span></span>
            <span class="z-formnote">{gt text='Allowed file size:'} {modgetvar module='MUVideo' name='maxSizeOfMovie' assign='allowedFileSize'}{$allowedFileSize|muvideoGetFileSize:'':false:false}</span>
            {if $mode ne 'create'}
                {if $movie.uploadOfMovie ne ''}
                    <span class="z-formnote">
                        {gt text='Current file'}:
                        <a href="{$movie.uploadOfMovieFullPathUrl}" title="{$formattedEntityTitle|replace:"\"":""}"{if $movie.uploadOfMovieMeta.isImage} rel="imageviewer[movie]"{/if}>
                        {if $movie.uploadOfMovieMeta.isImage}
                            {thumb image=$movie.uploadOfMovieFullPath objectid="movie-`$movie.id`" preset=$movieThumbPresetUploadOfMovie tag=true img_alt=$formattedEntityTitle}
                        {else}
                            {gt text='Download'} ({$movie.uploadOfMovieMeta.size|muvideoGetFileSize:$movie.uploadOfMovieFullPath:false:false})
                        {/if}
                        </a>
                    </span>
                    <span class="z-formnote">
                        {formcheckbox group='movie' id='uploadOfMovieDeleteFile' readOnly=false __title='Delete upload of movie ?'}
                        {formlabel for='uploadOfMovieDeleteFile' __text='Delete existing file'}
                    </span>
                {/if}
            {/if}
            {muvideoValidationError id='uploadOfMovie' class='validate-upload'}
        </div>
        <div class="z-formrow">
            {formlabel for='urlOfYoutube' __text='Url of youtube' cssClass=''}
            {formurlinput group='movie' id='urlOfYoutube' mandatory=false readOnly=false __title='Enter the url of youtube of the movie' textMode='singleline' maxLength=255 cssClass=' validate-url' }
            {muvideoValidationError id='urlOfYoutube' class='validate-url'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='poster' __text='Poster' cssClass=''}<br />{* break required for Google Chrome *}
            {formuploadinput group='movie' id='poster' mandatory=false readOnly=false cssClass=' validate-upload' }
            <span class="z-formnote"><a id="resetPosterVal" href="javascript:void(0);" class="z-hide" style="clear:left;">{gt text='Reset to empty value'}</a></span>
            
                <span class="z-formnote">{gt text='Allowed file extensions:'} <span id="posterFileExtensions">gif, jpeg, jpg, png</span></span>
            <span class="z-formnote">{gt text='Allowed file size:'} {modgetvar module='MUVideo' name='maxSizeOfPoster' assign='allowedPosterSize'}{$allowedPosterSize|muvideoGetFileSize:'':false:false}</span>
            {if $mode ne 'create'}
                {if $movie.poster ne ''}
                    <span class="z-formnote">
                        {gt text='Current file'}:
                        <a href="{$movie.posterFullPathUrl}" title="{$formattedEntityTitle|replace:"\"":""}"{if $movie.posterMeta.isImage} rel="imageviewer[movie]"{/if}>
                        {if $movie.posterMeta.isImage}
                            {thumb image=$movie.posterFullPath objectid="movie-`$movie.id`" preset=$movieThumbPresetPoster tag=true img_alt=$formattedEntityTitle}
                        {else}
                            {gt text='Download'} ({$movie.posterMeta.size|muvideoGetFileSize:$movie.posterFullPath:false:false})
                        {/if}
                        </a>
                    </span>
                    <span class="z-formnote">
                        {formcheckbox group='movie' id='posterDeleteFile' readOnly=false __title='Delete poster ?'}
                        {formlabel for='posterDeleteFile' __text='Delete existing file'}
                    </span>
                {/if}
            {/if}
            {muvideoValidationError id='poster' class='validate-upload'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='widthOfMovie' __text='Width of movie' cssClass=''}
            {formintinput group='movie' id='widthOfMovie' mandatory=false readOnly=false __title='Enter the width of the movie' textMode='singleline' maxLength=255 cssClass='' }
            {muvideoValidationError id='widthOfMovie' class='required'}
        </div>
        
        <div class="z-formrow">
            {formlabel for='heightOfMovie' __text='Height of movie' cssClass=''}
            {formintinput group='movie' id='heightOfMovie' mandatory=true readOnly=false __title='Enter the height of the movie' textMode='singleline' maxLength=255 cssClass='' }
            {muvideoValidationError id='heightOfMovie' class='required'}
        </div>
    </fieldset>
    
    {include file='helper/include_categories_edit.tpl' obj=$movie groupName='movieObj'}
    {include file='collection/include_selectOne.tpl' group='movie' alias='collection' aliasReverse='movie' mandatory=false idPrefix='muvideoMovie_Collection' linkingItem=$movie displayMode='dropdown' allowEditing=false}
    {if $mode ne 'create'}
        {include file='helper/include_standardfields_edit.tpl' obj=$movie}
    {/if}
    
    {* include display hooks *}
    {if $mode ne 'create'}
        {assign var='hookId' value=$movie.id}
        {notifydisplayhooks eventname='muvideo.ui_hooks.movies.form_edit' id=$hookId assign='hooks'}
    {else}
        {notifydisplayhooks eventname='muvideo.ui_hooks.movies.form_edit' id=null assign='hooks'}
    {/if}
    {if is_array($hooks) && count($hooks)}
        {foreach key='providerArea' item='hook' from=$hooks}
            <fieldset>
                {$hook}
            </fieldset>
        {/foreach}
    {/if}
    
    {* include return control *}
    {if $mode eq 'create'}
        <fieldset>
            <legend>{gt text='Return control'}</legend>
            <div class="z-formrow">
                {formlabel for='repeatCreation' __text='Create another item after save'}
                    {formcheckbox group='movie' id='repeatCreation' readOnly=false}
            </div>
        </fieldset>
    {/if}
    
    {* include possible submit actions *}
    <div class="z-buttons z-formbuttons">
    {foreach item='action' from=$actions}
        {assign var='actionIdCapital' value=$action.id|@ucwords}
        {gt text=$action.title assign='actionTitle'}
        {*gt text=$action.description assign='actionDescription'*}{* TODO: formbutton could support title attributes *}
        {if $action.id eq 'delete'}
            {gt text='Really delete this movie?' assign='deleteConfirmMsg'}
            {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass confirmMessage=$deleteConfirmMsg}
        {else}
            {formbutton id="btn`$actionIdCapital`" commandName=$action.id text=$actionTitle class=$action.buttonClass}
        {/if}
    {/foreach}
        {formbutton id='btnCancel' commandName='cancel' __text='Cancel' class='z-bt-cancel'}
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

        muvideoAddCommonValidationRules('movie', '{{if $mode ne 'create'}}{{$movie.id}}{{/if}}');
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

        Zikula.UI.Tooltips($$('.muvideo-form-tooltips'));
        muvideoInitUploadField('uploadOfMovie');
        muvideoInitUploadField('poster');
    });

/* ]]> */
</script>
