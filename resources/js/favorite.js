$(function() 
{
	var $btn = $(".addToFavorite");
	var data = { "contentID" : $btn.attr ( "id" ) };

	$btn.bind ( "click", function() 
	{
		data["isActive"] = $btn.hasClass ( "active" );

		$.post ( urlBase + "ajax/favContent", data, function ( res ) 
		{
			if ( res == "Ok" )
			{
				$btn.toggleClass ( "active" );

				if ( data.isActive )
					$.jGrowl ( "Удалено из избранного." );
				else $.jGrowl ( "Добавлено в избранное." );

			} else if ( res !== "" ) {
				$.jGrowl ( "Произошла непредвиденная ошибка. Код:<br />" + res, { theme: "error" } );
			} else {
				$.jGrowl ( "Произошла непредвиденная ошибка.", { theme: "error" } );
			}
		});
	});
});