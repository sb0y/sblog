{block name=body}
<div class="posts-list">
    {foreach $posts as $key=>$item}
    {include file="includes/post4cycle.tpl"}
    {foreachelse}
    <p>По вашему запросу ничего не найдено.</p>
    {/foreach}
</div>
{/block}