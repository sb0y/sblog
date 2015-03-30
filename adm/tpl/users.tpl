{block name=title}Список всех пользователей{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBaseAdm}resources/js/lists.js"></script>
{/block}
{block name=body nocache}
<div class="userList">
<div class="panel panel-default">
	<div class="panel-body">
			<form class="form-inline" method="POST" id="groupActionsForm" role="form">
				<div class="form-group">
					<button class="btn btn-primary" href="javascript:;" id="selectAll">Выделить всех</button>
				</div>
				<div class="form-group">
					<select class="form-control" id="groupActions" name="groupDelete">
						<option name="">Выполнить в выделенным...</option>
						<option name="deleteAll" id="deleteAllButton" disabled="disabled">Удалить всех</option>
					</select>
					<div id="selectHolder"></div>
				</div>
			</form>
	</div>
</div>
<div class="table-responsive">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th></th>
				<th><a href="{$urlBaseAdm}users?sort=nick&direction={$direction}">Имя{if $sort=="nick"} <img src="{$urlBaseAdm}resources/img/icons/arrow_{if $direction=="ASC" && $sort=="nick"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

				<th><a href="{$urlBaseAdm}users?sort=dt&direction={$direction}">Дата{if $sort=="dt"} <img src="{$urlBaseAdm}resources/img/icons/arrow_{if $direction=="ASC" && $sort=="dt"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</th>

				<th><a href="{$urlBaseAdm}users?sort=email&direction={$direction}">E-mail{if $sort=="email"} <img src="{$urlBaseAdm}resources/img/icons/arrow_{if $direction=="ASC" && $sort=="email"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

				<th><a href="{$urlBaseAdm}users?sort=source&direction={$direction}">Источник{if $sort=="source"} <img src="{$urlBaseAdm}resources/img/icons/arrow_{if $direction=="ASC" && $sort=="source"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

				<th><a href="{$urlBaseAdm}users?sort=ip&direction={$direction}">IP{if $sort=="ip"} <img src="{$urlBaseAdm}resources/img/icons/arrow_{if $direction=="ASC" && $sort=="ip"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

				<th><a href="{$urlBaseAdm}users?sort=profileURL&direction={$direction}">profileURL{if $sort=="profileURL"} <img src="{$urlBaseAdm}resources/img/icons/arrow_{if $direction=="ASC" && $sort=="profileURL"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

				<th><a href="{$urlBaseAdm}users?sort=role&direction={$direction}">Роль{if $sort=="role"} <img src="{$urlBaseAdm}resources/img/icons/arrow_{if $direction=="ASC" && $sort=="role"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
				
				<th></th>
				<th></th>
			</tr>
		</thead>
	<tbody>
		{foreach $list as $key => $value}
			<tr class="postRow {cycle values="odd,even"}" id="userid_{$value.userID}" data-userid="{$value.userID}">
				<td style="min-width:64px;" class="center">
					<img class="img-responsive img-thumbnail" src="{$urlBase}{$value|resolveAvatar}" />
				</td>
				<td class="center">{$value.nick}</td>
				<td class="center">{$value.dt}</td>
				<td class="center">{$value.email}</td>
				<td class="center">{$value.source}</td>
				<td class="center">{$value.ip|long2ip}</td>
				<td class="center">{if $value.profileURL}<a href="{$value.profileURL}" target="_blank">ссылка</a>{else}--{/if}</td>
				<td class="center">{$value.role}</td>
				<td class="center">
					<a href="{$urlBaseAdm}users/edit?userID={$value.userID}" class="btn btn-info">
						<span class="glyphicon glyphicon-edit"></span>&nbsp;<span class="hidden-xs hidden-sm">Редактировать</span>
					</a>
				</td>
				<td class="center">	
					<a href="{$urlBaseAdm}users?action=delete&userID={$value.userID}" onclick="return confirm ('Вы уверенны?')" class="btn btn-danger">
						<span class="glyphicon glyphicon-remove"></span>&nbsp;<span class="hidden-xs hidden-sm">Удалить</span>
					</a>
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
</div>
{/block}
