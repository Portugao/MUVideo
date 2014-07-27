{* purpose of this template: module configuration *}
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
            </fieldset>

            <div class="z-buttons z-formbuttons">
                {formbutton commandName='save' __text='Update configuration' class='z-bt-save'}
                {formbutton commandName='cancel' __text='Cancel' class='z-bt-cancel'}
            </div>
        {/muvideoFormFrame}
    {/form}
</div>
{include file='admin/footer.tpl'}
