{* purpose of this template: inclusion template for display of related playlists *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{checkpermission component='MUVideo:Playlist:' instance='::' level='ACCESS_EDIT' assign='hasAdminPermission'}
<h4>
    {$item->getTitleFromDisplayPattern()}
</h4>
