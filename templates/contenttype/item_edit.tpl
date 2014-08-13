{* Purpose of this template: edit view of specific item detail view content type *}

<div style="margin-left: 80px">
    <div class="z-formrow">
        {formlabel for='mUVideoObjectType' __text='Object type'}
            {muvideoObjectTypeSelector assign='allObjectTypes'}
            {formdropdownlist id='mUVideoObjectType' dataField='objectType' group='data' mandatory=true items=$allObjectTypes}
            <span class="z-sub z-formnote">{gt text='If you change this please save the element once to reload the parameters below.'}</span>
    </div>
    <div{* class="z-formrow"*}>
        <p>{gt text='Please select your item here. You can resort the dropdown list and reduce it\'s entries by applying filters. On the right side you will see a preview of the selected entry.'}</p>
        {muvideoItemSelector id='id' group='data' objectType=$objectType}
    </div>
    {if $objectType eq 'movie'}
    <div>
        {formlabel for='widthOfMovie' __text='Width of movie'}        
        {formintinput id='widthOfMovie' dataField='moviewidth' group='data'}
        {formlabel for='heightOfMovie' __text='Height of movie'}
        {formintinput id='heightOfMovie' dataField='movieheight' group='data'}
    </div>
    {/if}
    <div{* class="z-formrow"*}>
        {formradiobutton id='linkButton' value='link' dataField='displayMode' group='data' mandatory=1}
        {formlabel for='linkButton' __text='Link to object'}
        {formradiobutton id='embedButton' value='embed' dataField='displayMode' group='data' mandatory=1}
        {formlabel for='embedButton' __text='Embed object display'}
    </div>
</div>
