{extends file="main.tpl"}
{block name=title}Все записи от {$date}{/block}
{block name=body}
 <div class="posts-list">
    {if !empty ($date)}<p><h2 class="page-title">Все записи от {$date}</h2></p>{/if}
    	{if !empty ($posts)}
        {foreach $posts as $key=>$item}
		{include file="includes/post4cycle.tpl"}
        {foreachelse}
			<p>По вашему запросу ничего не найдено.</p>
        {/foreach}
        </div>
        {else}
        Ничего не найдено :(
        {/if}
{/block}
