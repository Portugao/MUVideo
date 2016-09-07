{* purpose of this template: collections display view *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{include file="`$lct`/header.tpl"}
<div class="muvideo-collection muvideo-display with-rightbox">
    {gt text='Collection' assign='templateTitle'}
    {assign var='templateTitle' value=$collection->getTitleFromDisplayPattern()|default:$templateTitle}
    {pagesetvar name='title' value=$templateTitle|@html_entity_decode}
    {if $lct eq 'admin'}
        <div class="z-admin-content-pagetitle">
            {icon type='display' size='small' __alt='Details'}
            <h3>{$templateTitle|notifyfilters:'muvideo.filter_hooks.collections.filter'}{icon id="itemActions`$collection.id`Trigger" type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}
            </h3>
        </div>
    {else}
        <h2>{$templateTitle|notifyfilters:'muvideo.filter_hooks.collections.filter'}{icon id="itemActions`$collection.id`Trigger" type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}
        </h2>
    {/if}

    {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
        <div class="muvideo-rightbox">
            {if $lct eq 'admin'}
                <h4>{gt text='Movies'}</h4>
            {else}
                <h3>{gt text='Movies'}</h3>
            {/if}
            
            {if isset($collection.movie) && $collection.movie ne null}
                {include file='movie/includeDisplayItemListMany.tpl' items=$collection.movie}
            {/if}
            
            {assign var='permLevel' value='ACCESS_EDIT'}
            {if $lct eq 'admin'}
                {assign var='permLevel' value='ACCESS_ADMIN'}
            {/if}
            {checkpermission component='MUVideo:Collection:' instance='`$collection.id`::' level=$permLevel assign='mayManage'}
            {if $mayManage || (isset($uid) && isset($collection.createdUserId) && $collection.createdUserId eq $uid)}
            <p class="managelink">
                {gt text='Create movie' assign='createTitle'}
                <a href="{modurl modname='MUVideo' type=$lct func='edit' ot='movie' collection="`$collection.id`" returnTo="`$lct`DisplayCollection"'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
            </p>
            {/if}
            {if $lct eq 'admin'}
                <h4>{gt text='Playlists'}</h4>
            {else}
                <h3>{gt text='Playlists'}</h3>
            {/if}
            
            {if isset($collection.playlists) && $collection.playlists ne null}
                {include file='playlist/includeDisplayItemListMany.tpl' items=$collection.playlists}
            {/if}
            
            {assign var='permLevel' value='ACCESS_EDIT'}
            {if $lct eq 'admin'}
                {assign var='permLevel' value='ACCESS_ADMIN'}
            {/if}
            {checkpermission component='MUVideo:Collection:' instance='`$collection.id`::' level=$permLevel assign='mayManage'}
            {if $mayManage || (isset($uid) && isset($collection.createdUserId) && $collection.createdUserId eq $uid)}
            <p class="managelink">
                {gt text='Create playlist' assign='createTitle'}
                <a href="{modurl modname='MUVideo' type=$lct func='edit' ot='playlist' collection="`$collection.id`" returnTo="`$lct`DisplayCollection"'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
            </p>
            {/if}
        </div>
    {/if}

    <dl>
        <dt>{gt text='Title'}</dt>
        <dd>{$collection.title}</dd>
        <dt>{gt text='Description'}</dt>
        <dd>{$collection.description}</dd>
        
    </dl>
    
    {checkpermission component="MUVideo::" instance=".*" level="ACCESS_ADD" assign="auth"}
    {if $auth eq true}
    	<a class="z-icon-es-add" href="{modurl modname='MUVideo' type='user' func='getVideos' ot='collection' collectionId=$collection.id}">{gt text='Get videos into this collection'}</a>
	{/if}
    {include file='helper/includeCategoriesDisplay.tpl' obj=$collection}
    {include file='helper/includeStandardFieldsDisplay.tpl' obj=$collection}

    {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
        {* include display hooks *}
        {notifydisplayhooks eventname='muvideo.ui_hooks.collections.display_view' id=$collection.id urlobject=$currentUrlObject assign='hooks'}
        {foreach name='hookLoop' key='providerArea' item='hook' from=$hooks}
            {if $providerArea ne 'provider.scribite.ui_hooks.editor'}{* fix for #664 *}
                {$hook}
            {/if}
        {/foreach}
        {if count($collection._actions) gt 0}
            <p id="itemActions{$collection.id}">
                {foreach item='option' from=$collection._actions}
                    <a href="{$option.url.type|muvideoActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" class="z-icon-es-{$option.icon}">{$option.linkText|safetext}</a>
                {/foreach}
            </p>
            <script type="text/javascript">
            /* <![CDATA[ */
                document.observe('dom:loaded', function() {
                    mUMUVideoInitItemActions('collection', 'display', 'itemActions{{$collection.id}}');
                });
            /* ]]> */
            </script>
        {/if}
        <br style="clear: right" />
    {/if}
</div>
{include file="`$lct`/footer.tpl"}
