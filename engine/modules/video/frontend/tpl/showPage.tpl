{block name=title nocache}Видео архив{/block}
{block name=post_summary nocache}
    <div class="offset_10 dark_gray horizontal_borders">
        <span>{$fill.title}</span>
        <div class="pull_right">
        <span class="date">Количество просмотров:</span>
        <span>{$fill.views_count}</span>
        <span class="date">Комментарии:</span>
        <span>{$fill.comments_count}</span>
        </div>
    </div>
{/block}
{block name=body nocache}
    {if $fill }
    <div class="content">
        <iframe width="100%" height="500" id="video_frame" src="{$fill.video}" frameborder="0" allowfullscreen></iframe>
    </div>
    {else}
        NOPE
    {/if}
{/block}