{if isset ($pages)}
<div class="pagination">
<ul>
{foreach $pages as $key => $value}
<li><span class="page-numbers{if $value.options} {$value.options}{/if}"><a href="{$urlBase}{$pagBase}/offset/{$value.value}">{$value.value}</a></span></li>
{/foreach}
</ul>
</div>
{/if}
