{block name=title}Поиск по фразе "{$searchWord}" в статьях{/block}

{block name=post_summary}
  
<div class="offset_10 dark_gray horizontal_borders">
   <span>Результаты поиска по статьям</span>
</div>  
  
{/block}

{block name=body}
{if isset ($smallWord)}
<p>Ваш запрос - слишком короткий. Пожайлуста, переформулируйте так, чтобы в нём было больше символов.</p>
{else}
{if isset ($searchRes)}
    <div class="entries">
    {assign "imagesFolder" "articleImages" }
    {foreach $searchRes as $key=>$item}
        {include file="includes/post4cycle.tpl"}
    {/foreach}
    </div>
	{include file="includes/pagination.tpl"}
{else}
<p>По запросу "{$searchWord nocache}" ничего не найдено.</p>
{/if}
{/if}

{/block}
