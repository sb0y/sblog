{extends file="base.tpl"}
{block name=title}Список всех постов{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resources/js/lists.js"></script>
{/block}
{block name=body}
{nocache}
<div class="header" id="table_head">
    <a href="#"><i class="icon thumbnails"></i></a>
    <a href="#" class="active"><i class="icon listview"></i></a>
 			<span>
				Всего найдено <strong>{$allCount}</strong> комментариев
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
		<th>
			<th><a href="{$urlBase}news/showPostComments/{$contentID}?sort=author&direction={$direction}">Автор{if $sort=="author"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort==author}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</th>

			<th><a href="{$urlBase}news/showPostComments/{$contentID}?sort=type&direction={$direction}">Где написан{if $sort=="type"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort==author}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</th>

			<th>Текст комментария</th>
			
			<th><a href="{$urlBase}news/showPostComments/{$contentID}?sort=dt&direction={$direction}">Дата{if $sort=="dt"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort==dt}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th><a href="{$urlBase}news/showPostComments/{$contentID}?sort=email&direction={$direction}">E-mail{if $sort=="email"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="email"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

			<th><a href="{$urlBase}news/showPostComments/{$contentID}?sort=ip&direction={$direction}">IP{if $sort=="ip"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="ip"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach $list as $key => $value}
			<tr class="postRow" id="{$value.commentID}">
				<td><a target="_blank" href="{$urlBase}adm/news/resolveUrlById/{$value.contentID}?prefix={urlencode ("#comment_{$value.commentID}")}">#{$value.commentID}</a></td>
				<td>{$value.author}</td>
				<td>{$value.type}</td>
				<td title="{$value.body|strip_tags}">{$value.body|strip_tags|truncate:100}</td>
				<td>{$value.dt|date_format:"%d-%m-%Y"}</td>
				<td>{$value.email}</td>
				<td>{$value.ip|long2ip}</td>
				<td align="center">
					<a href="{$urlBase}news/editComment?contentID={$contentID}&commentID={$value.commentID}" class="btn">Редактировать <i class="icon settings_mini"></i></a>
				</td>
				<td>		
					<a href="{$urlBase}news/showPostComments/{$contentID}?action=delete&commentID={$value.commentID}" onclick="return confirm ('Вы уверенны?')"><i class="icon delete_mini"></i></a>
				</td>
			</tr>
		{/foreach}
	</tbody>
	</table>
	
	 	
		 <p>
		 	{assign "pagBase" "news/showPostComments/{$contentID}"}
			{include file="includes/pagination.tpl"}
		 </p>
</div>
{/nocache}
{/block}
