{extends file="base.tpl"}
{block name=title}Глобальный поиск по фразе "{$searchWord}"{/block}
{block name=body}
<div class="search">
	{if isset ($smallWord)}
	<p>Ваш запрос - слишком короткий. Пожайлуста, переформулируйте так, чтобы в нём было больше символов.</p>
	{else}

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
</div>
{/block}