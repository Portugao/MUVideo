{* purpose of this template: module configuration page *}
{include file='admin/header.tpl'}
<div class="muvideo-config">
    {gt text='Settings' assign='templateTitle'}
    {pagesetvar name='title' value=$templateTitle}
    <div class="z-admin-content-pagetitle">
        {icon type='config' size='small' __alt='Settings'}
        <h3>{$templateTitle}</h3>
    </div>

    {form cssClass='z-form'}
        {* add validation summary and a <div> element for styling the form *}
        {muvideoFormFrame}
            {formsetinitialfocus inputId='pageSize'}
            {gt text='Variables' assign='tabTitle'}
            <fieldset>
                <legend>{$tabTitle}</legend>
            
                <p class="z-confirmationmsg">{gt text='Here you can manage all basic settings for this application.'}</p>
            
                <div class="z-formrow">
                    {formlabel for='pageSize' __text='Page size' cssClass=''}
                    {formintinput id='pageSize' group='config' maxLength=255 __title='Enter the page size. Only digits are allowed.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='maxSizeOfMovie' __text='Max size of movie' cssClass=''}
                    {formintinput id='maxSizeOfMovie' group='config' maxLength=255 __title='Enter the max size of movie. Only digits are allowed.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='maxSizeOfPoster' __text='Max size of poster' cssClass=''}
                    {formintinput id='maxSizeOfPoster' group='config' maxLength=255 __title='Enter the max size of poster. Only digits are allowed.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='standardPoster' __text='Standard poster' cssClass=''}
                    {formtextinput id='standardPoster' group='config' maxLength=255 __title='Enter the standard poster.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='youtubeApi' __text='Youtube api' cssClass=''}
                    {formtextinput id='youtubeApi' group='config' maxLength=255 __title='Enter the youtube api.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='channelIds' __text='Channel ids' cssClass=''}
                    {formtextinput id='channelIds' group='config' maxLength=255 __title='Enter the channel ids.'}
                </div>
                <div class="z-formrow">
                    {formlabel for='supportedModules' __text='Supported moduls' cssClass=''}
                    {formtextinput id='supportedModules' group='config' maxLength=255 __title='Enter the supported modules.'}
                </div>
                <div class="z-formrow">
                    {gt text='If this option is enabled, title and description of existing youtube videos will be overridden using the import function.' assign='toolTip'}
                    {formlabel for='overrideVars' __text='Override vars' cssClass='muvideo-form-tooltips ' title=$toolTip}
                    {formcheckbox id='overrideVars' group='config'}
                </div>
            </fieldset>

            <div class="z-buttons z-formbuttons">
                {formbutton commandName='save' __text='Update configuration' class='z-bt-save'}
                {formbutton commandName='cancel' __text='Cancel' class='z-bt-cancel'}
            </div>
        {/muvideoFormFrame}
    {/form}
</div>
{include file='admin/footer.tpl'}
<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        Zikula.UI.Tooltips($$('.muvideo-form-tooltips'));
    });
/* ]]> */
</script>
