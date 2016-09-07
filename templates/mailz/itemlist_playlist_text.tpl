{* Purpose of this template: Display playlists in text mailings *}
{foreach item='playlist' from=$items}
{$playlist->getTitleFromDisplayPattern()}
{modurl modname='MUVideo' type='user' func='display' ot='playlist' id=$playlist.id fqurl=true}
-----
{foreachelse}
{gt text='No playlists found.'}
{/foreach}
