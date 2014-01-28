{block name=title}Избаранное пользователя {$smarty.session.user.nick}{/block}
{block name=post_summary}
<div class="offset_10 dark_gray horizontal_borders">
	<span>Избаранное пользователя {$smarty.session.user.nick}</span>
</div>
{/block}
{block name=body}
	<div class="entries">
     {foreach $posts as $key=>$item}
		<div class="entry">
		    <div class="row">
		    	{if $item.poster}
		        <div class="col span_2">
		            <a href="{$urlBase}{$calledController}/{$item.slug}"><img class="entry_img" src="{$urlBase}content/photo/200x200/{$item.poster}" alt="{if $item.short|strip_tags}{$item.short}{else}{$item.body|strip_tags|truncate:100}{/if}" title="{if $item.short|strip_tags}{$item.short}{else}{$item.body|strip_tags|truncate:100}{/if}" /></a>
		        </div>
		        <div class="col span_10">
		        <div class="offset_0_15">
		    	{else}
		        <div class="col span_12">
		        <div>
		    	{/if}
		        <h2 class="title">
					<a href="{$urlBase}{if !isset($URLPrefix)}{$calledController}{else}{$URLPrefix}{/if}/{$item.URL}">{$item.title}</a>
				</h2>

				{if $item.short}{$item.short}{else}{$item.body|truncate:100}{/if}
		        </div>
			</div>
		</div>
		<div class="row offset_10_0">
			<div class="col span_12">
				<div class="offset_0_0_15">
					<a href="{$urlBase}{if isset($URLPrefix)}{$URLPrefix}{/if}{$item.URL}" class="pull_left readmore">Читать далее</a>
					<a href="{$urlBase}{if isset($URLPrefix)}{$URLPrefix}{/if}{$item.URL}" class="pull_right margin_0_10">{if $item.comments_count > 0}Комментарии ({$item.comments_count}){else}Откомментировать{/if}</a>
					<span class="pull_right date">{$item.dt|date_format:"%d"} {$item.dt|month_declination} {$item.dt|date_format:"%Y"}</span>
					<span style="margin:0 10px 0 0" class="pull_right date">{if $item.type=="news"}Новость{else if $item.type=="artice"}Cтатья{/if}</span>
					<span style="margin:0 10px 0 0" class="pull_right date">Рейтинг {$item.rating}</span>
				</div>
			</div>
		</div>
	</div>

	{foreachelse} 
	<p>У вас ещё нет избранного.</p>
	{/foreach}
	</div>
	{include file="includes/pagination.tpl"}
{/block}
