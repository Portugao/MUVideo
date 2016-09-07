{* Purpose of this template: Display item information for previewing from other modules *}
<dl id="playlist{$playlist.id}">
<dt>{$playlist->getTitleFromDisplayPattern()|notifyfilters:'muvideo.filter_hooks.playlists.filter'}</dt>
{% if playlist.description is not empty %}<dd>{{ playlist.description }}</dd>{% endif %}
</dl>
