function newMailMessageClass()
{
	var $history = $("#showHistoryButton");
	var $historyHolder = $("#messageHolder");
	var $form = $("#mailWriteForm");

	$form.bind ( "keydown", function ( e )
	{
		if ( e.keyCode == 13 && e.ctrlKey )
		{
			e.preventDefault();
			$form.submit();
		}
	});

	$history.bind ( "click", function ( e )
	{
		var senderID = parseInt ( this.getAttribute ( "data-senderID" ) );
		var receiverID = parseInt ( this.getAttribute ( "data-receiverID" ) );

		$historyHolder.show ( "slow" );

		$.get ( ( urlBase + "ajax/getMailHistory?senderID="+senderID+"&receiverID="+receiverID ), 
		function ( res )
		{
			if ( res === "" )
				return;

			var data = $.parseJSON ( res );

			if ( !data )
			{
				$.jGrowl ( "Произошла непредвиденная ошибка.", { theme: "error" } );
				return;
			}

			showData ( data );

		});

		e.preventDefault();
	});

	function showData ( data )
	{
		$historyHolder.empty();
		$historyHolder.append ( "<h2>История переписки</h2><br>" );
		var table = document.createElement ( "table" );
		var tbody = document.createElement ( "tbody" );

		table.appendChild ( tbody );
		table.className = "mailHistory table table-bordered table-striped";

		for ( var i in data )
		{
			var tr = document.createElement ( "tr" );
			var td1 = document.createElement ( "td" );
			var td2 = document.createElement ( "td" );
			var td3 = document.createElement ( "td" );
			var titleDiv = null;

			if ( data[i].subject !== "" )
			{
				titleDiv = document.createElement ( "div" );
				titleDiv.className = "ttl";
				titleDiv.innerHTML = data[i].subject;
				td2.appendChild ( titleDiv );
			}

			td1.className = "userNick";
			td2.className = "message";
			td3.className = "messageTime";

			tr.appendChild ( td1 );
			tr.appendChild ( td2 );
			tr.appendChild ( td3 );

			var dt = new Date ( parseInt ( data [ i ].tms ) * 1000 );
			var hours = dt.getUTCHours();
			var minutes = dt.getUTCMinutes();
			var day = dt.getUTCDate();
			var seconds = dt.getUTCSeconds();
			var mounths = dt.getUTCMonth() + 1; //January is 0!
			var year = new String ( dt.getUTCFullYear() );

			if ( day < 10 )
			{
    			day = '0' + day;
			} 

			if ( mounths < 10 ) 
			{
    			mounths = '0' + mounths;
			} 

			td3.innerHTML = hours + ":" + minutes + " <strong>" + day + "." + mounths + "." + year.substr ( 2, year.length ) + "</strong>";

			td2.onclick = function()
			{
				window.location.href = urlBase + "user/mail/message/" + data [ i ].messageID;
			}

			var a = document.createElement ( "a" );
			td1.appendChild ( a );
			a.innerHTML = data[i].nick;
			a.href = urlBase + "user/profile/" + data[i].userID;

			if ( data [ i ].body.length >= 150 )
				data [ i ].body = data [ i ].body.substr ( 0, 150 ) + " ...";

			$( td2 ).html ( data [ i ].body );
		
			tbody.appendChild ( tr );
		}

		$historyHolder.append ( table );
		$history.hide();
	}

}

window.newMailMessage = null;

$(function() {

	window.newMailMessage = new newMailMessageClass;

});