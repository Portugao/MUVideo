{* Purpose of this template: Display movies within an external context *}
{foreach item='movie' from=$items}
    <h3>{$movie->getTitleFromDisplayPattern()}</h3>
    <p><a href="{modurl modname='MUVideo' type='user' ot='movie' func='display'  id=$$objectType.id}">{gt text='Read more'}</a>
    </p>
{/foreach}
