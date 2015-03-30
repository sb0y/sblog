function userClass()
{
	var plus = "glyphicon-plus";
	var minus = "glyphicon-minus";

	function addFriend ( userID, button )
	{
		buttonAnim.process ( button, "addFriend" );

		$.post ( urlBase + "ajax/friendMngr?action=add&userID=" + userID, function ( res ) 
		{
			buttonAnim.finish ( "addFriend" );

			var $button = $( button );
			var $btnText = $button.find ( "span.btn-text" );
			var $btnIcon = $button.find ( "span.glyphicon" );
			var resArr = res.split ( "|" );

			if ( resArr.shift() == "Ok" )
			{
				$btnText.text ( "Удалить из друзей" );
				$btnIcon.removeClass ( plus );
				$btnIcon.addClass ( minus );

				$button.addClass ( "active" );

			} else {
				$.jGrowl ( "Произошла ошибка. Код:<br />" + res, { theme: "error" } );
			}
		});
	}

	function removeFriend ( userID, button )
	{
		buttonAnim.process ( button, "removeFriend" );

		$.post ( urlBase + "ajax/friendMngr?action=remove&userID=" + userID, function ( res ) 
		{
			buttonAnim.finish ( "removeFriend" );

			var $button = $( button );
			var $btnText = $button.find ( "span.btn-text" );
			var $btnIcon = $button.find ( "span.glyphicon" );

			if ( res == "Ok" )
			{
				$btnText.text ( "Добавить в друзья" );

				$btnIcon.removeClass ( minus );
				$btnIcon.addClass ( plus );

				$button.removeClass ( "active" );

				$button.blur();
			}
		});
	}

	function showMailForm()
	{
		var popup = new Popup;
		
		popup.onLoaded = function() 
		{ 
			buttonAnim.finish ( "sendMailButton" );
			var URLParts = location.pathname.split ( "/" );
			var userID = parseInt ( URLParts.pop() );

			writeMail.setReceiverID ( userID );
		}

		popup.showWindow ( "writeMessage" );
	}

	$(".friendButton").bind ( "click", function() 
	{
		if ( publicSession.userID )
		{
			if ( this.className.search ( "active" ) == -1 )
				addFriend ( this.getAttribute ( "data-id" ), this );
			else removeFriend ( this.getAttribute ( "data-id" ), this );
		
		} else {
			suggestionLogin ( function() { buttonAnim.finish ( "addFriend" ); } );
		}
	});

	$(".sendMailButton").bind ( "click", function()
	{
		buttonAnim.process ( this, "sendMailButton" );

		if ( publicSession.userID )
			showMailForm();
		else suggestionLogin ( function() { buttonAnim.finish ( "sendMailButton" ); } );
	});

	this.addFriend = addFriend;
}

var userUtils = null;

$(function() 
{
	userUtils = new userClass;
});