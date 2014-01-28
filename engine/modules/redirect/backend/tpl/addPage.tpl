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
{if !isset ($fill)}
{assign var=fill value=array()}
{/if}
<form id="form" method="post" enctype="multipart/form-data">
<div class="header">
	<div class="if_sticky">
    <a href="{$urlBase}users"><i class="icon close"></i></a>
    <button href="#" class="pull_right" type="submit" name="savePost"><i class="icon save"></i></button>
    </div>
</div>
<div class="offset_20">
    <div class="row context">
        <div class="col span_10">
			<fieldset id="writePost">
				<p>
					<label for="URL">URL</label>
					<textarea style="width:600px;height:200px;" id="URL" name="URL">{fromPost var='URL' arr=$fill}</textarea>
				</p>
			</fieldset>
		</div>
	</div>
</div>
</form>
{/block}
