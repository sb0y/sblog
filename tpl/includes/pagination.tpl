{if isset ($pages) && $pages}
<div class="pagination">
{foreach $pages as $key => $value}
<a class="page-numbers page{if $value.options} {$value.options}{/if}" href="{$urlBase}{$routePath|regex_replace:"/\/offset\/[0-9\/]*.*/":""}/offset/{$value.value}{if isset ($smarty.get.text)}?text={$smarty.get.text}{/if}">{$value.value}</a>
{/foreach}
</div>
{/if}
