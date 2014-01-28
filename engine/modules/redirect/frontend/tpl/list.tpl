{block name=title}Статьи{/block}
{block name=post_summary}{/block}
{block name=body}
	<div class="entries">
     {foreach $posts as $key=>$item}
     	{assign var=URLPrefix value="article"}
		{assign var=imagesFolder value="articleImages"}
		{include file="includes/post4cycle.tpl"}
        {foreachelse} 
			<p>По вашему запросу ничего не найдено.</p>
        {/foreach}
    </div>
	{include file="includes/pagination.tpl"}
{/block}
