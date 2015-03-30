{block name=title}Все записи по категории "{$catName}"{/block}
{block name=body}
<div class="blogByCat">
	<div class="page-header">
		<h1>Все записи из категории "{$catName}"</h1>
	</div>
	{foreach $posts as $key=>$item}
	{include file="includes/post4cycle.tpl"}
	{foreachelse}
	<p>По вашему запросу ничего не найдено.</p>
	{/foreach}
</div>
{/block}
