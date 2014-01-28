{block name=title}Все записи от {$date}{/block}
{block name=post_summary}
  
<div class="offset_10 dark_gray horizontal_borders">
   <span>{if !empty ($date)}Все записи от {$date}{/if}</span>
</div>  
  
{/block}
{block name=body}
 <div class="entries">
    
    	{if !empty ($posts)}
        {foreach $posts as $key=>$item}
		{include file="includes/post4cycle.tpl"}
        {foreachelse}
			<p>По вашему запросу ничего не найдено.</p>
        {/foreach}
        
        {else}
        Ничего не найдено :(
        {/if}
        </div>
        {include file="includes/pagination.tpl"}
{/block}
