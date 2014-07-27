{* Purpose of this template: Display one certain collection within an external context *}
<div id="collection{$collection.id}" class="muvideo-external-collection">
{if $displayMode eq 'link'}
    <p class="muvideo-external-link">
    <a href="{modurl modname='MUVideo' type='user' func='display' id=$collection.id}" title="{$collection->getTitleFromDisplayPattern()|replace:"\"":""}">
    {$collection->getTitleFromDisplayPattern()|notifyfilters:'muvideo.filter_hooks.collections.filter'}
    </a>
    </p>
{/if}
{checkpermissionblock component='MUVideo::' instance='::' level='ACCESS_EDIT'}
    {if $displayMode eq 'embed'}
        <p class="muvideo-external-title">
            <strong>{$collection->getTitleFromDisplayPattern()|notifyfilters:'muvideo.filter_hooks.collections.filter'}</strong>
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
            {if $collection.description ne ''}{$collection.description}<br />{/if}
        </p>
    *}
{/if}
</div>
