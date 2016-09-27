{* purpose of this template: playlists atom feed *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{muvideoTemplateHeaders contentType='application/atom+xml'}<?xml version="1.0" encoding="{charset assign='charset'}{if $charset eq 'ISO-8859-15'}ISO-8859-1{else}{$charset}{/if}" ?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title type="text">{gt text='Latest playlists'}</title>
    <subtitle type="text">{gt text='A direct feed showing the list of playlists'} - {$modvars.ZConfig.slogan}</subtitle>
    <author>
        <name>{$modvars.ZConfig.sitename}</name>
    </author>
{assign var='amountOfItems' value=$items|@count}
{if $amountOfItems gt 0}
{capture assign='uniqueID'}tag:{$baseurl|replace:'http://':''|replace:'/':''},{$items[0].createdDate|dateformat:'%Y-%m-%d'}:{modurl modname='MUVideo' type=$lct func='view' ot='playlist'}{/capture}
    <id>{$uniqueID}</id>
    <updated>{$items[0].updatedDate|dateformat:'%Y-%m-%dT%H:%M:%SZ'}</updated>
{/if}
<link rel="alternate" type="text/html" hreflang="{lang}" href="{modurl modname='MUVideo' type=$lct func='main' fqurl=true}" />
<link rel="self" type="application/atom+xml" href="{php}echo substr(\System::getBaseUrl(), 0, strlen(\System::getBaseUrl())-1);{/php}{getcurrenturi}" />
<rights>Copyright (c) {php}echo date('Y');{/php}, {$baseurl}</rights>

{foreach item='playlist' from=$items}
    <entry>
        <title type="html">{$playlist->getTitleFromDisplayPattern()|notifyfilters:'muvideo.filterhook.playlists'}</title>
        <link rel="alternate" type="text/html" href="{modurl modname='MUVideo' type=$lct func='view' ot='playlist' fqurl=true}" />
        {capture assign='uniqueID'}tag:{$baseurl|replace:'http://':''|replace:'/':''},{$playlist.createdDate|dateformat:'%Y-%m-%d'}:{modurl modname='MUVideo' type=$lct func='view' ot='playlist'}{/capture}
        <id>{$uniqueID}</id>
        {if isset($playlist.updatedDate) && $playlist.updatedDate ne null}
            <updated>{$playlist.updatedDate|dateformat:'%Y-%m-%dT%H:%M:%SZ'}</updated>
        {/if}
        {if isset($playlist.createdDate) && $playlist.createdDate ne null}
            <published>{$playlist.createdDate|dateformat:'%Y-%m-%dT%H:%M:%SZ'}</published>
        {/if}
        {if isset($playlist.createdUserId)}
            {usergetvar name='uname' uid=$playlist.createdUserId assign='cr_uname'}
            {usergetvar name='name' uid=$playlist.createdUserId assign='cr_name'}
            <author>
               <name>{$cr_name|default:$cr_uname}</name>
               <uri>{usergetvar name='_UYOURHOMEPAGE' uid=$playlist.createdUserId assign='homepage'}{$homepage|default:'-'}</uri>
               <email>{usergetvar name='email' uid=$playlist.createdUserId}</email>
            </author>
        {/if}

        <summary type="html">
            <![CDATA[
            {$playlist.description|truncate:150:"&hellip;"|default:'-'}
            ]]>
        </summary>
        <content type="html">
            <![CDATA[
            {$playlist.title|replace:'<br>':'<br />'}
            ]]>
        </content>
    </entry>
{/foreach}
</feed>
