function userClass()
{
	function addFriend ( userID, button )
	{
		buttonAnim.process ( button, "addFriend" );

		$.post ( urlBase + "ajax/friendMngr?action=add&userID=" + userID, function ( res ) 
		{
			buttonAnim.finish ( "addFriend" );
			var resArr = res.split ( "|" );

			if ( resArr.shift() == "Ok" )
			{
				button.innerHTML = resArr.shift() + " у вас в друзьях";
				var bclasses = button.className.split ( " " );
				for ( var i=0; bclasses.length > i; ++i )
				{
					if ( bclasses[i] == "add" )
						bclasses[i] = "remove";
				}

				button.className = bclasses.join ( " " );
				button.className += " active";
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

			if ( res == "Ok" )
			{
				button.innerHTML = "Добавить в друзья";
				var bclasses = button.className.split ( " " );
				for ( var i=0; bclasses.length > i; ++i )
				{
					if ( bclasses[i] == "remove" )
						bclasses[i] = "add";

					if ( bclasses[i] == "active" )
						bclasses[i] = "";
				}

				button.className = bclasses.join ( " " );
				button.blur();
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