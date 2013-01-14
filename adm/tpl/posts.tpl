{extends file="main.tpl"}
{block name=title}Список всех постов{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resources/js/lists.js"></script>
{/block}
{block name=body}
{nocache}
<div id="box">
	<h3>Все посты</h3>

	<table width="100%">
	<thead>
		<tr>
			<th><a href="{$urlBase}blog/posts?sort=title&direction={$direction}">Заголовок{if $sort=="title"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort==title}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</th>
			
			<th><a href="{$urlBase}blog/posts?sort=dt&direction={$direction}">Дата{if $sort=="dt"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort==dt}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th><a href="{$urlBase}blog/posts?sort=comments_count&direction={$direction}">Комментарии{if $sort=="comments_count"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="comments_count"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th>Действия</th>
		</tr>
	</thead>
	<tbody>
		{foreach $list as $key => $value}
			<tr class="postRow" id="{$value.contentID}">
				<td>{$value.title}</td>
				<td>{$value.dt}</td>
				<td>{$value.comments_count}</td>
				<td align="center">
					<a target="_blank" href="/blog/{$value.slug}"><img src="/adm/resources/img/icons/page_white_link.png" title="Ссылка на статью" width="16" height="16"></a>
					<a href="{$urlBase}blog/editPost?contentID={$value.contentID}"><img src="/adm/resources/img/icons/page_white_edit.png" title="Редактировать статью" width="16" height="16"></a>
					<a href="{$urlBase}blog/posts?action=delete&contentID={$value.contentID}" onclick="return confirm ('Вы уверенны?')"><img src="/adm/resources/img/icons/page_white_delete.png" title="Удалить статью" width="16" height="16"></a>
					<a href="/adm/blog/showPostComments/{$value.contentID}"><img src="/adm/resources/img/icons/folder_page.png" title="Посмотреть комментарии к статье" width="16" height="16"></a>
				</td>
			</tr>
		{/foreach}
	</tbody>
	</table>
		 <p>Всего найдео <strong>{$allCount}</strong> поста(ов)</p>
		 <p>
			<a href="javascript:;" id="selectAll">Выделить всё</a>
		 </p>
		 <p>
			<form method="POST" id="groupActionsForm">
				<select id="groupActions" name="groupDelete">
				<option name="">Выберите действие</option>
				<option name="deleteAll" id="deleteAllButton" disabled="disabled">Удалить всё</option>
				</select>
				<div id="selectHolder"></div>
			</form>
		 </p>
		 <p>
		 	{assign "pagBase" "blog/posts"}
			{include file="includes/pagination.tpl"}
		 </p>
</div>
{/nocache}
{/block}
