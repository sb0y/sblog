function draftProcessor()
{
	var popup = new Popup;
	var type = location.pathname.split ( "/" );
	type = type[2];
	var block = $( ".info-block" );
	var box = block.find ( "#messageBox" );
	var title = block.find ( "h3" );
	var time = 60000, delta = 100, timer;
	var contentID = parseInt ( getHTTPParam ( "contentID" ) );
	var form = $("form#form input:text,textarea");
	var data;

	if ( isNaN ( contentID ) )
	{
		contentID = 0;
	}

	$( "#loadDraftList" ).bind ( "click", function() 
	{
		openWindow();
		$( this ).blur();
	});

	$( window ).blur ( function() { window.blurred = true; if ( !popup.windowExist ( "drafts" ) ) save(); } );
	$( window ).focus ( function() { window.blurred = false; } );

	timer = setInterval ( function() 
	{
		if ( window.blurred || popup.windowExist ( "drafts" ) ) { return; }

		time -= delta;
		
		if ( time <= 0 ) 
		{
			//clearInterval ( timer );
			time = 60000;
			save(); // time passed
		}

	}, delta );

	function openWindow()
	{
		popup.setOptions ( { "contentID":contentID } );
		popup.showWindow ( "drafts" );
	}

	function getHTTPParam ( name )
	{
	    name = name.replace ( /[\[]/, "\\\[" ).replace ( /[\]]/, "\\\]" );
		var regex = new RegExp ( "[\\?&]" + name + "=([^&#]*)" ),
			results = regex.exec ( location.search );

	    return results == null ? "" : decodeURIComponent ( results[1].replace ( /\+/g, " " ) );
	}

	function message ( _title, text )
	{
		block.css ( "display", "block" );
		title.text ( _title );
		box.empty();
		box.append ( "<li>" + text + "</li>" );
		block.fadeOut ( 10000 );
	}

	function deleteDraft ( item )
	{
		if ( !confirm ( "Вы уверенны?" ) )
			return false;

		var $item = $( item );
		var id = parseInt ( $item.attr ("data-id") );

		$.post ( urlBase + "ajax/deleteDraft?op=one", {"id":id,"type":type}, 
			function ( res )
		{
			if ( res == "Ok" )
			{
				$item.parent().remove();
			}
		});
	}

	function deleteAll()
	{
		if ( !confirm ( "Вы уверенны?" ) )
			return false;

		$.post ( urlBase + "ajax/deleteDraft?op=all", {"type":type}, 
			function ( res )
		{
			if ( res == "Ok" )
			{
				$(".draftLayer #layerArticle,#layerAll p").empty();
				$("#layerArticle").prepend ( "<p>Черновиков ещё нет.</p>" );
				$("#layerAll").prepend ( "<p>Черновиков ещё нет.</p>" );
			}
		});
	}

	function deleteArticleDrafts()
	{
		if ( !confirm ( "Вы уверенны?" ) )
			return false;

		$.post ( urlBase + "ajax/deleteDraft?op=article", {"type":type}, 
			function ( res )
		{
			if ( res == "Ok" )
			{
				$(".draftLayer #layerArticle p").empty();
				$("#layerArticle").prepend ( "<p>Черновиков ещё нет.</p>" );
			}
		});
	}

	function save ( nick )
	{
		var isOk = false;

		data = {};
		form.each ( function ( i )
		{
			//console.log (this);

			if ( this.name == "key" || this.name == "dt" )
				return; // continue

			if ( this.value !== "" )
			{
				isOk = true;
				//return false; // break
			}

			data [ form[i].name ] = form[i].value;
		});

		//console.log ( data );

		if ( !isOk )
			return false;

		var httpStr = urlBase + "ajax/saveDraft?type=" + type;

		if ( contentID )
		{
			httpStr += "&contentID=" + contentID;
		}

		if ( nick )
			data.nick = nick;
		
		$.post ( httpStr, data, function ( res )
		{
			if ( res == "Ok" )
			{
				message ( "Черновики", "<p>Добавлен новый черновик.</p><br /><p><b><a onclick=\"drafts.openWindow()\" href=\"javascript:;\">"+
					"Посмотреть</a></b></p>" );
			}
		});
	}

	this.activateTab = function ( tabID )
	{
		if ( $("#forArticleThis").hasClass ( "active" ) )
		{
			$("#forArticleThis").removeClass ( "active" );
			$("#allUsersArticles").addClass ( "active" );
		
		} else if ( $("#allUsersArticles").hasClass ( "active" ) ) {

			$("#allUsersArticles").removeClass ( "active" );
			$("#forArticleThis").addClass ( "active" );

		}

		$("#layerArticle").toggle ( "fast" );
		$("#layerAll").toggle ( "fast" );
	}

	this.loadDraft = function ( data )
	{
		if ( !confirm ( "Вы уверены? Ваши текущие несохранённые данные в форме будут утерянны." ) )
		{
			return false;
		}

		for ( var a in data )
		{
			form.each ( function ( i )
			{
				if ( a == this.name )
				{
					this.value = data[a];
				}
			});
		}

		popup.closeWindow ( "drafts" );
		message ( "Черновик загружен", "Данные черновика добавлены в Вашу форму." );
	}

	function saveAndHide()
	{
		var titleInput = $("#form #title")
		var title = "";

		if ( titleInput.length && titleInput.val() !== "" )
			title = "Черновик \"" + titleInput.val() + "\"";

		var draftName = prompt ( "Введите название черновика.\nОтмена - название по умолчанию.", title );

		save ( draftName );
		popup.closeWindow ( "drafts" );
	}

	this.type = type;
	this.save = save;
	this.openWindow = openWindow;
	this.deleteDraft = deleteDraft;
	this.deleteArticleDrafts = deleteArticleDrafts;
	this.deleteAll = deleteAll;
	this.saveAndHide = saveAndHide;
}

window.drafts = null;

$(function()
{
	drafts = new draftProcessor;
	//drafts.save();
});