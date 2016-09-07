{* purpose of this template: playlists list view *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{include file="`$lct`/header.tpl"}
<div class="muvideo-playlist muvideo-view">
    {gt text='Playlist list' assign='templateTitle'}
    {pagesetvar name='title' value=$templateTitle}
    {if $lct eq 'admin'}
        <div class="z-admin-content-pagetitle">
            {icon type='view' size='small' alt=$templateTitle}
            <h3>{$templateTitle}</h3>
        </div>
    {else}
        <h2>{$templateTitle}</h2>
    {/if}

    {if $canBeCreated}
        {checkpermissionblock component='MUVideo:Playlist:' instance='::' level='ACCESS_EDIT'}
            {gt text='Create playlist' assign='createTitle'}
            <a href="{modurl modname='MUVideo' type=$lct func='edit' ot='playlist'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
        {/checkpermissionblock}
    {/if}
    {assign var='own' value=0}
    {if isset($showOwnEntries) && $showOwnEntries eq 1}
        {assign var='own' value=1}
    {/if}
    {assign var='all' value=0}
    {if isset($showAllEntries) && $showAllEntries eq 1}
        {gt text='Back to paginated view' assign='linkTitle'}
        <a href="{modurl modname='MUVideo' type=$lct func='view' ot='playlist'}" title="{$linkTitle}" class="z-icon-es-view">{$linkTitle}</a>
        {assign var='all' value=1}
    {else}
        {gt text='Show all entries' assign='linkTitle'}
        <a href="{modurl modname='MUVideo' type=$lct func='view' ot='playlist' all=1}" title="{$linkTitle}" class="z-icon-es-view">{$linkTitle}</a>
    {/if}

    {include file='playlist/viewQuickNav.tpl' all=$all own=$own workflowStateFilter=false}{* see template file for available options *}

    {if $lct eq 'admin'}
    <form action="{modurl modname='MUVideo' type='playlist' func='handleSelectedEntries' lct=$lct}" method="post" id="playlistsViewForm" class="z-form">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
    {/if}
        <table class="z-datatable">
            <colgroup>
                {if $lct eq 'admin'}
                    <col id="cSelect" />
                {/if}
                <col id="cTitle" />
                <col id="cDescription" />
                <col id="cUrlOfYoutubePlaylist" />
                <col id="cCollection" />
                <col id="cItemActions" />
            </colgroup>
            <thead>
            <tr>
                {if $lct eq 'admin'}
                    <th id="hSelect" scope="col" align="center" valign="middle">
                        <input type="checkbox" id="togglePlaylists" />
                    </th>
                {/if}
                <th id="hTitle" scope="col" class="z-left">
                    {sortlink __linktext='Title' currentsort=$sort modname='MUVideo' type=$lct func='view' sort='title' sortdir=$sdir all=$all own=$own collection=$collection workflowState=$workflowState q=$q pageSize=$pageSize ot='playlist'}
                </th>
                <th id="hDescription" scope="col" class="z-left">
                    {sortlink __linktext='Description' currentsort=$sort modname='MUVideo' type=$lct func='view' sort='description' sortdir=$sdir all=$all own=$own collection=$collection workflowState=$workflowState q=$q pageSize=$pageSize ot='playlist'}
                </th>
                <th id="hUrlOfYoutubePlaylist" scope="col" class="z-left">
                    {sortlink __linktext='Url of youtube playlist' currentsort=$sort modname='MUVideo' type=$lct func='view' sort='urlOfYoutubePlaylist' sortdir=$sdir all=$all own=$own collection=$collection workflowState=$workflowState q=$q pageSize=$pageSize ot='playlist'}
                </th>
                <th id="hCollection" scope="col" class="z-left">
                    {sortlink __linktext='Collection' currentsort=$sort modname='MUVideo' type=$lct func='view' sort='collection' sortdir=$sdir all=$all own=$own collection=$collection workflowState=$workflowState q=$q pageSize=$pageSize ot='playlist'}
                </th>
                <th id="hItemActions" scope="col" class="z-right z-order-unsorted">{gt text='Actions'}</th>
            </tr>
            </thead>
            <tbody>
        
        {foreach item='playlist' from=$items}
            <tr class="{cycle values='z-odd, z-even'}">
                {if $lct eq 'admin'}
                    <td headers="hSelect" align="center" valign="top">
                        <input type="checkbox" name="items[]" value="{$playlist.id}" class="playlists-checkbox" />
                    </td>
                {/if}
                <td headers="hTitle" class="z-left">
                    <a href="{modurl modname='MUVideo' type=$lct func='display' ot='playlist'  id=$playlist.id}" title="{gt text='View detail page'}">{$playlist.title|notifyfilters:'muvideo.filterhook.playlists'}</a>
                </td>
                <td headers="hDescription" class="z-left">
                    {$playlist.description}
                </td>
                <td headers="hUrlOfYoutubePlaylist" class="z-left">
                    <a href="{$playlist.urlOfYoutubePlaylist}" title="{gt text='Visit this page'}">{icon type='url' size='extrasmall' __alt='Homepage'}</a>
                </td>
                <td headers="hCollection" class="z-left">
                    {if isset($playlist.collection) && $playlist.collection ne null}
                        <a href="{modurl modname='MUVideo' type=$lct func='display' ot='collection'  id=$playlist.collection.id}">{strip}
                          {$playlist.collection->getTitleFromDisplayPattern()}
                        {/strip}</a>
                        <a id="collectionItem{$playlist.id}_rel_{$playlist.collection.id}Display" href="{modurl modname='MUVideo' type=$lct func='display' ot='collection'  id=$playlist.collection.id theme='Printer' forcelongurl=true}" title="{gt text='Open quick view window'}" class="z-hide">{icon type='view' size='extrasmall' __alt='Quick view'}</a>
                        <script type="text/javascript">
                        /* <![CDATA[ */
                            document.observe('dom:loaded', function() {
                                mUMUVideoInitInlineWindow($('collectionItem{{$playlist.id}}_rel_{{$playlist.collection.id}}Display'), '{{$playlist.collection->getTitleFromDisplayPattern()|replace:"'":""}}');
                            });
                        /* ]]> */
                        </script>
                    {else}
                        {gt text='Not set.'}
                    {/if}
                </td>
                <td id="itemActions{$playlist.id}" headers="hItemActions" class="z-right z-nowrap z-w02">
                    {if count($playlist._actions) gt 0}
                        {icon id="itemActions`$playlist.id`Trigger" type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}
                        {foreach item='option' from=$playlist._actions}
                            <a href="{$option.url.type|muvideoActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>{icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}</a>
                        {/foreach}
                        <script type="text/javascript">
                        /* <![CDATA[ */
                            document.observe('dom:loaded', function() {
                                mUMUVideoInitItemActions('playlist', 'view', 'itemActions{{$playlist.id}}');
                            });
                        /* ]]> */
                        </script>
                    {/if}
                </td>
            </tr>
        {foreachelse}
            <tr class="z-{if $lct eq 'admin'}admin{else}data{/if}tableempty">
                <td class="z-left" colspan="{if $lct eq 'admin'}6{else}5{/if}">
            {gt text='No playlists found.'}
              </td>
            </tr>
        {/foreach}
        
            </tbody>
        </table>
        
        {if !isset($showAllEntries) || $showAllEntries ne 1}
            {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page' modname='MUVideo' type=$lct func='view' ot='playlist'}
        {/if}
    {if $lct eq 'admin'}
            <fieldset>
                <label for="mUVideoAction">{gt text='With selected playlists'}</label>
                <select id="mUVideoAction" name="action">
                    <option value="">{gt text='Choose action'}</option>
                    <option value="delete" title="{gt text='Delete content permanently.'}">{gt text='Delete'}</option>
                </select>
                <input type="submit" value="{gt text='Submit'}" />
            </fieldset>
        </div>
    </form>
    {/if}

    
    {* here you can activate calling display hooks for the view page if you need it *}
    {*if $lct ne 'admin'}
        {notifydisplayhooks eventname='muvideo.ui_hooks.playlists.display_view' urlobject=$currentUrlObject assign='hooks'}
        {foreach key='providerArea' item='hook' from=$hooks}
            {$hook}
        {/foreach}
    {/if*}
</div>
{include file="`$lct`/footer.tpl"}

<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        {{if $lct eq 'admin'}}
            {{* init the "toggle all" functionality *}}
            if ($('togglePlaylists') != undefined) {
                $('togglePlaylists').observe('click', function (e) {
                    Zikula.toggleInput('playlistsViewForm');
                    e.stop();
                });
            }
        {{/if}}
    });
/* ]]> */
</script>
