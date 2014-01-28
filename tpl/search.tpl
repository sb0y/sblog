{block name=title}Поиск по фразе "{$searchWord}" в новостях{/block}

{block name=post_summary}
  
<div class="offset_10 dark_gray horizontal_borders">
   <span>Результаты поиска по новостям</span>
</div>  
 
{/block}

{block name=body}
{if isset ($smallWord)}
<p>Ваш запрос - слишком короткий. Пожайлуста, переформулируйте так, чтобы в нём было больше символов.</p>
{else}
<div class="searchTypes horisontal_borders">
<span{if $calledController=="search"} class="active"{/if}><a href="{$urlBase}search{if isset($smarty.get.text)}?text={$smarty.get.text}{/if}">Всё</a></span><span{if $calledController=="news"} class="active"{/if}><a href="{$urlBase}news/search{if isset($smarty.get.text)}?text={$smarty.get.text}{/if}">Новости</a></span><span{if $calledController=="article"} class="active"{/if}><a href="{$urlBase}article/search{if isset($smarty.get.text)}?text={$smarty.get.text}{/if}">Статьи</a></span>
</div>
{if isset ($searchRes)}
    <div class="entries">
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
