{block name=title}Комментарии пользователя {$smarty.session.user.nick}{/block}
{block name=post_summary}
<div class="offset_10 dark_gray horizontal_borders">
	<span>Мои комментарии</span>
</div>
{/block}
{block name=body}
<div class="comments">
	<ul class="comments">
		{foreach $comments as $k=>$v}
		<li class="comment {cycle values="odd,even"}" id="comment_{$v.commentID}">
		    <div class="row">
		        <div class="col span_1 user-info">
		            <a href="#" title="{$v.nick}" alt="{$v.nick}"><div class="avatar_block" style="background: url({if isset ($v.avatar_small)}{$urlBase}content/avatars/{$v.avatar_small}{else}{$urlBase}resource/images/no-avatar-small.png{/if}) no-repeat top center; "></div></a>
		        </div>
		        <div class="col span_11 post-data">
		            <div class="offset_10">
		                <p class="lh16"><a class="{$v.source} authorName" href="{$urlBase}user/profile/{$v.userID}">{$v.author}</a>
		                    <span class="pull_right date">{$v.dt|date_format:"%d"} {$v.dt|month_declination} {$v.dt|date_format:"%H:%M"} <a href="{$urlBase}{$calledController}/{$v.slug|urlencode}/#comment_{$v.commentID}" title="Ссылка на комментарий">#</a>
		                    <a class="addToFavorite" href="javascript:;" title="Добавить в избранное">★</a>
		                </span>
		                </p>
		                <div class="offset_10">
		                    {$v.body}
		                </div>
		                <div class="offset_10 fromComment">В {if $v.type=="news"}новости{else if $v.type=="article"}статье{/if} <a href="{$urlBase}{$v.type}/{$v.slug}">{$v.title}</a> в <strong>{$v.dt|date_format:"%d.%m.%Y %H:%M"}</strong></div>
		            </div> 
		        </div>
		    </div>
		</li>
		{foreachelse}
		<p class="alert info">У вас пока нет комментариев.</p>
		{/foreach}
	</ul>
	{include file="includes/pagination.tpl"}
</div>
{/block}