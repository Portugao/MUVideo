{* Purpose of this template: Display movies in text mailings *}
{foreach item='movie' from=$items}
{$movie->getTitleFromDisplayPattern()}
{modurl modname='MUVideo' type='user' func='display' ot='movie'  id=$$objectType.id fqurl=true}
-----
{foreachelse}
{gt text='No movies found.'}
{/foreach}
