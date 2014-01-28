{block name=title}Создание новой статьи{/block}
{block name=pageScripts}
<script type="text/javascript">
	var urlBase = "{$urlBase}";
	var picsDir = "articleImages";
	var contentAdress = urlBase+"content/"+picsDir;
</script>
<script type="text/javascript" src="{$urlBase}resources/js/jquery.sticky.js"></script>
<script type="text/javascript" src="{$urlBase}resources/js/ajaxPicUpload.js"></script>
<script type="text/javascript" src="{$urlBase}resources/js/writePost.js"></script>
<script type="text/javascript" src="{$urlBase}resources/js/moment.js"></script>
<script type="text/javascript" src="{$urlBase}resources/js/pikaday.js"></script>
<script type="text/javascript" src="{$urlBase}resources/js/pikaday.jquery.js"></script>
<script type="text/javascript" src="/resources/js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript" src="/resources/js/weditor.js"></script>
<script type="text/javascript" src="/resources/js/popup.js"></script>
<script type="text/javascript" src="{$urlBase}resources/js/drafts.js"></script>
<script type="text/javascript">
	var tinymce;
	$(document).ready(function() 
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
{assign var=gbURL value="article/listPage"}
{include file="includes/forms/post.tpl" nocache}
{/block}
