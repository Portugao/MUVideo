{* Purpose of this template: Display playlists within an external context *}
{foreach item='playlist' from=$items}
    <h3>{$playlist->getTitleFromDisplayPattern()}</h3>
    <p><a href="{modurl modname='MUVideo' type='user' ot='playlist' func='display'  id=$playlist.id}">{gt text='Read more'}</a>
    </p>
{/foreach}
