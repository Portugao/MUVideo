{* Purpose of this template: Display movies within an external context *}
<dl>
    {foreach item='movie' from=$items}
        <dt>{$movie->getTitleFromDisplayPattern()}</dt>
        {if $movie.description}
            <dd>{$movie.description|strip_tags|truncate:200:'&hellip;'}</dd>
        {/if}
        <dd><a href="{modurl modname='MUVideo' type='user' func='display' id=$movie.id}">{gt text='Read more'}</a>
        </dd>
    {foreachelse}
        <dt>{gt text='No entries found.'}</dt>
    {/foreach}
</dl>
