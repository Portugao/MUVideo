{* purpose of this template: collections view json view *}
{muvideoTemplateHeaders contentType='application/json'}[
{foreach item='collection' from=$items name='collections'}
    {if not $smarty.foreach.collections.first},{/if}
    {$collection->toJson()}
{/foreach}
]
