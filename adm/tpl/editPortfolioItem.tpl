{extends file="base.tpl"}
{block name=title}Редактирование объекта{/block}
{block name=pageScripts}
<script type="text/javascript">
	var picsDir = "portfolioPics";
	var contentAdress = urlBase+"content/"+picsDir;
</script>
<script type="text/javascript" src="/adm/resources/js/ajaxPicUpload.js"></script>
{/block}
{block name=body}
{nocache}
{include file="includes/forms/portfolioItem.tpl"}
{/nocache}
{/block}