{if $pagination.pagesTotal > 1}
<div class="pagination bs-component">
	<ul class="pagination pagination-lg">
		<li{if $pagination.offset < 2} class="disabled"{/if}><a href="{if $pagination.offset > 1}{$urlBaseAdm}{$pagBase}/offset/{$pagination.offset-1}{else}javascript:;{/if}">«</a></li>
		{foreach $pagination.pages as $key => $value}
		<li class="page-numbers{if $value.options} {$value.options}{/if}"><a href="{$urlBaseAdm}{$pagBase}/offset/{$value.value}">{$value.value}</a></li>
		{/foreach}
		<li{if ($pagination.offset*$pagination.itemsOnPage) >= $pagination.allCount} class="disabled"{/if}><a href="{if ($pagination.offset*$pagination.itemsOnPage) <= $pagination.allCount}{$urlBaseAdm}{$pagBase}/offset/{$pagination.offset+1}{else}javascript:;{/if}">»</a></li>
	</ul>
</div>
{/if}