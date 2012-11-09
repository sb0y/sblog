{extends file="main.tpl"}
{block name=title}Редактирование поста{/block}
{block name=pageScripts}
<script type="text/javascript" src="/adm/resources/js/writePost.js"></script>
<script type="text/javascript" src="/resource/js/jquery.example.min.js"></script>
{/block}
{block name=body}
{nocache}
{include file="includes/forms/post.tpl"}
{/nocache}
{/block}