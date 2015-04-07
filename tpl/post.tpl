{extends file="base.tpl"}
{block name=title}{if isset ($item)}{$item.title}{else}Ничего не найдено по запросу: "{basename ($routePath)}"{/if}{/block}
{block name=pageScripts}
	<script src="{$urlBase}resource/js/tiny_mce/jquery.tinymce.js"></script>
	<script type="text/javascript" src="{$urlBase}resources/js/weditor.js"></script>
	<link rel="stylesheet" href="{$urlBase}resources/css/geshi.css" type="text/css" media="all" />
{/block}
{block name=body}
<div class="posts-list post"> 
	{if isset ($item)}
			<article> 
				<div class="page-header">
					<h1>{$item.title}</h1>
				</div>

				<ul class="nav nav-pills">
					<li title="Дата написания статьи">
						<a href="{$urlBase}blog/date/{$item.dt|date_format:"%d.%m.%Y"}"><span class="glyphicon glyphicon-time"></span>&nbsp;{$item.dt|date_format:"%d.%m.%Y"}</a>
					</li>
					<li title="Автор статьи"><a href="{$urlBase}user/profile/{$item.userID}"><span class="glyphicon glyphicon-user"></span>&nbsp;{$item.author}</a></li>
					{if count($item.cats) > 1}
					<li class="dropdown">
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
						  Категории <span class="caret"></span>
						</a>
					</li>
					<ul class="dropdown-menu">
					{foreach $item.cats as $k=>$v}
						<li><a href="{$urlBase}blog/category/{$v.catSlug|urlencode}">{$v.catName}</a></li>
					{/foreach}
					</ul>
					{elseif !empty ( $item.cats )}
					{foreach $item.cats as $k=>$v}
						{if $v@first}
							<li title="Категории статьи"><a href="{$urlBase}blog/category/{$v.catSlug|urlencode}"><span class="glyphicon glyphicon-tag"></span>&nbsp;{$v.catName}</a></li>
						{/if}
					{/foreach}
					{/if}
				</ul>
	  
				{if isset ($item.img)}
				<div class="post-image">
					<img alt="post-image1" class="width-auto" src="{$urlBase}resource/images/post-image.png"/>
				</div>
				{/if}
				<hr>
				<div class="content">
					{$item.body}
				</div>
			</article>

			<br><hr>
			<!-- Posted Comments -->

			<div class="comments">
			{foreach $comments as $k=>$v}
				<!-- Comment -->
				<div class="media" id="comment_{$v.commentID}">

					<a class="pull-left" href="{if $v.source!="direct"}{$v.profileURL}{/if}">
						{if isset ($v.avatar_small)}<img style="width:64px;" src="{$urlBase}content/avatars/{$v.avatar_small}" class="media-object img-responsive img-thumbnail" title="{$v.nick}" alt="{$v.nick}" />{else}<img alt="Нет аватара" style="width:64px;" title="Нет аватара" src="{$urlBase}resources/images/no-avatar-small.png" class="media-object img-responsive img-thumbnail" />{/if}
					</a>

					<div class="media-body">
						<h4 class="media-heading">
							<a{if $v.source!="direct"} class="{$v.source}" rel="nofollow" target="_blank"{/if} href="{if $v.source!="direct"}{$v.profileURL}{/if}">{$v.author}</a>
							<small>{$v.dt|date_format:"%d"} {$v.dt|month_declination} в {$v.dt|date_format:"%H:%M"}&nbsp;<a href="{$urlBase}blog/{$item.slug|urlencode}/#comment_{$v.commentID}" title="Ссылка на комментарий">#</a></small>
						</h4>
						{$v.body|nl2p}
					</div>

				 </div>
			{foreachelse}
				<div class="alert alert-dismissible alert-info">
				   Статью пока никто не комментировал. Ваш комментарий может стать первым.
				</div>
			{/foreach}

			{if isset ($smarty.session.user) nocache}
			<hr>
			<div class="well">
				<h4>Написать комментарий</h4>
				<div class="row">
					<div class="col-lg-12">
						<div id="comment-textarea">
							<form method="post" role="form">
								<input type="hidden" value="{if $args}{$args[0]}{/if}" name="SLUG">
								<div class="form-group">
									<div class="hidden-xs">
										<textarea name="comment[]" class="comment-area form-control" rows="3" tabindex="4"></textarea>
									</div>
									<div class="visible-xs">
										<textarea name="comment[]" class="form-control" rows="3" tabindex="4"></textarea>
									</div>
								</div>
								<button type="submit" class="btn btn-primary">Отправить</button>
							</form>
						</div>
				</div>
				</div>
			</div>
			{else}
			<br />
			<div class="well well-lg">
				<a href="javascript:;" class="loginButton">Войдите</a> или <a target="_blank" href="{$urlBase}user/registration">зарегистрируйтесь</a>, чтобы написать комментарий.
			</div>
			{/if}
	 </div>
	 {else}
		<p>По вашему запросу ничего не найдено.</p>
	 {/if}
</div>
{/block}
