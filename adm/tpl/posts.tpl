{block name=title}Список всех постов{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBaseAdm}resources/js/lists.js"></script>
{/block}
{block name=body}
<div class="posts">
<h1 class="page-header">Посты</h1>
<div class="panel panel-default">
	<div class="panel-body">
		<p>
			<form class="form-inline" method="POST" id="groupActionsForm" role="form">
				<div class="form-group">
					<a style="margin:0 20px 0 0; border-right: 1px solid #ddd;" href="{$urlBaseAdm}blog/posts/write" class="btn btn-danger">Новый пост</a>
					<button class="btn btn-primary" href="javascript:;" id="selectAll">Выделить всё</button>&nbsp;
					<select class="form-control" id="groupActions" name="groupDelete">
						<option name="">Выполнить в выделенным...</option>
						<option name="deleteAll" id="deleteAllButton" disabled="disabled">Удалить всё</option>
					</select>
					<div id="selectHolder"></div>
				</div>
			</form>
		</p>

	</div>
</div>
<div class="table-responsive">
	<table class="table table-bordered table-striped">
	<thead>
		<tr>
		<th></th>
			<th><a href="{$urlBaseAdm}news/posts?sort=title&direction={$direction}">Заголовок{if $sort=="title"} <img src="{$urlBaseAdm}resources/img/icons/arrow_{if $direction=="ASC" && $sort==title}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</th>
			
			<th class="center"><a href="{$urlBaseAdm}news/posts?sort=dt&direction={$direction}">Дата{if $sort=="dt"} <img src="{$urlBaseAdm}resources/img/icons/arrow_{if $direction=="ASC" && $sort==dt}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th class="center"><a href="{$urlBaseAdm}news/posts?sort=comments_count&direction={$direction}">Комментарии{if $sort=="comments_count"} <img src="{$urlBaseAdm}resources/img/icons/arrow_{if $direction=="ASC" && $sort=="comments_count"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach $list as $key => $value}
			<tr class="postRow {cycle values="odd,even"}" id="{$value.contentID}">
				<td class="center"><a target="_blank" href="{$urlBase}blog/{$value.URL}">#{$value.contentID}</a></td>
				<td style="vertical-align:middle;"><a target="_blank" href="{$urlBase}blog/{$value.URL}">{$value.title}</a></td>
				<td class="center">{$value.dt}</td>
				<td class="center"><a href="{$urlBaseAdm}blog/showPostComments/{$value.contentID}" class="">{$value.comments_count}</a></td>
				<td class="center">
					<a href="{$urlBaseAdm}blog/posts/edit?contentID={$value.contentID}" class="btn btn-info">
						<span class="glyphicon glyphicon-edit"></span>&nbsp;<span class="hidden-xs hidden-sm">Редактировать</span>
					</a>
				</td>
				<td class="center">	
					<a href="{$urlBaseAdm}blog/posts?action=delete&contentID={$value.contentID}" onclick="return confirm ('Вы уверенны?')" class="btn btn-danger">
						<span class="glyphicon glyphicon-remove"></span>&nbsp;<span class="hidden-xs hidden-sm">Удалить</span>
					</a>
				</td>
			</tr>
		{/foreach}
	</tbody>
	</table>
		
		 <p>
		 	{assign "pagBase" "blog/posts"}
			{include file="includes/pagination.tpl"}
		 </p>
</div>
</div>
{/block}
