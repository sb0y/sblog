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
			<th><a href="{$urlBase}blog/showPostComments/{$contentID}?sort=author&direction={$direction}">Автор{if $sort=="author"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort==author}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</th>

			<th>Текст комментария</th>
			
			<th><a href="{$urlBase}blog/showPostComments/{$contentID}?sort=dt&direction={$direction}">Дата{if $sort=="dt"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort==dt}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th><a href="{$urlBase}blog/showPostComments/{$contentID}?sort=email&direction={$direction}">E-mail{if $sort=="email"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="email"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

			<th><a href="{$urlBase}blog/showPostComments/{$contentID}?sort=ip&direction={$direction}">IP{if $sort=="ip"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="ip"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th>Действия</th>
		</tr>
	</thead>
	<tbody>
		{foreach $list as $key => $value}
			<tr class="postRow" id="{$value.commentID}">
				<td>{$value.author}</td>
				<td title="{$value.body}">{$value.body|truncate:100}</td>
				<td>{$value.dt|date_format:"%d-%m-%Y"}</td>
				<td>{$value.email}</td>
				<td>{$value.ip}</td>
				<td align="center">
					<a target="_blank" href="/adm/blog/resolveUrlById/{$value.contentID}?prefix={urlencode ("#comment_{$value.commentID}")}"><img src="/adm/resources/img/icons/page_white_link.png" title="Ссылка на комментарий" width="16" height="16"></a>
					
					<a href="{$urlBase}blog/editComment?commentID={$value.commentID}&contentID={$contentID}"><img src="/adm/resources/img/icons/page_white_edit.png" title="Редактировать комментарий" width="16" height="16"></a>
					
					<a href="{$urlBase}blog/showPostComments?action=delete&commentID={$value.commentID}" onclick="return confirm ('Вы уверенны?')"><img src="/adm/resources/img/icons/page_white_delete.png" title="Удалить комментарий" width="16" height="16"></a>
				</td>
			</tr>
		{/foreach}
	</tbody>
	</table>
		 <p>Всего найдео <strong>{$allCount}</strong> комментариев(я)</p>
	 	
	 	 <p><a href="javascript:;" id="selectAll">Выделить всё</a></p>
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
		 	{assign "pagBase" "blog/showPostComments/{$contentID}"}
			{include file="includes/pagination.tpl"}
		 </p>
</div>
{/nocache}
{/block}
