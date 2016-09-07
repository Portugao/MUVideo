{* Purpose of this template: Display playlists in html mailings *}
{*
<ul>
{foreach item='playlist' from=$items}
    <li>
        <a href="{modurl modname='MUVideo' type='user' func='display' ot='playlist' id=$playlist.id fqurl=true}
        ">{$playlist->getTitleFromDisplayPattern()}
        </a>
    </li>
{foreachelse}
    <li>{gt text='No playlists found.'}</li>
{/foreach}
</ul>
*}

{include file='contenttype/itemlist_playlist_display_description.tpl'}
