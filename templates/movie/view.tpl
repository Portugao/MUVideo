{* purpose of this template: movies list view *}
{assign var='lct' value='user'}
{if isset($smarty.get.lct) && $smarty.get.lct eq 'admin'}
    {assign var='lct' value='admin'}
{/if}
{include file="`$lct`/header.tpl"}
<div class="muvideo-movie muvideo-view">
    {gt text='Movie list' assign='templateTitle'}
    {pagesetvar name='title' value=$templateTitle}
    {if $lct eq 'admin'}
        <div class="z-admin-content-pagetitle">
            {icon type='view' size='small' alt=$templateTitle}
            <h3>{$templateTitle}</h3>
        </div>
    {else}
        <h2>{$templateTitle}</h2>
    {/if}

    {if $canBeCreated}
        {checkpermissionblock component='MUVideo:Movie:' instance='::' level='ACCESS_EDIT'}
            {gt text='Create movie' assign='createTitle'}
            <a href="{modurl modname='MUVideo' type=$lct func='edit' ot='movie'}" title="{$createTitle}" class="z-icon-es-add">{$createTitle}</a>
        {/checkpermissionblock}
    {/if}
    {assign var='own' value=0}
    {if isset($showOwnEntries) && $showOwnEntries eq 1}
        {assign var='own' value=1}
    {/if}
    {assign var='all' value=0}
    {if isset($showAllEntries) && $showAllEntries eq 1}
        {gt text='Back to paginated view' assign='linkTitle'}
        <a href="{modurl modname='MUVideo' type=$lct func='view' ot='movie'}" title="{$linkTitle}" class="z-icon-es-view">{$linkTitle}</a>
        {assign var='all' value=1}
    {else}
        {gt text='Show all entries' assign='linkTitle'}
        <a href="{modurl modname='MUVideo' type=$lct func='view' ot='movie' all=1}" title="{$linkTitle}" class="z-icon-es-view">{$linkTitle}</a>
    {/if}

    {include file='movie/viewQuickNav.tpl' all=$all own=$own workflowStateFilter=false}{* see template file for available options *}

    {if $lct eq 'admin'}
    <form action="{modurl modname='MUVideo' type='movie' func='handleSelectedEntries' lct=$lct}" method="post" id="moviesViewForm" class="z-form">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
    {/if}
        <table class="z-datatable">
            <colgroup>
                {if $lct eq 'admin'}
                    <col id="cSelect" />
                {/if}
                <col id="cTitle" />
                <col id="cDescription" />
                <col id="cUploadOfMovie" />
                <col id="cUrlOfYoutube" />
                <col id="cPoster" />
                <col id="cWidthOfMovie" />
                <col id="cHeightOfMovie" />
                <col id="cCollection" />
                <col id="cItemActions" />
            </colgroup>
            <thead>
            <tr>
                {assign var='catIdListMainString' value=','|implode:$catIdList.Main}
                {if $lct eq 'admin'}
                    <th id="hSelect" scope="col" align="center" valign="middle">
                        <input type="checkbox" id="toggleMovies" />
                    </th>
                {/if}
                <th id="hTitle" scope="col" class="z-left">
                    {sortlink __linktext='Title' currentsort=$sort modname='MUVideo' type=$lct func='view' sort='title' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString collection=$collection workflowState=$workflowState q=$q pageSize=$pageSize ot='movie'}
                </th>
                <th id="hDescription" scope="col" class="z-left">
                    {sortlink __linktext='Description' currentsort=$sort modname='MUVideo' type=$lct func='view' sort='description' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString collection=$collection workflowState=$workflowState q=$q pageSize=$pageSize ot='movie'}
                </th>
                <th id="hUploadOfMovie" scope="col" class="z-left">
                    {sortlink __linktext='Upload of movie' currentsort=$sort modname='MUVideo' type=$lct func='view' sort='uploadOfMovie' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString collection=$collection workflowState=$workflowState q=$q pageSize=$pageSize ot='movie'}
                </th>
                <th id="hUrlOfYoutube" scope="col" class="z-left">
                    {sortlink __linktext='Url of youtube' currentsort=$sort modname='MUVideo' type=$lct func='view' sort='urlOfYoutube' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString collection=$collection workflowState=$workflowState q=$q pageSize=$pageSize ot='movie'}
                </th>
                <th id="hPoster" scope="col" class="z-left">
                    {sortlink __linktext='Poster' currentsort=$sort modname='MUVideo' type=$lct func='view' sort='poster' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString collection=$collection workflowState=$workflowState q=$q pageSize=$pageSize ot='movie'}
                </th>
                <th id="hWidthOfMovie" scope="col" class="z-right">
                    {sortlink __linktext='Width of movie' currentsort=$sort modname='MUVideo' type=$lct func='view' sort='widthOfMovie' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString collection=$collection workflowState=$workflowState q=$q pageSize=$pageSize ot='movie'}
                </th>
                <th id="hHeightOfMovie" scope="col" class="z-right">
                    {sortlink __linktext='Height of movie' currentsort=$sort modname='MUVideo' type=$lct func='view' sort='heightOfMovie' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString collection=$collection workflowState=$workflowState q=$q pageSize=$pageSize ot='movie'}
                </th>
                <th id="hCollection" scope="col" class="z-left">
                    {sortlink __linktext='Collection' currentsort=$sort modname='MUVideo' type=$lct func='view' sort='collection' sortdir=$sdir all=$all own=$own catidMain=$catIdListMainString collection=$collection workflowState=$workflowState q=$q pageSize=$pageSize ot='movie'}
                </th>
                <th id="hItemActions" scope="col" class="z-right z-order-unsorted">{gt text='Actions'}</th>
            </tr>
            </thead>
            <tbody>
        
        {foreach item='movie' from=$items}
            <tr class="{cycle values='z-odd, z-even'}">
                {if $lct eq 'admin'}
                    <td headers="hSelect" align="center" valign="top">
                        <input type="checkbox" name="items[]" value="{$movie.id}" class="movies-checkbox" />
                    </td>
                {/if}
                <td headers="hTitle" class="z-left">
                    <a href="{modurl modname='MUVideo' type=$lct func='display' ot='movie'  id=$movie.id}" title="{gt text='View detail page'}">{$movie.title|notifyfilters:'muvideo.filterhook.movies'}</a>
                </td>
                <td headers="hDescription" class="z-left">
                    {$movie.description}
                </td>
                <td headers="hUploadOfMovie" class="z-left">
                    {if $movie.uploadOfMovie ne ''}
                    <a href="{$movie.uploadOfMovieFullPathURL}" title="{$movie->getTitleFromDisplayPattern()|replace:"\"":""}"{if $movie.uploadOfMovieMeta.isImage} rel="imageviewer[movie]"{/if}>
                    {if $movie.uploadOfMovieMeta.isImage}
                        {thumb image=$movie.uploadOfMovieFullPath objectid="movie-`$movie.id`" preset=$movieThumbPresetUploadOfMovie tag=true img_alt=$movie->getTitleFromDisplayPattern()}
                    {else}
                        {gt text='Download'} ({$movie.uploadOfMovieMeta.size|muvideoGetFileSize:$movie.uploadOfMovieFullPath:false:false})
                    {/if}
                    </a>
                    {else}&nbsp;{/if}
                </td>
                <td headers="hUrlOfYoutube" class="z-left">
                    {if $movie.urlOfYoutube ne ''}
                    <a href="{$movie.urlOfYoutube}" title="{gt text='Visit this page'}">{icon type='url' size='extrasmall' __alt='Homepage'}</a>
                    {else}&nbsp;{/if}
                </td>
                <td headers="hPoster" class="z-left">
                    {if $movie.poster ne ''}
                    <a href="{$movie.posterFullPathURL}" title="{$movie->getTitleFromDisplayPattern()|replace:"\"":""}"{if $movie.posterMeta.isImage} rel="imageviewer[movie]"{/if}>
                    {if $movie.posterMeta.isImage}
                        {thumb image=$movie.posterFullPath objectid="movie-`$movie.id`" preset=$movieThumbPresetPoster tag=true img_alt=$movie->getTitleFromDisplayPattern()}
                    {else}
                        {gt text='Download'} ({$movie.posterMeta.size|muvideoGetFileSize:$movie.posterFullPath:false:false})
                    {/if}
                    </a>
                    {else}&nbsp;{/if}
                </td>
                <td headers="hWidthOfMovie" class="z-right">
                    {$movie.widthOfMovie}
                </td>
                <td headers="hHeightOfMovie" class="z-right">
                    {$movie.heightOfMovie}
                </td>
                <td headers="hCollection" class="z-left">
                    {if isset($movie.collection) && $movie.collection ne null}
                        <a href="{modurl modname='MUVideo' type=$lct func='display' ot='collection'  id=$movie.collection.id}">{strip}
                          {$movie.collection->getTitleFromDisplayPattern()}
                        {/strip}</a>
                        <a id="collectionItem{$movie.id}_rel_{$movie.collection.id}Display" href="{modurl modname='MUVideo' type=$lct func='display' ot='collection'  id=$movie.collection.id theme='Printer' forcelongurl=true}" title="{gt text='Open quick view window'}" class="z-hide">{icon type='view' size='extrasmall' __alt='Quick view'}</a>
                        <script type="text/javascript">
                        /* <![CDATA[ */
                            document.observe('dom:loaded', function() {
                                mUMUVideoInitInlineWindow($('collectionItem{{$movie.id}}_rel_{{$movie.collection.id}}Display'), '{{$movie.collection->getTitleFromDisplayPattern()|replace:"'":""}}');
                            });
                        /* ]]> */
                        </script>
                    {else}
                        {gt text='Not set.'}
                    {/if}
                </td>
                <td id="itemActions{$movie.id}" headers="hItemActions" class="z-right z-nowrap z-w02">
                    {if count($movie._actions) gt 0}
                        {icon id="itemActions`$movie.id`Trigger" type='options' size='extrasmall' __alt='Actions' class='z-pointer z-hide'}
                        {foreach item='option' from=$movie._actions}
                            <a href="{$option.url.type|muvideoActionUrl:$option.url.func:$option.url.arguments}" title="{$option.linkTitle|safetext}"{if $option.icon eq 'preview'} target="_blank"{/if}>{icon type=$option.icon size='extrasmall' alt=$option.linkText|safetext}</a>
                        {/foreach}
                        <script type="text/javascript">
                        /* <![CDATA[ */
                            document.observe('dom:loaded', function() {
                                mUMUVideoInitItemActions('movie', 'view', 'itemActions{{$movie.id}}');
                            });
                        /* ]]> */
                        </script>
                    {/if}
                </td>
            </tr>
        {foreachelse}
            <tr class="z-{if $lct eq 'admin'}admin{else}data{/if}tableempty">
                <td class="z-left" colspan="{if $lct eq 'admin'}10{else}9{/if}">
            {gt text='No movies found.'}
              </td>
            </tr>
        {/foreach}
        
            </tbody>
        </table>
        
        {if !isset($showAllEntries) || $showAllEntries ne 1}
            {pager rowcount=$pager.numitems limit=$pager.itemsperpage display='page' modname='MUVideo' type=$lct func='view' ot='movie'}
        {/if}
    {if $lct eq 'admin'}
            <fieldset>
                <label for="mUVideoAction">{gt text='With selected movies'}</label>
                <select id="mUVideoAction" name="action">
                    <option value="">{gt text='Choose action'}</option>
                    <option value="delete" title="{gt text='Delete content permanently.'}">{gt text='Delete'}</option>
                </select>
                <input type="submit" value="{gt text='Submit'}" />
            </fieldset>
        </div>
    </form>
    {/if}

    
    {* here you can activate calling display hooks for the view page if you need it *}
    {*if $lct ne 'admin'}
        {notifydisplayhooks eventname='muvideo.ui_hooks.movies.display_view' urlobject=$currentUrlObject assign='hooks'}
        {foreach key='providerArea' item='hook' from=$hooks}
            {$hook}
        {/foreach}
    {/if*}
</div>
{include file="`$lct`/footer.tpl"}

<script type="text/javascript">
/* <![CDATA[ */
    document.observe('dom:loaded', function() {
        {{if $lct eq 'admin'}}
            {{* init the "toggle all" functionality *}}
            if ($('toggleMovies') != undefined) {
                $('toggleMovies').observe('click', function (e) {
                    Zikula.toggleInput('moviesViewForm');
                    e.stop();
                });
            }
        {{/if}}
    });
/* ]]> */
</script>
