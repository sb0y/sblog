function handlePast (pl, o)
{
	//o.content = o.content.replace (/\</gi,'&lt;').replace (/\>/gi,'&gt;')
	if (o.content.charAt (o.content.length+1) != "\n")
		o.content += "\n";
}

function handleSubmit (ed, e)
{
	//var txt = ed.getContent()
	//txt = txt.replace (/\<span id="htmlTab"[^>]*>.*<\/span>"/gi, "lol");
	//ed.setContent (txt);
}


$(document).on('click', '.insertVideo', function()
{
	tinymce.activeEditor.setContent ( tinymce.activeEditor.getContent() + $(this).prev('textarea').val() );
});


function addPictureByUrl ( place )
{
	var str = "<p>";
	var $j = $(place.parentNode.parentNode);
	var $img = $j.find ("img.showPic")[0];
	var urlSmall = $img.src;
	var a = $j.find ("a.showPicLink")[0];
	var urlBig = a.href;

	if ( urlSmall )
	{
		str += "<a class=\"fancybox\" target=\"_blank\" href=\""+urlBig+"\">";
	}

	str += "<img src=\"" + ( urlSmall ? urlSmall : urlBig ) + "\" />";

	if ( urlSmall )
		str += "</a>";

	str += "</p>";

	tinymce.activeEditor.setContent ( tinymce.activeEditor.getContent() + str );
}

var editorSettings = 
{
		script_url : urlBase+"resources/js/tiny_mce/tiny_mce.js",
		theme : "advanced",
		plugins : "emotions,bbcode,codesnippet,paste",
		cleanup : true,
		theme_advanced_buttons1_add_before : "juststifyleft,justifycenter,justifyright,justifyfull",
		theme_advanced_buttons1 : "",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,
		force_br_newlines : false,
		convert_newlines_to_brs : false,
		forced_root_block : '',
		force_p_newlines : true,
		// Skin options
        skin : "cirkuit",
		//content_css : "/css/tinymce.css",
		disk_cache : true,
		language : "ru",
		add_unload_trigger : true,
		remove_linebreaks : false,
		inline_styles : true,
		theme_advanced_path : false,
		convert_fonts_to_spans : true,
        convert_newlines_to_brs : false,
        object_resizing : true,
    	paste_auto_cleanup_on_paste: true,
		paste_convert_headers_to_strong: false,
		paste_strip_class_attributes: "",
		paste_remove_spans: true,
		paste_remove_styles: true,
		paste_preprocess : function (pl, o) 
		{
			handlePast (pl, o);
		},

		setup : function ( ed ) 
		{
      		ed.onSubmit.add(function (ed, e)
      		{
      			handleSubmit (ed, e);
      		});

			ed.onKeyDown.add(function(editor, event) 
			{
				// We only listen for the tab key
				if (event.keyCode!=9) return;
			        
				editor.execCommand("mceInsertContent", false, "<span class=\"htmlTab\" style=\"text-indent:5em;\"> </span>");
				tinymce.dom.Event.cancel(event);

				return;
			});
  		}
}