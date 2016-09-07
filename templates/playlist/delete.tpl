{* purpose of this template: playlists delete confirmation view *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{include file="`$lct`/header.tpl"}
<div class="muvideo-playlist muvideo-delete">
    {gt text='Delete playlist' assign='templateTitle'}
    {pagesetvar name='title' value=$templateTitle}
    {if $lct eq 'admin'}
        <div class="z-admin-content-pagetitle">
            {icon type='delete' size='small' __alt='Delete'}
            <h3>{$templateTitle}</h3>
        </div>
    {else}
        <h2>{$templateTitle}</h2>
    {/if}

    <p class="z-warningmsg">{gt text='Do you really want to delete this playlist ?'}</p>

    <form class="z-form" action="{modurl modname='MUVideo' type=$lct func='delete' ot='playlist'  id=$playlist.id}" method="post">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
            <input type="hidden" id="confirmation" name="confirmation" value="1" />
            <fieldset>
                <legend>{gt text='Confirmation prompt'}</legend>
                <div class="z-buttons z-formbuttons">
                    {gt text='Delete' assign='deleteTitle'}
                    {button src='14_layer_deletelayer.png' set='icons/small' text=$deleteTitle title=$deleteTitle class='z-btred'}
                    <a href="{modurl modname='MUVideo' type=$lct func='view' ot='playlist'}">{icon type='cancel' size='small' __alt='Cancel' __title='Cancel'} {gt text='Cancel'}</a>
                </div>
            </fieldset>

            {notifydisplayhooks eventname='muvideo.ui_hooks.playlists.form_delete' id="`$playlist.id`" assign='hooks'}
            {foreach key='providerArea' item='hook' from=$hooks}
                <fieldset>
                    {*<legend>{$hookName}</legend>*}
                    {$hook}
                </fieldset>
            {/foreach}
        </div>
    </form>
</div>
{include file="`$lct`/footer.tpl"}
