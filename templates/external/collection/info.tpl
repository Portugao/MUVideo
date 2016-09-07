{* Purpose of this template: Display item information for previewing from other modules *}
<dl id="collection{$collection.id}">
<dt>{$collection->getTitleFromDisplayPattern()|notifyfilters:'muvideo.filter_hooks.collections.filter'}</dt>
{% if collection.description is not empty %}<dd>{{ collection.description }}</dd>{% endif %}
<dd>{assignedcategorieslist categories=$collection.categories doctrine2=true}</dd>
</dl>
