{* purpose of this template: playlists view json view *}
{muvideoTemplateHeaders contentType='application/json'}[
{foreach item='playlist' from=$items name='playlists'}
    {if not $smarty.foreach.playlists.first},{/if}
    {$playlist->toJson()}
{/foreach}
]
