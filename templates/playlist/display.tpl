{* purpose of this template: playlists display view *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{include file="`$lct`/header.tpl"}
<div class="muvideo-playlist muvideo-display">
    {gt text='Playlist' assign='templateTitle'}
    {assign var='templateTitle' value=$playlist->getTitleFromDisplayPattern()|default:$templateTitle}
    {pagesetvar name='title' value=$templateTitle|@html_entity_decode}
    {if $lct eq 'admin'}
        <div class="z-admin-content-pagetitle">
            {icon type='display' size='small' __alt='Details'}
            <h3>{$templateTitle|notifyfilters:'muvideo.filter_hooks.playlists.filter'}{icon id="itemActions`$playlist.id`Trigger" type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}
            </h3>
        </div>
    {else}
        <h2>{$templateTitle|notifyfilters:'muvideo.filter_hooks.playlists.filter'}{icon id="itemActions`$playlist.id`Trigger" type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}
        </h2>
    {/if}


    <dl>
        <dt>{gt text='Title'}</dt>
        <dd>{$playlist.title}</dd>
        <dt>{gt text='Description'}</dt>
        <dd>{$playlist.description}</dd>
        <dt>{gt text='Url of youtube playlist'}</dt>
        <dd>{if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
        <a href="{$playlist.urlOfYoutubePlaylist}" title="{gt text='Visit this page'}">{icon type='url' size='extrasmall' __alt='Homepage'}</a>
        <br />YOUTUBEPLAYLIST[{$playlist.id}]
        {else}
          {$playlist.urlOfYoutubePlaylist}
        {/if}
        </dd>
        <dt>{gt text='Collection'}</dt>
        <dd>
        {if isset($playlist.collection) && $playlist.collection ne null}
          {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
          <a href="{modurl modname='MUVideo' type=$lct func='display' ot='collection'  id=$playlist.collection.id}">{strip}
            {$playlist.collection->getTitleFromDisplayPattern()}
          {/strip}</a>
          <a id="collectionItem{$playlist.collection.id}Display" href="{modurl modname='MUVideo' type=$lct func='display' ot='collection'  id=$playlist.collection.id theme='Printer' forcelongurl=true}" title="{gt text='Open quick view window'}" class="z-hide">{icon type='view' size='extrasmall' __alt='Quick view'}</a>
          <script type="text/javascript">
          /* <![CDATA[ */
              document.observe('dom:loaded', function() {
                  mUMUVideoInitInlineWindow($('collectionItem{{$playlist.collection.id}}Display'), '{{$playlist.collection->getTitleFromDisplayPattern()|replace:"'":""}}');
              });
          /* ]]> */
          </script>
          {else}
            {$playlist.collection->getTitleFromDisplayPattern()}
          {/if}
        {else}
            {gt text='Not set.'}
        {/if}
        </dd>
        
    </dl>
    {include file='helper/includeStandardFieldsDisplay.tpl' obj=$playlist}

    {if !isset($smarty.get.theme) || $smarty.get.theme ne 'Printer'}
        {* include display hooks *}
        {notifydisplayhooks eventname='muvideo.ui_hooks.playlists.display_view' id=$playlist.id urlobject=$currentUrlObject assign='hooks'}
        {foreach name='hookLoop' key='providerArea' item='hook' from=$hooks}
            {if $providerArea ne 'provider.scribite.ui_hooks.editor'}{* fix for #664 *}
                {$hook}
            {/if}
        {/foreach}
        {if count($playlist._actions) gt 0}
            <p id="itemActions{$playlist.id}">
                {foreach item='option' from=$playlist._actions}
                    <a href="{$option.url.type|muvideoActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}" class="z-icon-es-{$option.icon}">{$option.linkText|safetext}</a>
                {/foreach}
            </p>
            <script type="text/javascript">
            /* <![CDATA[ */
                document.observe('dom:loaded', function() {
                    mUMUVideoInitItemActions('playlist', 'display', 'itemActions{{$playlist.id}}');
                });
            /* ]]> */
            </script>
        {/if}
    {/if}
</div>
{include file="`$lct`/footer.tpl"}
