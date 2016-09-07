{* purpose of this template: playlists view csv view *}
{muvideoTemplateHeaders contentType='text/comma-separated-values; charset=iso-8859-15' asAttachment=true fileName='Playlists.csv'}
{strip}"{gt text='Title'}";"{gt text='Description'}";"{gt text='Url of youtube playlist'}";"{gt text='Workflow state'}"
;"{gt text='Collection'}"
{/strip}
{foreach item='playlist' from=$items}
{strip}
    "{$playlist.title}";"{$playlist.description}";"{$playlist.urlOfYoutubePlaylist}";"{$playlist.workflowState|muvideoObjectState:false|lower}"
    ;"{if isset($playlist.collection) && $playlist.collection ne null}{$playlist.collection->getTitleFromDisplayPattern()|default:''}{/if}"
{/strip}
{/foreach}
