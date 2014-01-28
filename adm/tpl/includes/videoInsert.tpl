<fieldset id="videos">
	<form id="video_form" action="/adm/video/addPageAjax" method="post"{*enctype="multipart/form-data"*}>
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
	                        <a href="javascript:void(null)" class="btn info" id="generate">Загрузить и вставить в текст</a>
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
	                            <input id="pictures_video" name="pictures" value="{fromPost var='pictures' arr=$fill}" />
	                        </p>
	                        <p>
	                            <label for="video">Видео</label>
	                            <input id="video" name="video" value="{fromPost var='video' arr=$fill}" />
	                        </p>
	                    </div>
	                </fieldset>
	            </div>
	        </div>
	    </div>
	</form>
	<div id="video_frames"></div>
    <script>
        $(document).on('click','#generate',function(){
            $url = $('#url').val();
            $url = $url.split('watch?v=').pop();
            $url = $url.split('&').shift();
            

        	$.ajax({
				url: "https://gdata.youtube.com/feeds/api/videos/"+$url+"?v=2&alt=json",
			}).done(function(data) {
	    		$('#videos #title').val(data.entry.title.$t);
	    		$('#videos #slug').val(data.entry.title.$t);
	    		$('#comments_count').val(data.entry.gd$comments.gd$feedLink.countHint);
	    		$('#views_count').val(data.entry.yt$statistics.viewCount);
	    		$('#pictures_video').val(data.entry.media$group.media$thumbnail[3].url);
	    		$('#video').val('//www.youtube.com/embed/'+$url);
	    		$('#videos  #description').val(data.entry.media$group.media$description.$t);
	    		// console.log(data.entry.media$group);
	    		// $('.data-info').slideDown();
	    		$('#video_frames').append('<p><img src="'+$('#pictures_video').val()+'" id="picture_preview" width="120" style="margin-left: 20px;"/><textarea style="width: 300px; height: 85px;"><iframe width="920" height="550" src="'+$('#video').val()+'" frameborder="0" style="display: block;" allowfullscreen></iframe></textarea><a class="insertVideo" href="javascript:;" style="display: block;margin-left: 20px;">Добавить в текст</a></p>');
	    		var datastring = $("#videos form").serialize();
				$.ajax({
		            type: "POST",
		            url: "/adm/video/addPageAjax",
		            data: datastring,
		            dataType: "json"
	        	});

			});
        });
    </script>
</fieldset>