{* Purpose of this template: Display one certain movie within an external context *}
<div id="movie{$movie.id}" class="muvideo-external-movie">
{if $displayMode eq 'link'}
    <p class="muvideo-external-link">
    <a href="{modurl modname='MUVideo' type='user' func='display' id=$movie.id}" title="{$movie->getTitleFromDisplayPattern()|replace:"\"":""}">
    {$movie->getTitleFromDisplayPattern()|notifyfilters:'muvideo.filter_hooks.movies.filter'}
    </a>
    </p>
{/if}
{checkpermissionblock component='MUVideo::' instance='::' level='ACCESS_EDIT'}
    {if $displayMode eq 'embed'}
        <p class="muvideo-external-title">
            <strong>{$movie->getTitleFromDisplayPattern()|notifyfilters:'muvideo.filter_hooks.movies.filter'}</strong>
        </p>
    {/if}
{/checkpermissionblock}

{if $displayMode eq 'link'}
{elseif $displayMode eq 'embed'}
    <div class="muvideo-external-snippet">
        {if $movie.poster ne ''}
          <a href="{$movie.posterFullPathURL}" title="{$movie->getTitleFromDisplayPattern()|replace:"\"":""}"{if $movie.posterMeta.isImage} rel="imageviewer[movie]"{/if}>
          {if $movie.posterMeta.isImage}
              {thumb image=$movie.posterFullPath objectid="movie-`$movie.id`" preset=$movieThumbPresetPoster tag=true img_alt=$movie->getTitleFromDisplayPattern()}
          {else}
              {gt text='Download'} ({$movie.posterMeta.size|muvideoGetFileSize:$movie.posterFullPath:false:false})
          {/if}
          </a>
        {else}&nbsp;{/if}
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
            {if $movie.description ne ''}{$movie.description}<br />{/if}
        </p>
    *}
{/if}
</div>
