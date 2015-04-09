{extends file="base.tpl"}
{block name=title}Список всех категорий{/block}
{block name=pageScripts}
<script type="text/javascript" src="{$urlBase}resources/js/lists.js"></script>
{/block}
{block name=sub_sidebar nocache}
    {include file='includes/sidebar/post.tpl'}
{/block}
{block name=body}
{nocache}
<div class="header" id="table_head">
    <a href="#"><i class="icon thumbnails"></i></a>
    <a href="#" class="active"><i class="icon listview"></i></a>
		<span>
				Всего найдено <strong>{$allCount}</strong> категории(ий)
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
			<th><a href="{$urlBase}{$calledController}/categories?sort=catName&direction={$direction}">Название{if $sort=="catName"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="catName"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</th>
			
			<th><a href="{$urlBase}{$calledController}/categories?sort=catSlug&direction={$direction}">URL{if $sort=="catSlug"} <img src="/adm/resources/img/icons/arrow_{if $direction=="ASC" && $sort=="catSlug"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
			
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		{foreach $list as $key => $value}
			<tr class="postRow" id="{$value.categoryID}">
				<td>{$value.catName}</td>
				<td>{$value.catSlug}</td>
				<td align="center">
					<a href="{$urlBase}{$calledController}/editCategory?categoryID={$value.categoryID}" class="btn">Редактировать <i class="icon settings_mini"></i></a>
				</td>
				<td>
					<a href="{$urlBase}{$calledController}/categories?action=delete&categoryID={$value.categoryID}" onclick="return confirm ('Вы уверенны?')"><i class="icon delete_mini"></i></a>
				</td>
			</tr>
		{/foreach}
	</tbody>
	</table>
	
		 <p>
		 	{assign "pagBase" "{$calledController}/categories"}
			{include file="includes/pagination.tpl"}
		 </p>
</div>
{/nocache}
{/block}
