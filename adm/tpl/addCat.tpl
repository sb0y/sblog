{extends file="main.tpl"}
{block name=title}Добавление новой категории{/block}
{block name=pageScripts}
{/block}
{block name=sub_sidebar nocache}
    {include file='includes/sidebar/post.tpl'}
{/block}
{block name=body}
{nocache}
{include file="includes/forms/category.tpl"}
{/nocache}
{/block}