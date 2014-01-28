{block name=pageScripts nocache}
<link rel="stylesheet" type="text/css" href="/resources/css/imgareaselect-default.css" />
<script type="text/javascript" src="/resources/js/jquery.imgareaselect.min.js"></script>

{/block}
{block name=body}
<script type="text/javascript">
$(document).ready(function () {
    $('#photo').imgAreaSelect({ aspectRatio: "1:1", handles: true, fadeSpeed: 200, onSelectChange: preview_1,minHeight:200,minWidth:200 });
    $(document).on('click','.preview_mode',function() {
        var demention = $(this).attr('data-demention');    
        var preview_mode =  $(this).attr('data-preview');    
        $('#photo').imgAreaSelect({ remove: true, });
        if(preview_mode == '1'){
            $('#photo').imgAreaSelect({ aspectRatio: demention, handles: true, fadeSpeed: 200, onSelectChange: preview_1,minHeight:200,minWidth:200 });
        }
        if(preview_mode == '2'){
            $('#photo').imgAreaSelect({ aspectRatio: demention, handles: true, fadeSpeed: 200, onSelectChange: preview_2,minHeight:140,minWidth:200 });
        }
    });
});

function preview_1(img, selection) {
    if (!selection.width || !selection.height)
        return;
    
    var scaleX = 200 / selection.width;
    var scaleY = 200 / selection.height;

    $('.frame_1 #preview img').css({
        width: Math.round(scaleX * {$fill.width}),
        height: Math.round(scaleY * {$fill.height}),
        marginLeft: -Math.round(scaleX * selection.x1),
        marginTop: -Math.round(scaleY * selection.y1)
    });
    $('#pre_1').hide();
    $('.frame_1').show();
    $('#x').val(selection.x1);
    $('#y1').val(selection.y1);
    $('#w1').val(selection.width);
    $('#h1').val(selection.height);    
}


function preview_2(img, selection) {
    if (!selection.width || !selection.height)
        return;
    
    var scaleX = 200 / selection.width;
    var scaleY = 140 / selection.height;

    $('.frame_2 #preview img').css({
        width: Math.round(scaleX * {$fill.width}),
        height: Math.round(scaleY * {$fill.height}),
        marginLeft: -Math.round(scaleX * selection.x1),
        marginTop: -Math.round(scaleY * selection.y1)
    });
    $('#pre_2').hide();
    $('.frame_2').show();
    $('#x2').val(selection.x1);
    $('#y2').val(selection.y1);
    $('#w2').val(selection.width);
    $('#h2').val(selection.height);    
}



</script>
    <form id="form" method="post" enctype="multipart/form-data">
        <div class="header">
            <div class="if_sticky">
                <a href="{$urlBase}photo/listPage"><i class="icon close"></i></a>
                <button href="#" class="pull_right" type="submit" name="savePost"><i class="icon save"></i></button>
            </div>
        </div>
        <div class="offset_20">
            <div class="row context">
                <div class="col span_10">
                    <fieldset id="writeCategory">
                        <p>
                            <input type="hidden" id="key" name="key" value="{fromPost var='key' arr=$fill}" />
                            {if !isset($fill.width)}
                            <label for="url">Выберите файл</label>
                            <input type="file" id="picture" name="picture" value="{fromPost var='picture' arr=$fill}" />
                            
                            {else}
                            <input type="hidden" id="picture" name="picture" value="{fromPost var='picture' arr=$fill}" />
                            <input type="hidden" id="slug" name="slug" value="{fromPost var='slug' arr=$fill}" />
                            <input type="hidden" id="comments_count" name="comments_count" value="{fromPost var='comments_count' arr=$fill}" />
                            <input type="hidden" id="x1" value="" />
				 		    <input type="hidden" id="w1" name="w1" value="" />
				         	<input type="hidden" id="h1" name="h1" value="" />
                            {* я хуй знает почему, но при id="x1" поле не заполнется... поэтому просто x *}
				         	<input type="hidden" id="x" name="x1" value="" />
                            <input type="hidden" id="y1" name="y1" value="" />											        	

                            <input type="hidden" id="w2" name="w2" value="" />
				         	<input type="hidden" id="h2" name="h2" value="" />
				         	<input type="hidden" id="x2" name="x2" value="" />
                            <input type="hidden" id="y2" name="y2" value="" />
                            {/if}
                        </p>
                        <div class="data-info" style="display: block;">
	                        <p>
	                            <label for="title">Заголовок</label>
	                            <input id="title" name="title" value="{fromPost var='title' arr=$fill}" />
	                        </p>
	                        <p>
	                            <label for="description">Описание</label>
                                <textarea id="description" name="description" >{fromPost var='description' arr=$fill}</textarea>
	                        </p>
                            <p>
                                <label for="showOnSite">Показывать на сайте</label><input id="showOnSite" class="clear" type="checkbox" name="showOnSite" value="Y"{if isset ( $fill.showOnSite ) && $fill.showOnSite=='Y'} checked="checked"{/if} />
                            <p>
	                        {if isset($fill.width)}
	                            <label for="pictures">Скриншот</label>
                                <hr class="clear" />
								<img src="/content/photo/200x200/{fromPost var='picture' arr=$fill}" id="pre_1" style="margin: 0 1em; width: 200px; height: 200px; float: left;" class="preview_mode" data-demention="1:1" data-preview="1">
								
                                <div class="frame_1 preview_mode" style="margin: 0 1em; float: left; width: 200px; height: 200px; display:none;" data-demention="1:1" data-preview="1">
                                  <div id="preview" style="width: 200px; height: 200px; overflow: hidden;">
                                    <img src="/content/photo/resized/{fromPost var='picture' arr=$fill}" style="width: 200px; height: 200px;">
                                  </div>
                                </div>

                                <img src="/content/photo/200x140/{fromPost var='picture' arr=$fill}" id="pre_2" style="margin: 0 1em; width: 200px; height: 140px; float: left;"  class="preview_mode" data-demention="4:3" data-preview="2">
                                <div class="frame_2 preview_mode" style="margin: 0 1em; float: left;width: 200px; height: 140px; display:none;" data-demention="4:3" data-preview="2">
                                  <div id="preview" style="width: 200px; height: 140px; overflow: hidden;">
                                    <img src="/content/photo/resized/{fromPost var='picture' arr=$fill}" style="width: 200; height: 140px;">
                                  </div>
                                </div>      
								<div id="tabs_wrapper" class="tab1 clear">
							    <p class="instructions">
							      Выделите область для создания превью. 
							    </p>
							    <div class="frame">
							      <img id="photo" src="/content/photo/resized/{fromPost var='picture' arr=$fill}">
							    </div>
							    <span class="clear"></span>
                            {/if}
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </form>
 {/block}