{extends file="main.tpl"}
{block name=title}Создание нового поста{/block}
{block name=pageScripts}
<script type="text/javascript">
	var picsDir = "postImages";
	var contentAdress = urlBase+"content/"+picsDir;
</script>
<script type="text/javascript" src="/adm/resources/js/ajaxPicUpload.js"></script>
<script type="text/javascript" src="/adm/resources/js/writePost.js"></script>
{/block}
{block name=body}
{nocache}
{include file="includes/forms/post.tpl"}
{/nocache}
{/block}
