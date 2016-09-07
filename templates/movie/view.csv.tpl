{* purpose of this template: movies view csv view *}
{muvideoTemplateHeaders contentType='text/comma-separated-values; charset=iso-8859-15' asAttachment=true fileName='Movies.csv'}
{strip}"{gt text='Title'}";"{gt text='Description'}";"{gt text='Upload of movie'}";"{gt text='Url of youtube'}";"{gt text='Poster'}";"{gt text='Width of movie'}";"{gt text='Height of movie'}";"{gt text='Workflow state'}"
;"{gt text='Collection'}"
{/strip}
{foreach item='movie' from=$items}
{strip}
    "{$movie.title}";"{$movie.description}";"{$movie.uploadOfMovie}";"{$movie.urlOfYoutube}";"{$movie.poster}";"{$movie.widthOfMovie}";"{$movie.heightOfMovie}";"{$movie.workflowState|muvideoObjectState:false|lower}"
    ;"{if isset($movie.collection) && $movie.collection ne null}{$movie.collection->getTitleFromDisplayPattern()|default:''}{/if}"
{/strip}
{/foreach}
