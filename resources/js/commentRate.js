$(function() 
{
    $(".ratingUp").bind ( "click", function ( e )
    {
        if ( !publicSession.userID )
        {
            suggestionLogin();
            return false;
        }

        var comment = DOMLevelUP ( this, 6 );
        var commentID = parseInt ( comment.getAttribute ( "data-id" ) );
        $.post( urlBase + "ajax/changeRate", { commentID: commentID, type: "up" })
          .done(function( data ) {
            data = $.trim ( data );
            switch ( data )
            {
                case 'Login Error':
                    $.jGrowl ( data, { theme: "error" } );
                break;
                case 'Error':
                    $.jGrowl ( data, { theme: "error" } );
                break;
                case "Vote twice":
                    $.jGrowl ( "Нельзя голосовать дважды.", { theme: "warning" } );
                break;

                case "Vote for himself disallowed":
                    $.jGrowl ( "Нельзя голосовать за себя, любимого.", { theme: "error" } );
                break;

                default:
                    $( '.ratingCounter.comment_' + commentID ).text ( data );
                break;
            }
        });
    });

    $(".ratingDown").bind ( "click", function ( e )
    {
        if ( !publicSession.userID )
        {
            suggestionLogin();
            return false;
        }

        var comment = DOMLevelUP ( this, 6 );
        var commentID = parseInt ( comment.getAttribute ( "data-id" ) );
        $.post( urlBase + "ajax/changeRate", { commentID: commentID, type: "down" })
          .done(function( data ) {
            data = $.trim ( data );
            switch ( data )
            {
                case 'Login Error':
                    $.jGrowl ( data, { theme: "error" } );
                break;

                case 'Error':
                    $.jGrowl ( data, { theme: "error" } );
                break;

                case "Vote twice":
                    $.jGrowl ( "Нельзя голосовать дважды.", { theme: "warning" } );
                break;

                case "Vote for himself disallowed":
                    $.jGrowl ( "Нельзя голосовать за себя, любимого.", { theme: "error" } );
                break;

                default:
                    $( '.ratingCounter.comment_' + commentID ).text ( data );
                break;
            }
            
        });
    });
});