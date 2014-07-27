{* purpose of this template: collections xml inclusion template *}
<collection id="{$item.id}" createdon="{$item.createdDate|dateformat}" updatedon="{$item.updatedDate|dateformat}">
    <id>{$item.id}</id>
    <title><![CDATA[{$item.title}]]></title>
    <description><![CDATA[{$item.description}]]></description>
    <workflowState>{$item.workflowState|muvideoObjectState:false|lower}</workflowState>
    <movie>
    {if isset($item.Movie) && $item.Movie ne null}
        {foreach name='relationLoop' item='relatedItem' from=$item.Movie}
        <movie>{$relatedItem->getTitleFromDisplayPattern()|default:''}</movie>
        {/foreach}
    {/if}
    </movie>
</collection>
