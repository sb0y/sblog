{extends file="main.tpl"}
{block name=title}Список всех категорий{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resources/js/lists.js"></script>
{/block}
{block name=body}
{nocache}
<div id="box">
	<h3>Все категории</h3>

	<table width="100%">
	<thead>
		<tr>
			<th><a href="{$urlBase}blog/categories?sort=catName&direction={$direction}">Название{if $sort=="catName"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="catName"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</th>
			
			<th><a href="{$urlBase}blog/categories?sort=catSlug&direction={$direction}">URL{if $sort=="catSlug"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="catSlug"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th>Действия</th>
		</tr>
	</thead>
	<tbody>
		{foreach $list as $key => $value}
			<tr class="postRow" id="{$value.categoryID}">
				<td>{$value.catName}</td>
				<td>{$value.catSlug}</td>
				<td align="center">
					<a href="{$urlBase}blog/editCategory?categoryID={$value.categoryID}"><img src="/adm/resources/img/icons/page_white_edit.png" title="Редактировать категорию" width="16" height="16"></a>
					<a href="{$urlBase}blog/categories?action=delete&categoryID={$value.categoryID}" onclick="return confirm ('Вы уверенны?')"><img src="/adm/resources/img/icons/page_white_delete.png" title="Удалить категорию" width="16" height="16"></a>
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
