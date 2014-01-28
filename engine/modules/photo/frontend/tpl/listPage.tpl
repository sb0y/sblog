{block name=title}Видео архив{/block}
{block name=post_summary}
    <div class="offset_10 dark_gray horizontal_borders">
        <span>Видео архив</span>
    </div>
{/block}

{block name=body nocache}

<ul>
    {foreach $fill as $key => $value}
    <li class="pull_left">
        <a href="{$urlBase}video/{$value.slug}"><img src="{$urlBase}content/videoPreview/{$value.pictures}" width="300" style="margin: 3px 0 0 5px;" /></a>
    </li>
    {foreachelse}
        NOPE
    {/foreach}
</ul>
    <div class="row">
        {include file="includes/pagination.tpl"}
    </div>
{/block}