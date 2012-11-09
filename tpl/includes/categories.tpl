{foreach $categories as $v}
	<li><a href="{$urlBase}blog/category/{$v.catSlug}">{$v.catName}</a></li>
{/foreach}