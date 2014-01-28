{block name=title}Список всех пользователей{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resources/js/lists.js"></script>
{/block}
{block name=sub_sidebar nocache}
{include file='includes/sidebar/users.tpl'}
{/block}
{block name=body nocache}
<div class="header" id="table_head">
    <a href="#"><i class="icon thumbnails"></i></a>
    <a href="#" class="active"><i class="icon listview"></i></a>
	<span>
			Всего найдено <strong>{$allCount}</strong> пользователей
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
			<th><a href="{$urlBase}users?sort=nick&direction={$direction}">Имя{if $sort=="nick"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="nick"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

			<th><a href="{$urlBase}users?sort=dt&direction={$direction}">Дата{if $sort=="dt"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="dt"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</th>

			<th><a href="{$urlBase}users?sort=email&direction={$direction}">E-mail{if $sort=="email"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="email"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

			<th><a href="{$urlBase}users?sort=source&direction={$direction}">Источник{if $sort=="source"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="source"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

			<th><a href="{$urlBase}users?sort=ip&direction={$direction}">IP{if $sort=="ip"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="ip"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

			<th><a href="{$urlBase}users?sort=profileURL&direction={$direction}">profileURL{if $sort=="profileURL"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="profileURL"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

			<th><a href="{$urlBase}users?sort=role&direction={$direction}">Роль{if $sort=="role"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="role"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach $list as $key => $value}
			<tr class="postRow {cycle values="odd,even"}" id="{$value.userID}">
				<td>
					<img src="{if $value.avatar_small}/content/avatars/{$value.avatar_small}{else if $value.avatar}/content/avatars/{$value.avatar}{else}/resources/images/no-avatar-small.png{/if}" />
				</td>
				<td>{$value.nick}</td>
				<td>{$value.dt}</td>
				<td>{$value.email}</td>
				<td>{$value.source}</td>
				<td>{$value.ip|long2ip}</td>
				<td>{if $value.profileURL}<a href="{$value.profileURL}" target="_blank">ссылка</a>{else}--{/if}</td>
				<td>{$value.role}</td>
				<td align="center">
					<a href="{$urlBase}users/edit?userID={$value.userID}" class="btn">Редактировать <i class="icon settings_mini"></i></a>
				</td>
				<td>	
					<a href="{$urlBase}users?action=delete&userID={$value.userID}" onclick="return confirm ('Вы уверенны?')" class=""><i class="icon delete_mini"></i></a>
				</td>
			</tr>
		{/foreach}
	</tbody>
	</table>
		
		 <p>
		 	{assign "pagBase" "users"}
			{include file="includes/pagination.tpl"}
		 </p>
</div>
{/block}
