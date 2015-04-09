{extends file="base.tpl"}
{block name=title}Создание нового поста{/block}
{block name=pageScripts}
<script type="text/javascript">
	var urlBase = "{$urlBase}";
	var picsDir = "postImages";
	var contentAdress = urlBase+"content/"+picsDir;
</script>
<script type="text/javascript" src="{$urlBase}resources/js/jquery.sticky.js"></script>
<script type="text/javascript" src="{$urlBase}resources/js/ajaxPicUpload.js"></script>
<script type="text/javascript" src="{$urlBase}resources/js/writePost.js"></script>
<script type="text/javascript" src="{$urlBase}resources/js/moment.js"></script>
<script type="text/javascript" src="{$urlBase}resources/js/pikaday.js"></script>
<script type="text/javascript" src="{$urlBase}resources/js/pikaday.jquery.js"></script>
<script type="text/javascript" src="/resources/js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/adm/resources/js/photo.js"></script>
<script type="text/javascript" src="/resources/js/weditor.js"></script>
<link rel="stylesheet" href="/resources/css/auth-form.css" type="text/css" media="all" />
<script type="text/javascript" src="/resources/js/popup.js"></script>
<script type="text/javascript" src="{$urlBase}resources/js/drafts.js"></script>
<script type="text/javascript">
	var tinymce;
	$(function()
	{
		editorSettings["script_url"] = "/resources/js/tiny_mce/tiny_mce.js";
		editorSettings["theme_advanced_resize_horizontal"] = true;
		editorSettings["plugins"] = "codesnippet,paste,fullscreen";
		//editorSettings["relative_urls"] = false;
		editorSettings["convert_urls"] = false;
		editorSettings["entity_encoding"] = "raw";
		editorSettings["theme_advanced_buttons1"] = "bold,italic,underline,strikethrough,|,undo,redo,|,bullist,numlist,|,blockquote,forecolor,backcolorformatselect,fontsizeselect,link,unlink, fullscreen, code";
		tinymce = $("#post-admin-body").tinymce ( editorSettings );
	});
</script>
{/block}
{block name=sub_sidebar}
{include file="includes/sidebar/post.tpl" nocache}
{/block}
{block name=body}
{include file="includes/forms/post.tpl" nocache}
{plugin exec=video}
	{include file="includes/videoInsert.tpl"}
{/plugin}

{/block}

