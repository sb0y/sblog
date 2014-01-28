{block name=title}Все записи по категории "{$catName}"{/block}
{block name=post_summary}
<div class="offset_10 dark_gray horizontal_borders">
	<span>Все записи из категории "{$catName}"</span>
</div>
{/block}
{block name=body}
	<div class="entries">
     {foreach $posts as $key=>$item}
		{assign var=URLPrefix value="news"}
		{include file="includes/post4cycle.tpl"}
        {foreachelse} 
			<p>По вашему запросу ничего не найдено.</p>
        {/foreach}
    </div>
	{include file="includes/pagination.tpl"}
{/block}
