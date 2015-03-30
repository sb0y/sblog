{block name=title}Редактирование поста{/block}
{block name=pageScripts}
<script type="text/javascript">
	var picsDir = "postImages";
	var contentAdress = urlBase+"content/"+picsDir;
</script>
<script type="text/javascript" src="{$urlBaseAdm}resources/js/ajaxPicUpload.js"></script>
<script type="text/javascript" src="{$urlBaseAdm}resources/js/writePost.js"></script>
<script type="text/javascript" src="{$urlBaseAdm}resources/js/moment.js"></script>
<script type="text/javascript" src="{$urlBaseAdm}resources/js/pikaday.js"></script>
<script type="text/javascript" src="{$urlBaseAdm}resources/js/pikaday.jquery.js"></script>
<script type="text/javascript" src="{$urlBaseAdm}resources/js/pictures.js"></script>
<script type="text/javascript" src="{$urlBase}resources/js/popup.js"></script>
<script type="text/javascript" src="{$urlBaseAdm}resources/js/drafts.js"></script>
{/block}

{block name=body}
{include file="includes/forms/post.tpl" nocache}
{plugin exec=video}
<hr>
{include file="includes/videoInsert.tpl"}
{/plugin}
{/block}