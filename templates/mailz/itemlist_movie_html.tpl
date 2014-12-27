{* Purpose of this template: Display movies in html mailings *}
{*
<ul>
{foreach item='movie' from=$items}
    <li>
        <a href="{modurl modname='MUVideo' type='user' func='display' ot='movie' id=$movie.id fqurl=true}
        ">{$movie->getTitleFromDisplayPattern()}
        </a>
    </li>
{foreachelse}
    <li>{gt text='No movies found.'}</li>
{/foreach}
</ul>
*}

{include file='contenttype/itemlist_movie_display_description.tpl'}
