{* purpose of this template: movies view csv view *}
{muvideoTemplateHeaders contentType='text/comma-separated-values; charset=iso-8859-15' asAttachment=true filename='Movies.csv'}
{strip}"{gt text='Title'}";"{gt text='Description'}";"{gt text='Upload of movie'}";"{gt text='Url of youtube'}";"{gt text='Poster'}";"{gt text='Workflow state'}"
;"{gt text='Collection'}"
{/strip}
{foreach item='movie' from=$items}
{strip}
    "{$movie.title}";"{$movie.description}";"{$movie.uploadOfMovie}";"{$movie.urlOfYoutube}";"{$movie.poster}";"{$item.workflowState|muvideoObjectState:false|lower}"
    ;"{if isset($movie.Collection) && $movie.Collection ne null}{$movie.Collection->getTitleFromDisplayPattern()|default:''}{/if}"
{/strip}
{/foreach}
