{block name=title}Поиск по фразе "{$searchWord}"{/block}
{block name=body}
<div class="posts-list post">
    <div class="page-header">
        <h1>Результаты поиска по сайту</h1>
    </div>
    {if isset ($smallWord)}
    <p>Ваш запрос - слишком короткий. Пожайлуста, переформулируйте так, чтобы в нём было больше символов.</p>
    {else}
    {if isset ($searchRes)}
        {foreach $searchRes as $key=>$item}
        {include file="includes/post4cycle.tpl"}
        {/foreach}
    {else}
    <p>По запросу "{$searchWord nocache}" ничего не найдено.</p>
    {/if}
    {/if}
</div>
{/block}
