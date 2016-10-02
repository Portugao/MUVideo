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
            {formtabbedpanelset}
                {gt text='Variables' assign='tabTitle'}
                {formtabbedpanel title=$tabTitle}
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
                            {formlabel for='supportedModules' __text='Supported modules' cssClass=''}
                            {formtextinput id='supportedModules' group='config' maxLength=255 __title='Enter the supported modules.'}
                        </div>
                        <div class="z-formrow">
                            {gt text='If this option is enabled, title and description of existing youtube videos will be overridden using the import function.' assign='toolTip'}
                            {formlabel for='overrideVars' __text='Override vars' cssClass='muvideo-form-tooltips ' title=$toolTip}
                            {formcheckbox id='overrideVars' group='config'}
                        </div>
                    </fieldset>
                {/formtabbedpanel}
                {gt text='Images' assign='tabTitle'}
                {formtabbedpanel title=$tabTitle}
                    <fieldset>
                        <legend>{$tabTitle}</legend>
                    
                        <p class="z-confirmationmsg">{gt text='Here you can define shrinking behaviour for huge images.'|nl2br}</p>
                    
                        <div class="z-formrow">
                            {gt text='Whether to enable shrinking to maximum image dimensions. The original image is not stored.' assign='toolTip'}
                            {formlabel for='enableShrinkingForMoviePoster' __text='Enable shrinking for movie poster' cssClass='muvideo-form-tooltips ' title=$toolTip}
                            {formcheckbox id='enableShrinkingForMoviePoster' group='config' cssClass='shrink-enabler'}
                        </div>
                        <div class="z-formrow shrinkdimension-movieposter">
                            {gt text='The maximum image width.' assign='toolTip'}
                            {formlabel for='shrinkWidthMoviePoster' __text='Shrink width movie poster' cssClass='muvideo-form-tooltips ' title=$toolTip}
                            <div>
                                {formintinput id='shrinkWidthMoviePoster' group='config' size=8 maxLength=4 __title='themaximumimagewidth'}
                                 {gt text='pixels'}
                            </div>
                        </div>
                        <div class="z-formrow shrinkdimension-movieposter">
                            {gt text='The maximum image height.' assign='toolTip'}
                            {formlabel for='shrinkHeightMoviePoster' __text='Shrink height movie poster' cssClass='muvideo-form-tooltips ' title=$toolTip}
                            <div>
                                {formintinput id='shrinkHeightMoviePoster' group='config' size=8 maxLength=4 __title='themaximumimageheight'}
                                 {gt text='pixels'}
                            </div>
                        </div>
                    </fieldset>
                {/formtabbedpanel}
            {/formtabbedpanelset}

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
    function muvideoToggleShrinkSettings(fieldName)
    {
        $$('.shrinkdimension-' + fieldName.toLowerCase()).each(function(elem, index) {
            if ($('enableShrinkingFor' + fieldName).checked == true) {
                elem.removeClassName('z-hide');
            } else {
                elem.addClassName('z-hide');
            }
        });
    }

    document.observe('dom:loaded', function() {
        Zikula.UI.Tooltips($$('.muvideo-form-tooltips'));
        $$('.shrink-enabler').each(function(elem, index) {
            elem.observe('click', muvideoToggleShrinkSettings(elem.getAttribute('id').replace('enableShrinkingFor', '')));
            elem.observe('keyup', muvideoToggleShrinkSettings(elem.getAttribute('id').replace('enableShrinkingFor', '')));
            muvideoToggleShrinkSettings(elem.getAttribute('id').replace('enableShrinkingFor', ''));
        });
    });
/* ]]> */
</script>
