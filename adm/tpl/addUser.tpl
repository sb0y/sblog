{extends file="base.tpl"}
{block name=title}Добавление нового пользователя{/block}
{block name=pageScripts}{/block}
{block name=sub_sidebar}
{nocache}
{include file='includes/sidebar/users.tpl'}
{/nocache}
{/block}
{block name=body}
{nocache}
{include file="includes/forms/user.tpl"}
{/nocache}
{/block}
