function mailProcessor()
{
	var $avatar = $("#avatarHolder");
	var $field = null, $list = null, $input = null, $ul = null, $body = null, $selHolder = null, $pseudoInputHolder = null, $pseudoHolder;
	var canShowList = false, hintInProcess = false, dontClearOnBlur = true, selectedUserIndex = 0;
	var receivers = {};
	var $form = $( "#mailWriteForm" );

	$form.bind ( "submit", function ( e )
	{
		var wasError = false;

		if ( !$selHolder.children().length )
		{
			$pseudoHolder.addClass ( "error" );
			animateError ( $pseudoHolder );
			wasError = true;
			$.jGrowl ( "Укажите кому Вы пишите письмо.", { theme: "error" } );
		} else {
			$pseudoHolder.removeClass ( "error" );
		}

		var body = $( "#textBody" );
		if ( !$.trim ( body.val() ) )
		{
			body.addClass ( "error" );
			animateError ( body );
			wasError = true;
			$.jGrowl ( "Необходимо написать что-то в теле письма.", { theme: "error" } );
		} else {
			body.removeClass ( "error" );
		}

		if ( wasError )
		{
			e.preventDefault();
			return false;
		}

		dontClearOnBlur = true;
		return true;
	});

	$( ".error" ).each ( function()
	{
		animateError ( $( this ) );
	});

	$form.bind ( "keydown", function ( e )
	{
		if ( e.keyCode == 13 && e.ctrlKey )
		{
			e.preventDefault();
			$form.append ( "<input name=\"sendMail\" type=\"hidden\" value=\"sendMail\" />" );
			$form.submit();
		}
	});

	var dlists = $(".dropListField").each ( function ( i )
	{
		$field = $( this );
		$list = $field.find ( ".dropList" );
		$input = $field.find ( "input.dropDownField" );
		$ul = $list.find ( "ul" );
		$selHolder = $field.find ( "#selInputHolder" );
		$pseudoInputHolder = $field.find ( ".pseudoInputDiv" );
		$pseudoHolder = $field.find ( ".pseudoInputDiv" );

		// это понадобится, когда будут готовы друзья
		//$input.bind ( "focus", function()
		//{
			//if ( canShowList )
				//$list.show();
		//});

		$input.bind ( "blur", function()
		{
			hideList();

			if ( !dontClearOnBlur )
			{
				$avatar.attr ( "src", urlBase + "resources/images/no-avatar-small.png" );
				$input.val ( "" );
			} else {
				dontClearOnBlur = false;
			}
		});

		$pseudoInputHolder.bind ( "click", function()
		{
			$input.focus();
		});

		$input.bind ( "keydown", function ( e )
		{
			if ( e.keyCode == 8 )
			{
				var cursor = getCursor ( this );

				if ( cursor.start == 0 && cursor.end == 0 && $selHolder.children().length )
				{
					var $sel = $selHolder.children().last();

					if ( !$sel.hasClass ( "hover" ) )
						$sel.get ( 0 ).onmousedown();
					else 
						$sel.find ( "span.close" ).get ( 0 ).onclick();
				}
			}

		});

		$input.bind ( "keyup change keydown", function ( e )
		{
			if ( e.keyCode == 13 )
				return;

			if ( ( $list.is ( ":visible" ) && e.keyCode >= 37 ) && 
				 ( $list.is ( ":visible" ) && e.keyCode <= 40 ) )
					return; // not handle but fired to a next handler


			if ( this.value === "" || this.value.length < 3 )
				return; // not handle but fired to a next handler

			if ( !hintInProcess )
			{
				var self = this;
				hintInProcess = true;
				setTimeout ( function()
				{
					hideList();
					getUserList ( self.value );
				}, 500 );
			}
		});
	});

	function animateError ( $obj )
	{
		$obj.css ( "backgroundColor", "#FFA6A6" ).animate ( { backgroundColor: "#FFFFFF" }, 1000 );
	}

	function showList()
	{
		var $lis = $ul.find ( "li" );

		$field.bind ( "keydown", function ( e )
		{
			if ( e.keyCode == 37 && e.keyCode == 39 )
				return;

			var $selectedLi = $ul.find ( "li.hover" );

			if ( !$selectedLi.length )
				return;

			var $tmpNode = null;
			
			switch ( e.keyCode )
			{
				// up
				case 38:
					$selectedLi.removeClass ( "hover" );
					$tmpNode = $selectedLi.prev();

					if ( !$tmpNode.is ( "li" ) ) // its 'ul' when last or first
						$tmpNode = $lis.last();

					setAvatar ( $tmpNode.addClass ( "hover" ).get ( 0 ).onmouseover._dropData.avatar );
				break;
				// down
				case 40:

					if ( !$list.is ( ":visible" ) )
					{
						return showList();
					}

					$selectedLi.removeClass ( "hover" );
					$tmpNode = $selectedLi.next();

					if ( !$tmpNode.is ( "li" ) )
						$tmpNode = $lis.first();

					setAvatar ( $tmpNode.addClass ( "hover" ).get ( 0 ).onmouseover._dropData.avatar );
				break;
				// enter
				case 13:
					var hdata = $selectedLi.get ( 0 ).onmouseover._dropData;
					selectUser ( hdata.userID, hdata.nick, hdata.avatar );
					e.preventDefault();
				break;
			}

			//e.preventDefault();	
		});

		setAvatar ( $lis.first().addClass ( "hover" ).get ( 0 ).onmouseover._dropData.avatar );
		$list.show();
	}

	function hideList()
	{
		$field.unbind ( "keydown" );
		$list.hide();
	}

	function getUserList ( possiblyNick )
	{
		var request = $.ajax(
		{
		  type: "GET",
		  url: "/ajax/userList?nick=" + encodeURIComponent ( possiblyNick ),
		  dataType: "json"
		});

		request.always ( function ( res )
		{
			hintInProcess = false;
		});

		return request.done ( function ( res )
		{
			if ( typeof res.error != "undefined" )
			{
				$.jGrowl ( "Произошла ошибка. Код:<br />" + res, { theme: "error" } );
				return;
			}

			$ul.empty();

			for ( var i in res )
			{
				canShowList = true;
				addToList ( res[i].nick, ( res[i].avatar_small ? res[i].avatar_small : res[i].avatar ), res[i].userID );
			}

			if ( $ul.length )
			{
				showList();
			}
		});
	}

	function getCursor ( input ) 
	{
		var result = { "start":0 ,"end":0 };

		if ( input.setSelectionRange ) 
		{
			result.start = input.selectionStart;
			result.end = input.selectionEnd;
		} else if ( !document.selection ) { 
			return false;
		} else if ( document.selection && document.selection.createRange ) {

			var range = document.selection.createRange();
			var stored_range = range.duplicate();
			stored_range.moveToElementText ( input );
			stored_range.setEndPoint ( "EndToEnd", range );
			result.start = stored_range.text.length - range.text.length;
			result.end = result.start + range.text.length;
		}

		return result;
	}

	function addToList ( nick, avatar, userID )
	{
		var li = document.createElement ( "li" );
		li.innerHTML = nick;
		li.onmouseover = function()
		{
			this._dropData = {};

			setAvatar ( avatar );

			$ul.find ( "li" ).each ( function()
			{
				this.className = this.className.replace ( /[^a-z0-9-_]*hover[^a-z0-9-_]*/g, "" );
			});

			this.className = "hover";
		}

		li.onmouseover._dropData = {"avatar":avatar,"nick":nick,"userID":userID};

		li.onmousedown = function ( e )
		{
			var event = e || window.event;
			
			selectUser ( userID, nick, avatar );

			if ( event.preventDefault ) 
			{
				event.preventDefault();
			} else {
				event.returnValue = false;
  			}

  			return false;
  		}

		$ul.append ( li );
	}

	function setAvatar ( avatar )
	{
		$avatar.attr ( "src", urlBase + ( avatar ? ( "content/avatars/" + avatar ) : 
			"resources/images/no-avatar-small.png" ) );
	}

	function selectUser ( userID, nick, avatar )
	{
		var hinput = $( "<input type=\"hidden\" name=\"receivers[]\" value=\""+userID+"\" id=\"userid_"+userID+"\" />" );
		receivers[userID] = { "input":hinput, "userID":userID, "avatar":avatar };

		hideList();
		$input.val ( "" );
		dontClearOnBlur = true;

		var div = document.createElement ( "div" );

		div.className = "token";
		div.id = "userid_" + userID;

		var span1 = document.createElement ( "span" );
		span1.className = "userName";
		span1.innerHTML = nick;

		var span2 = document.createElement ( "span" );
		span2.className = "close";

		span2.onclick = function()
		{
			var root = div.parentNode;
			root.removeChild ( div );
			hinput.remove();

			receivers[userID] = null;
			delete receivers[userID];

			var selChildrens = $selHolder.children();

			if ( selChildrens.length )
				setAvatar ( receivers[ selChildrens.first().attr ("id").replace ( /[^0-9]/g, "" ) ].avatar );

			if ( !$selHolder.children().length )
				$selHolder.hide();

			if ( selectedUserIndex )
				selectedUserIndex = 0;
		}

		div.onmouseover = function()
		{
			setAvatar ( avatar );	
		}
		
		div.onmouseout = function()
		{
			if ( !selectedUserIndex )
			{
				var selChildrens = $selHolder.children();

				if ( selChildrens.length )
					setAvatar ( receivers[ selChildrens.first().attr ("id").replace ( /[^0-9]/g, "" ) ].avatar );
			
			} else {
				setAvatar ( receivers [ selectedUserIndex ].avatar );
			}
		}

		div.onmousedown = function ( e )
		{
			setAvatar ( avatar );

			var selChildrens = $selHolder.children();

			if ( selChildrens.length )
			{
				selChildrens.each ( function ( i )
				{
					this.className = this.className.replace ( /[^a-z0-9-_]*hover[^a-z0-9-_]*/g, "" );
				});
			}

			this.className += " hover";
			selectedUserIndex = userID;

			var event = e || window.event;

			if ( event.preventDefault ) 
			{
				event.preventDefault();
			} else {
				event.returnValue = false;
  			}

  			return false;
		}

		div.appendChild ( span1 );
		div.appendChild ( span2 );

		$selHolder.append ( div );
		$selHolder.show();
		$form.append ( hinput );
		$pseudoHolder.removeClass ( "error" );

		if ( avatar )
			setAvatar ( avatar );
	}

	function setReceiverID ( userID )
	{
		var request = $.ajax(
		{
		  type: "GET",
		  url: urlBase + "ajax/userList?userID=" + userID,
		  dataType: "json"
		});

		request.always ( function ( res )
		{
			hintInProcess = false;
		});

		return request.done ( function ( res )
		{
			if ( typeof res.error != "undefined" )
			{
				return;
			}

			$ul.empty();
			var tmp = res.shift();
			selectUser ( tmp.userID, tmp.nick, tmp.avatar );
		});
	}

	$input.focus();

	this.setReceiverID = setReceiverID;
}

window.writeMail = null;

$(function() {

	window.writeMail = new mailProcessor;

});