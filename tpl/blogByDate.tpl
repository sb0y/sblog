{block name=title}Все записи от {$date}{/block}
{block name=body}
 <div class="blogByDate">
    {if !empty ($date)}
    <div class="page-header">
        <h1>Все записи от {$date}</h1>
    </div>
    {/if}
    {if !empty ($posts)}
    {foreach $posts as $key=>$item}
    {include file="includes/post4cycle.tpl"}
    {/foreach}
    {else}
    <p>Ничего не найдено :-(</p>
    {/if}
</div>
{/block}
