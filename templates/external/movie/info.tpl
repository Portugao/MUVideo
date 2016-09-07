{* Purpose of this template: Display item information for previewing from other modules *}
<dl id="movie{$movie.id}">
<dt>{$movie->getTitleFromDisplayPattern()|notifyfilters:'muvideo.filter_hooks.movies.filter'}</dt>
<dd>{if $movie.poster ne ''}
<a href="{$movie.posterFullPathURL}" title="{$movie->getTitleFromDisplayPattern()|replace:"\"":""}"{if $movie.posterMeta.isImage} rel="imageviewer[movie]"{/if}>
{if $movie.posterMeta.isImage}
    {thumb image=$movie.posterFullPath objectid="movie-`$movie.id`" preset=$movieThumbPresetPoster tag=true img_alt=$movie->getTitleFromDisplayPattern()}
{else}
    {gt text='Download'} ({$movie.posterMeta.size|muvideoGetFileSize:$movie.posterFullPath:false:false})
{/if}
</a>
{else}&nbsp;{/if}
</dd>
{% if movie.description is not empty %}<dd>{{ movie.description }}</dd>{% endif %}
<dd>{assignedcategorieslist categories=$movie.categories doctrine2=true}</dd>
</dl>
