{* purpose of this template: inclusion template for display of related playlists *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{checkpermission component='MUVideo:Playlist:' instance='::' level='ACCESS_EDIT' assign='hasAdminPermission'}
{if isset($items) && $items ne null && count($items) gt 0}
<ul class="muvideo-related-item-list playlist">
{foreach name='relLoop' item='item' from=$items}
    {if $hasAdminPermission || $item.workflowState eq 'approved'}
    <li>
    <a href="{modurl modname='MUVideo' type=$lct func='display' ot='playlist' id=$item.id}">{$item->getTitleFromDisplayPattern()}</a>
    </li>
    {/if}
{/foreach}
</ul>
{/if}
