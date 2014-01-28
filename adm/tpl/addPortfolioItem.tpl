{extends file="main.tpl"}
{block name=title}Создание нового объекта{/block}
{block name=pageScripts}
<script type="text/javascript">
	var picsDir = "portfolioPics";
	var contentAdress = urlBase+"content/"+picsDir;
</script>
<script type="text/javascript" src="{$urlBase}resources/js/ajaxPicUpload.js"></script>
{/block}
{block name=body}
{include file="includes/forms/portfolioItem.tpl" nocache}
{/block}
