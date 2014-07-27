{* purpose of this template: movies xml inclusion template *}
<movie id="{$item.id}" createdon="{$item.createdDate|dateformat}" updatedon="{$item.updatedDate|dateformat}">
    <id>{$item.id}</id>
    <title><![CDATA[{$item.title}]]></title>
    <description><![CDATA[{$item.description}]]></description>
    <uploadOfMovie{if $item.uploadOfMovie ne ''} extension="{$item.uploadOfMovieMeta.extension}" size="{$item.uploadOfMovieMeta.size}" isImage="{if $item.uploadOfMovieMeta.isImage}true{else}false{/if}"{if $item.uploadOfMovieMeta.isImage} width="{$item.uploadOfMovieMeta.width}" height="{$item.uploadOfMovieMeta.height}" format="{$item.uploadOfMovieMeta.format}"{/if}{/if}>{$item.uploadOfMovie}</uploadOfMovie>
    <urlOfYoutube>{$item.urlOfYoutube}</urlOfYoutube>
    <poster{if $item.poster ne ''} extension="{$item.posterMeta.extension}" size="{$item.posterMeta.size}" isImage="{if $item.posterMeta.isImage}true{else}false{/if}"{if $item.posterMeta.isImage} width="{$item.posterMeta.width}" height="{$item.posterMeta.height}" format="{$item.posterMeta.format}"{/if}{/if}>{$item.poster}</poster>
    <workflowState>{$item.workflowState|muvideoObjectState:false|lower}</workflowState>
    <collection>{if isset($item.Collection) && $item.Collection ne null}{$item.Collection->getTitleFromDisplayPattern()|default:''}{/if}</collection>
</movie>
