{block name=body nocache}
    <div class="header" id="table_head">
        <a href="#"><i class="icon thumbnails"></i></a>
        <a href="#" class="active"><i class="icon listview"></i></a>
 			<span>
				Всего найдено <strong>{$allCount}</strong> редиректа(ов)
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
            <th><a href="{$urlBase}{$calledController}/listPage?sort=URL&direction={$direction}">Ссылка{if $sort=="URL"} <img src="{$urlBase}resources/img/icons/arrow_{if $direction=="ASC" && $sort==URL}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</th>

            <th><a href="{$urlBase}{$calledController}/listPage?sort=code&direction={$direction}">Код редиректа{if $sort=="code"} <img src="{$urlBase}resources/img/icons/arrow_{if $direction=="ASC" && $sort=="code"}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>

            <th>Результат</th>

            <th><a href="{$urlBase}{$calledController}/listPage?sort=dt&direction={$direction}">Дата добавления{if $sort=="dt"} <img src="{$urlBase}resources/img/icons/arrow_{if $direction=="ASC" && $sort==dt}down{else}up{/if}_mini.gif" width="16" height="16" align="absmiddle" />{/if}</a></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        {foreach $list as $key => $value}
            <tr class="postRow {cycle values="odd,even"}" id="{$value.entryID}">
                <td>#{$value.entryID}</td>
                <td><a target="_blank" href="http://{$siteDomain}/~{$value.code}">{$value.URL|truncate:80}</a></td>
                <td>{$value.code}</td>
                <td><input name="" value="http://{$siteDomain}/~{$value.code}" onclick="this.select()" /></td>
                <td>{$value.dt|date_format:"%d.%m.%Y"}</td>
                <td>
                    <a href="{$urlBase}{$calledController}/edit?entryID={$value.entryID}" class="btn">Редактировать <i class="icon settings_mini"></i></a>
                </td>
                <td>
                    <a href="{$urlBase}{$calledController}/listPage?action=delete&entryID={$value.entryID}" onclick="return confirm ('Вы уверенны?')" class=""><i class="icon delete_mini"></i></a>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>

    {*<p>*}
        {*{assign "pagBase" "video/listPage"}*}
        {*{include file="includes/pagination.tpl"}*}
    {*</p>*}
{/block}