{* purpose of this template: movies view xml view *}
{muvideoTemplateHeaders contentType='text/xml'}<?xml version="1.0" encoding="{charset}" ?>
<movies>
{foreach item='item' from=$items}
    {include file='movie/include.xml'}
{foreachelse}
    <noMovie />
{/foreach}
</movies>
