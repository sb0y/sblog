{extends file="main.tpl"}
{block name=title}Список всех пользователей{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resources/js/lists.js"></script>
{/block}
{block name=body}
{nocache}
<div id="box">
	<h3>Все пользователи</h3>

	<table width="100%">
	<thead>
		<tr>
			<th><a href="{$urlBase}users?sort=nick&direction={$direction}">Имя{if $sort=="nick"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="nick"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

			<th><a href="{$urlBase}users?sort=dt&direction={$direction}">Дата{if $sort=="dt"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="dt"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</th>

			<th><a href="{$urlBase}users?sort=email&direction={$direction}">E-mail{if $sort=="email"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="email"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

			<th><a href="{$urlBase}users?sort=source&direction={$direction}">Источник{if $sort=="source"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="source"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

			<th><a href="{$urlBase}users?sort=ip&direction={$direction}">IP{if $sort=="ip"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="ip"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

			<th><a href="{$urlBase}users?sort=profileURL&direction={$direction}">profileURL{if $sort=="profileURL"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="profileURL"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

			<th><a href="{$urlBase}users?sort=role&direction={$direction}">Роль{if $sort=="role"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="role"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th>Действия</th>
		</tr>
	</thead>
	<tbody>
		{foreach $list as $key => $value}
			<tr class="postRow" id="{$value.userID}">
				<td>{$value.nick}</td>
				<td>{$value.dt}</td>
				<td>{$value.email}</td>
				<td>{$value.source}</td>
				<td>{$value.ip|long2ip}</td>
				<td>{if $value.profileURL}<a href="{$value.profileURL}" target="_blank">ссылка</a>{else}--{/if}</td>
				<td>{$value.role}</td>
				<td align="center">
					<a href="{$urlBase}users/edit?userID={$value.userID}"><img src="/adm/resources/img/icons/user_edit.png" title="Редактировать провиль пользователя" width="16" height="16"></a>
					<a href="{$urlBase}users?action=delete&userID={$value.userID}" onclick="return confirm ('Вы уверенны?')"><img src="/adm/resources/img/icons/user_delete.png" title="Удалить профиль пользователя" width="16" height="16"></a>
				</td>
			</tr>
		{/foreach}
	</tbody>
	</table>
		 <p>Всего найдео <strong>{$allCount}</strong> поста(ов)</p>
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
		 	{assign "pagBase" "blog/categories"}
			{include file="includes/pagination.tpl"}
		 </p>
</div>
{/nocache}
{/block}
