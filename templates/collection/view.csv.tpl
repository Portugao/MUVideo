{* purpose of this template: collections view csv view *}
{muvideoTemplateHeaders contentType='text/comma-separated-values; charset=iso-8859-15' asAttachment=true filename='Collections.csv'}
{strip}"{gt text='Title'}";"{gt text='Description'}";"{gt text='Workflow state'}"
;"{gt text='Movie'}"
{/strip}
{foreach item='collection' from=$items}
{strip}
    "{$collection.title}";"{$collection.description}";"{$item.workflowState|muvideoObjectState:false|lower}"
    ;"
        {if isset($collection.Movie) && $collection.Movie ne null}
            {foreach name='relationLoop' item='relatedItem' from=$collection.Movie}
            {$relatedItem->getTitleFromDisplayPattern()|default:''}{if !$smarty.foreach.relationLoop.last}, {/if}
            {/foreach}
        {/if}
    "
{/strip}
{/foreach}
