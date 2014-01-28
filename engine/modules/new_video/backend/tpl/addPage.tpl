{block name=body}

    <form id="form" method="post"{*enctype="multipart/form-data"*}>
        <div class="header">
            <div class="if_sticky">
                <a href="{$urlBase}video/listPage"><i class="icon close"></i></a>
                <button href="#" class="pull_right" type="submit" name="savePost"><i class="icon save"></i></button>
            </div>
        </div>
        <div class="offset_20">
            <div class="row context">
                <div class="col span_10">
                    <fieldset id="writeCategory">
                        <p>
                            <label for="url">Ссылка на видео</label>
                            <input id="url" name="url" value="{fromPost var='url' arr=$fill}" />
                        </p>
                        <p>
                            <label for="">&nbsp;</label>
                            <a href="javascript:void(null)" class="btn info" id="generate">Сгенерить</a>
                        </p>
                        <div class="data-info" style="display: none;">
	                        <p>
	                            <label for="title">Заголовок</label>
	                            <input id="title" name="title" value="{fromPost var='title' arr=$fill}" />
	                        </p>
	                        <p>
	                            <label for="slug">Слаг</label>
	                            <input id="slug" name="slug" value="{fromPost var='slug' arr=$fill}" />
	                        <p>
	                            <label for="description">Описание</label>
                                <textarea id="description" name="description" >{fromPost var='description' arr=$fill}</textarea>
	                        </p>

                            <p class="checkboxes">
                                <label for="showOnSite">Показывать на сайте</label><input id="showOnSite" class="clear" type="checkbox" name="showOnSite" value="Y"{if isset ( $fill.showOnSite ) && $fill.showOnSite=='Y'} checked="checked"{/if} />
                            <p>
	                        <p>
	                            <label for="comments_count">Кол-во комментариев</label>
	                            <input id="comments_count" name="comments_count" value="{fromPost var='comment_count' arr=$fill}" />
	                        </p>
	                        <p>
	                            <label for="views_count">Кол-во просмотров</label>
	                            <input id="views_count" name="views_count" value="{fromPost var='views_count' arr=$fill}" />
	                        </p>
	                        <p>
	                            <label for="pictures">Скриншот</label>
	                            <input id="pictures" name="pictures" value="{fromPost var='pictures' arr=$fill}" />
								<img src="{fromPost var='pictures' arr=$fill}" id="picture_preview" width="320" />
	                        </p>
	                        <p>
	                            <label for="video">Видео</label>
	                            <input id="video" name="video" value="{fromPost var='video' arr=$fill}" />
	                            <iframe width="420" height="315" style="display: none;" id="video_frame" src="{fromPost var='video' arr=$fill}" frameborder="0" allowfullscreen></iframe>
	                        </p>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).on('click','#generate',function(){
            $url = $('#url').val();
            $url = $url.split('watch?v=').pop();
            $url = $url.split('&').shift();
            

        	$.ajax({
				url: "https://gdata.youtube.com/feeds/api/videos/"+$url+"?v=2&alt=json",
			}).done(function(data) {
	    		$('#title').val(data.entry.title.$t);
	    		$('#slug').val(data.entry.title.$t);
	    		$('#comments_count').val(data.entry.gd$comments.gd$feedLink.countHint);
	    		$('#views_count').val(data.entry.yt$statistics.viewCount);
	    		$('#pictures').val(data.entry.media$group.media$thumbnail[3].url);
    			$('#picture_preview').attr('src',data.entry.media$group.media$thumbnail[3].url);
	    		$('#video').val('//www.youtube.com/embed/'+$url);
	    		$('#video_frame').attr('src','//www.youtube.com/embed/'+$url).show();
	    		$('#description').val(data.entry.media$group.media$description.$t);
	    		// console.log(data.entry.media$group);
	    		$('.data-info').slideDown();
			});
//            var page = url.substring(url.lastIndexOf('/') + 1);
        });
    </script>

{/block}