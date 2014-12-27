{* Purpose of this template: Display collections in text mailings *}
{foreach item='collection' from=$items}
{$collection->getTitleFromDisplayPattern()}
{modurl modname='MUVideo' type='user' func='display' ot='collection' id=$collection.id fqurl=true}
-----
{foreachelse}
{gt text='No collections found.'}
{/foreach}
