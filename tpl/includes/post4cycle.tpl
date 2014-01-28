<div class="entry">
    <div class="row">
    {if $item.poster}
        <div class="col span_2">
            <a href="{$urlBase}{$item.URL}"><img class="entry_img" src="{$urlBase}content/photo/resized/{$item.poster}" alt="{if $item.short}{$item.short|strip_tags}{else}{$item.body|strip_tags|truncate:100}{/if}" title="{if $item.short|strip_tags}{$item.short}{else}{$item.body|strip_tags|truncate:100}{/if}" /></a>
        </div>
        <div class="col span_10">
        <div class="offset_0_15">
    {else}
        <div class="col span_12">
        <div>
    {/if}
            <h2 class="title">
                <a href="{$urlBase}{$item.URL}">{$item.title}</a>
            </h2>

                {if $item.short}{$item.short}{else}{$item.body|truncate:100}{/if}
            </div>
        </div>
    </div>
    <div class="row offset_10_0">
        <div class="col span_12">
            <div class="offset_0_0_15">
                <a href="{$urlBase}{$item.URL}" class="pull_left readmore">Читать далее</a>
                <a href="{$urlBase}{$item.URL}" class="pull_right margin_0_10">{if $item.comments_count > 0}Комментарии ({$item.comments_count}){else}Откомментировать{/if}</a>
                <span class="pull_right date">{$item.dt|date_format:"%d"} {$item.dt|month_declination} {$item.dt|date_format:"%Y"}</span>
            </div>
        </div>
    </div>
</div>
