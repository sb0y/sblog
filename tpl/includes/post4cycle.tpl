<article>
	<div class="row post" id="post_{$item.contentID}">
			<h2>{$item.title}</h2>
			<div class="summary">
				<span>Дата:</span> <a href="{$urlBase}blog/date/{$item.dt|date_format:"%d.%m.%Y"}">{$item.dt|date_format:"%d.%m.%Y"}</a>,
				<span>Автор:</span> <a href="#">Sb0y</a>,
				<span>Категории:</span> {foreach $item.cats as $k=>$v}<a href="{$urlBase}blog/category/{$v.catSlug|urlencode}">{$v.catName}</a>{if !$v@last},&nbsp;{/if}{/foreach}
			</div>
		<div class="content">
			<p>{if $item.short}{$item.short|nl2br}{else}{$item.body|nl2br}{/if}</p>
		</div>
		<div class="post-read-more">
			<a href="{$urlBase}blog/{$item.slug|urlencode}">Читать дальше</a>
		</div>
	</div>
</article>
