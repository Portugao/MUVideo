{* purpose of this template: collections view csv view *}
{muvideoTemplateHeaders contentType='text/comma-separated-values; charset=iso-8859-15' asAttachment=true fileName='Collections.csv'}
{strip}"{gt text='Title'}";"{gt text='Description'}";"{gt text='Workflow state'}"
;"{gt text='Movie'}";"{gt text='Playlists'}"
{/strip}
{foreach item='collection' from=$items}
{strip}
    "{$collection.title}";"{$collection.description}";"{$collection.workflowState|muvideoObjectState:false|lower}"
    ;"
    {if isset($collection.movie) && $collection.movie ne null}
        {foreach name='relationLoop' item='relatedItem' from=$collection.movie}
        {$relatedItem->getTitleFromDisplayPattern()|default:''}{if !$smarty.foreach.relationLoop.last}, {/if}
        {/foreach}
    {/if}
    ";"
    {if isset($collection.playlists) && $collection.playlists ne null}
        {foreach name='relationLoop' item='relatedItem' from=$collection.playlists}
        {$relatedItem->getTitleFromDisplayPattern()|default:''}{if !$smarty.foreach.relationLoop.last}, {/if}
        {/foreach}
    {/if}
    "
{/strip}
{/foreach}
