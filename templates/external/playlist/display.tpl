{* Purpose of this template: Display one certain playlist within an external context *}
<div id="playlist{$playlist.id}" class="muvideo-external-playlist">
{if $displayMode eq 'link'}
    <p class="muvideo-external-link">
    <a href="{modurl modname='MUVideo' type='user' func='display' ot='playlist'  id=$playlist.id}" title="{$playlist->getTitleFromDisplayPattern()|replace:"\"":""}">
    {$playlist->getTitleFromDisplayPattern()|notifyfilters:'muvideo.filter_hooks.playlists.filter'}
    </a>
    </p>
{/if}
{checkpermissionblock component='MUVideo::' instance='::' level='ACCESS_EDIT'}
    {* for normal users without edit permission show only the actual file per default *}
    {if $displayMode eq 'embed'}
        <p class="muvideo-external-title">
            <strong>{$playlist->getTitleFromDisplayPattern()|notifyfilters:'muvideo.filter_hooks.playlists.filter'}</strong>
        </p>
    {/if}
{/checkpermissionblock}

{if $displayMode eq 'link'}
{elseif $displayMode eq 'embed'}
    <div class="muvideo-external-snippet">
        &nbsp;
    </div>

    {* you can distinguish the context like this: *}
    {*if $source eq 'contentType'}
        ...
    {elseif $source eq 'scribite'}
        ...
    {/if*}

    {* you can enable more details about the item: *}
    {*
        <p class="muvideo-external-description">
            {if $playlist.description ne ''}{$playlist.description}<br />{/if}
        </p>
    *}
{/if}
</div>
