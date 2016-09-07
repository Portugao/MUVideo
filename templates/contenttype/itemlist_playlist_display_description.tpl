{* Purpose of this template: Display playlists within an external context *}
<dl>
    {foreach item='playlist' from=$items}
        <dt>{$playlist->getTitleFromDisplayPattern()}</dt>
        {if $playlist.description}
            <dd>{$playlist.description|strip_tags|truncate:200:'&hellip;'}</dd>
        {/if}
        <dd><a href="{modurl modname='MUVideo' type='user' ot='playlist' func='display'  id=$playlist.id}">{gt text='Read more'}</a>
        </dd>
    {foreachelse}
        <dt>{gt text='No entries found.'}</dt>
    {/foreach}
</dl>
