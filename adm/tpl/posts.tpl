{extends file="main.tpl"}
{block name=title}Список всех постов{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resources/js/lists.js"></script>
{/block}
{block name=sub_sidebar}
{include file='includes/sidebar/post.tpl'}
{/block}
{block name=body}
<div class="header" id="table_head">
    <a href="#"><i class="icon thumbnails"></i></a>
    <a href="#" class="active"><i class="icon listview"></i></a>
 			<span>
				Всего найдено <strong>{$allCount}</strong> поста(ов)
			</span>
			<a href="javascript:;" id="selectAll"><i>Выделить всё</i></a>
			<span>
			<form method="POST" id="groupActionsForm">
				<select id="groupActions" name="groupDelete">
				<option name="">Выберите действие</option>
				<option name="deleteAll" id="deleteAllButton" disabled="disabled">Удалить всё</option>
				</select>
				<div id="selectHolder"></div>
			</form>
		 </span>	 
</div>
<div class="table">
	<table width="100%">
	<thead>
		<tr>
		<th></th>
			<th><a href="{$urlBase}news/posts?sort=title&direction={$direction}">Заголовок{if $sort=="title"} <img src="{$urlBase}resources/img/icons/arrow_{if $direction=="ASC" && $sort==title}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</th>
			
			<th><a href="{$urlBase}news/posts?sort=dt&direction={$direction}">Дата{if $sort=="dt"} <img src="{$urlBase}resources/img/icons/arrow_{if $direction=="ASC" && $sort==dt}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th><a href="{$urlBase}news/posts?sort=comments_count&direction={$direction}">Комментарии{if $sort=="comments_count"} <img src="{$urlBase}resources/img/icons/arrow_{if $direction=="ASC" && $sort=="comments_count"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach $list as $key => $value}
			<tr class="postRow {cycle values="odd,even"}" id="{$value.contentID}">
				<td><a target="_blank" href="/news/{$value.URL}">#{$value.contentID}</a></td>
				<td><a target="_blank" href="/news/{$value.URL}">{$value.title}</a></td>
				<td>{$value.dt}</td>
				<td><a href="{$urlBase}news/showPostComments/{$value.contentID}" class="">{$value.comments_count}</a></td>
				<td>
					<a href="{$urlBase}news/editPost?contentID={$value.contentID}" class="btn">Редактировать <i class="icon settings_mini"></i></a>
				</td>
				<td>	
					<a href="{$urlBase}news/posts?action=delete&contentID={$value.contentID}" onclick="return confirm ('Вы уверенны?')" class=""><i class="icon delete_mini"></i></a>
				</td>
			</tr>
		{/foreach}
	</tbody>
	</table>
		
		 <p>
		 	{assign "pagBase" "news/posts"}
			{include file="includes/pagination.tpl"}
		 </p>
</div>
{/block}
