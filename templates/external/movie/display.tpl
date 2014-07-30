{* Purpose of this template: Display one certain movie within an external context *}
{pageaddvar name='javascript' value='jquery'}
{pageaddvar name='javascript' value='jquery-ui'}
{if $movie.urlOfYoutube eq ''}
{pageaddvar name='javascript' value='modules/MUVideo/lib/vendor/projekktor/projekktor-1.3.09.min.js'}
{pageaddvar name='stylesheet' value='modules/MUVideo/lib/vendor/projekktor/themes/maccaco/projekktor.style.css'}
{/if}
{if $movie.urlOfYoutube ne ''}
{pageaddvar name='javascript' value='modules/MUVideo/lib/vendor/lazyYT/lazyYT.js'}
{pageaddvar name='stylesheet' value='modules/MUVideo/lib/vendor/lazyYT/lazyYT.css'}
{/if}
<div id="movie{$movie.id}" class="muvideo-external-movie">
{if $displayMode eq 'link'}
    <p class="muvideo-external-link">
    <a href="{modurl modname='MUVideo' type='user' func='display' ot='movie' id=$movie.id}" title="{$movie->getTitleFromDisplayPattern()|replace:"\"":""}">
    {$movie->getTitleFromDisplayPattern()|notifyfilters:'muvideo.filter_hooks.movies.filter'}
    </a>
    </p>
{/if}
{checkpermissionblock component='MUVideo::' instance='::' level='ACCESS_EDIT'}
    {if $displayMode eq 'embed'}
        <p class="muvideo-external-title">
            <strong>{$movie->getTitleFromDisplayPattern()|notifyfilters:'muvideo.filter_hooks.movies.filter'}</strong>
        </p>
    {/if}
{/checkpermissionblock}

{if $displayMode eq 'link'}
{elseif $displayMode eq 'embed'}
    <div class="muvideo-external-snippet">
       {* {if $movie.poster ne ''}
          <a href="{$movie.posterFullPathURL}" title="{$movie->getTitleFromDisplayPattern()|replace:"\"":""}"{if $movie.posterMeta.isImage} rel="imageviewer[movie]"{/if}>
          {if $movie.posterMeta.isImage}
              {thumb image=$movie.posterFullPath objectid="movie-`$movie.id`" preset=$movieThumbPresetPoster tag=true img_alt=$movie->getTitleFromDisplayPattern()}
          {else}
              {gt text='Download'} ({$movie.posterMeta.size|muvideoGetFileSize:$movie.posterFullPath:false:false})
          {/if}
          </a>
        {else}&nbsp;{/if} *}
        {if $movie.urlOfYoutube ne ''}
        <div class="lazyYT" data-youtube-id={$youtubeId} data-width="600" data-height="450">loading...</div>
        {/if}
        {if $movie.urlOfYoutube eq ''}
            <video id="player_a" class="projekktor" poster="{$movie.poster}" title="{$movie.title}" width="640" height="385" controls>             
                <source src="{$movie.uploadOfMovieFullPathUrl}" />         
            </video>
        {/if}
    </div>

    {* you can distinguish the context like this: *}
    {*if $source eq 'contentType'}
        ...
    {elseif $source eq 'scribite'}
        ...
    {/if*}

    {* you can enable more details about the item: *}
    {*
        <p class="muvideo-external-description">
            {if $movie.description ne ''}{$movie.description}<br />{/if}
        </p>
    *}
{/if}
</div>
<script type="text/javascript">
/* <![CDATA[ */
        var MU = jQuery.noConflict(); 
               
        MU(document).ready(function() {
        {{if $movie.urlOfYoutube ne ''}}
        jQuery('.lazyYT').lazyYT(); 
        {{/if}} 
        {{if $movie.urlOfYoutube eq ''}}           
        projekktor('#player_a', {
        poster: '{{$movie.poster}}',
        title: '{{$movie.title}}',
        playerFlashMP4: '/modules/MUVideo/lib/vendor/projekktor/swf/StrobeMediaPlayback/StrobeMediaPlayback.swf',
        playerFlashMP3: '/modules/MUVideo/lib/vendor/projekktor/swf/StrobeMediaPlayback/StrobeMediaPlayback.swf',
        width: 640,
        height: 385,
        playlist: [
            {
            0: {src: {{if $movie.urlOfYoutube ne ''}}'{{$movie.urlOfYoutube}}', type: 'video/youtube'{{else}}'{{$movie.uploadOfMovieFullPathURL}}'{{/if}}}
            }
        ]    
        });
        {{/if}}
    });
            /* ]]> */
            </script>
