{extends file="main.tpl"}
{block name=title}Список всех объектов в портфолио{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resources/js/lists.js"></script>
{/block}
{block name=body}
{nocache}
<div id="box">
	<h3>Все объекты портфолио</h3>

	<table width="100%">
	<thead>
		<tr>
			<th><a href="{$urlBase}portfolio/items?sort=title&direction={$direction}">Заголовок{if $sort=="title"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort==title}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</th>
			
			<th><a href="{$urlBase}portfolio/item?sort=dt&direction={$direction}">Дата{if $sort=="dt"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort==dt}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

			<th><a href="{$urlBase}portfolio/item?sort=type&direction={$direction}">Тип{if $sort=="type"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort==type}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th>Действия</th>
		</tr>
	</thead>
	<tbody>
		{foreach $list as $key => $value}
			<tr class="postRow" id="{$value.id}">
				<td>{$value.title}</td>
				<td>{$value.dt}</td>
				<td>{$value.type}</td>
				<td align="center">
					<a target="_blank" href="/portfolio/{$value.slug}"><img src="/adm/resources/img/icons/page_white_link.png" title="Ссылка на объект" width="16" height="16"></a>
					<a href="{$urlBase}portfolio/editItem?id={$value.id}"><img src="/adm/resources/img/icons/page_white_edit.png" title="Редактировать объект" width="16" height="16"></a>
					<a href="{$urlBase}portfolio/item?action=delete&id={$value.id}" onclick="return confirm ('Вы уверенны?')"><img src="/adm/resources/img/icons/page_white_delete.png" title="Удалить объект" width="16" height="16"></a>
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
		 	{assign "pagBase" "portfolio"}
			{include file="includes/pagination.tpl"}
		 </p>
</div>
{/nocache}
{/block}
