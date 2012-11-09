{extends file="main.tpl"}
{block name=title}Все записи по категории "{$catName}"{/block}
{block name=body}
 <div class="posts-list">
    <p><h2 class="page-title">Все записи из категории "{$catName}"</h2></p>
        {foreach $posts as $key=>$item}
		{include file="includes/post4cycle.tpl"}
        {foreachelse}
			<p>По вашему запросу ничего не найдено.</p>
        {/foreach}
        </div>
{/block}
