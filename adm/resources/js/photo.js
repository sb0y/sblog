$(document).ready(function(){
	$('<div class="postPictures"></div>').insertAfter('form#form');
	$('<div class="thumbPopup"></div>').insertAfter('.form_textfield');
	loadForm();
});
$(document).on('click', '.editPictureThumbs',function() {
	var $contentID = $(this).attr('data-id');
	if($contentID != '')
	{
		$.ajax({
			url: "/adm/photo/editPageAjax?contentID="+$contentID,
			context: document.body
		}).done(function(data) {
			$('.thumbPopup').html(data).fadeIn(300);
			$('.form_textfield').hide();
		});
	}
	
});
$(document).on('click', '.closeThumbler',function() {
	$('#photo').imgAreaSelect({ remove: true, });
	$('.thumbPopup').fadeOut(300).html('');
	$('.form_textfield').show();

});

$(document).on('click', '.removePicture',function() {
	if (confirm('Точно хотите удалить изображение?')) {
		$contentID = $(this).attr('data-id');
		if($contentID != '')
		{
			$.ajax({
				url: "/adm/photo/deleteAjax?contentID="+$contentID,
				context: document.body
			}).done(function(data) {
				$('li#picture_'+$contentID).fadeOut(300).remove();
			});	
		}
	}
	else {
	return false;
	}
});


$(document).on('click', '.addPictureToPoster',function() {
	var poster = $(this).attr('data-picture');
	$('#poster').val(poster);
	if ($("#posterImg").length > 0){
  		$('#posterImg').attr('src', '/content/photo/200x200/'+poster);
	}
	else 
	{
		if($("#posterMsg").length > 0)
		{
			$("#posterMsg").html('<img id="posterImg" src="/content/photo/200x200/'+poster+'" width="100" />');	
		}
	}	
});


$(document).on('click', '.addPictureToText',function() {
	var poster = $(this).attr('data-picture');
	$data = '<img src="/content/photo/200x200/'+poster+'" />'
	tinymce.activeEditor.setContent ( tinymce.activeEditor.getContent() + $data );
});
$(document).on('submit', '#resize_form',function() { 
    $(this).ajaxSubmit({
        success: function(data)
        {
         	reloadPicture($('#resize_form #picture').val());
        }
    });
    return false;
}); 
$(document).on('submit', '#picture_form',function() { 
    $(this).ajaxSubmit({
        success: function(data)
        {
            loadPictures();
        }
    });
    return false;
}); 
function loadForm()
{
	var $key = $('input#key').val();
	$.ajax({
		url: "/adm/photo/addPageAjax?key="+$key,
		context: document.body
	}).done(function(data) {
		$('.postPictures').append(data);
		loadPictures();
	});
}		
function loadPictures()
{
	var $key = $('input#key').val();
	if($key != '')
	{
		$.ajax({
			url: "/adm/photo/listPageAjax?key="+$key,
			context: document.body
		}).done(function(data) {
			$('.postPictures .postImages').remove();
			$('.postPictures').append(data);
		});
	}	
}
function reloadPicture($id)
{
	d = new Date();
	$("img[src$='/content/photo/200x200/"+$id+"']").attr("src", "/content/photo/200x200/"+$id+"?"+d.getTime());
}
