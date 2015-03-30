function mailListProcessor()
{
	var $form = $("#listForm");
	var $table = $form.find ( "table#mailTable" );
	var $panel = $( "#mailPanel" );
	var $removeButton = $panel.find ( "#removeButton" );
	var $markAsRead = $panel.find ( "#markAsRead" );
	var $markAsUnread = $panel.find ( "#markAsUnread" );
	var $mailCountHrefInt = $("#mainCountHrefInt");

	$(".messageRow").bind ( "click", function ( e )
	{
		if ( e.toElement.className == "checkBox" )
		{
			e.stopPropagation();
			return false;
		}

		var id = this.getAttribute ( "data-id" );
		
		if ( !id )
			return false;

		location.href = urlBase + "user/mail/message/" + id;
	});

	$markAsUnread.bind ( "click", function()
	{
		var $trs = $table.find ( "tr.active" );

		if ( !$trs.length )
			return false;

		var array = makeMarkArray ( $trs, "N" );

		$.post ( urlBase + "ajax/markMails", array.data, 
		function ( res )
		{
			if ( res == "Ok" )
			{
				$trs.addClass ( "unreadMail" );
				$trs.each ( function()
				{
					var $node = $( this );
					var $ch = $node.find ( "input[type='checkbox'].checkBox" );

					selectMail ( $node, $ch );
				});

				$mailCountHrefInt.text ( ( parseInt ( $mailCountHrefInt.text() ) + array.size ) );
			}
		});

	});

	$markAsRead.bind ( "click", function()
	{
		var $trs = $table.find ( "tr.active" );

		if ( !$trs.length )
			return false;

		var array = makeMarkArray ( $trs, "Y" );

		$.post ( urlBase + "/ajax/markMails", array.data, 
		function ( res )
		{
			if ( res == "Ok" )
			{
				$trs.removeClass ( "unreadMail" );
				$trs.each ( function()
				{
					var $node = $( this );
					var $ch = $node.find ( "input[type='checkbox'].checkBox" );

					selectMail ( $node, $ch );
				});

				var mailCount = parseInt ( $mailCountHrefInt.text() ) - array.size;
				$mailCountHrefInt.text ( mailCount < 0 ? 0 : mailCount );
			}
		});
	});

	$removeButton.bind ( "click", function()
	{
		if ( !confirm ( "Вы уверенны?" ) )
			return false;	

		var $trs = $table.find ( "tr.active" );

		if ( !$trs.length )
			return false;

		var list = {};
		var dcnt = 0;

		$trs.each ( function()
		{
			var isRead = 0;

			if ( this.className.search ( /unreadMail/ ) != -1 )
			{
				isRead = 1;
				++dcnt;
			}

			list [ this.getAttribute ( "data-id" ) ] = isRead;
		});

		$.post ( urlBase + "/ajax/deleteMailMessages", list, 
		function ( res )
		{
			if ( res == "Ok" )
			{
				var res = parseInt ( $mailCountHrefInt.text() ) - dcnt;

				if ( res > 0 )
				{
					$mailCountHrefInt.text ( res );
				} else {
					$mailCountHrefInt.parent().html ( "Личные сообщения" );
				}

				$trs.css ( "backgroundColor", "#FFA6A6" ).fadeOut ( 1000, function()
				{
					$( this ).remove();
					checkTable();
				});
			}
		});
	});

	$(".deleteMail").bind ( "click", function ( e )
	{
		if ( !confirm ( "Вы уверенны?" ) )
			return false;

		e.stopPropagation();

		var root = this;
		var $node = $( this.parentNode.parentNode );
		var tmp = {};

		tmp [ $node.attr ( "data-id" ) ] = ( $node.hasClass ( "unreadMail" ) ) ? 1 : 0;

		$.post ( urlBase + "/ajax/deleteMailMessages", tmp, 
		function ( res )
		{
			if ( res == "Ok" )
			{
				var res = parseInt ( $mailCountHrefInt.text() ) - 1;

				if ( res > 0 )
				{
					$mailCountHrefInt.text ( res );
				} else {
					$mailCountHrefInt.parent().html ( "Личные сообщения" );
				}

				$node.css ( "backgroundColor", "#FFA6A6" ).fadeOut ( 1000, function()
				{ 
					$( this ).remove();
					checkTable();
				});	
			}
		});
	});

	$(".checkBox").bind ( "click", function ( e )
	{
		e.stopPropagation();

		var $node = $( this.parentNode.parentNode );
		var $ch = $( this );

		selectMail ( $node, $ch );
	});

	function makeMarkArray ( $array, mark )
	{
		var tmp = {};
		var size = 0;

		$array.each ( function()
		{
			var id = this.getAttribute ( "data-id" );
			tmp [ id ] = mark;
			++size;
		});

		return { "data":tmp, "size":size };
	}

	function togglePanel()
	{
		if ( $table.find ( "tr.active" ).length )
		{
			$panel.show ( "slow" );
		} else {
			$panel.hide ( "slow" );
		}
	}

	function checkTable()
	{
		if ( !$table.find ( "tr" ).length )
		{
			$form.append ( "<p>Вам ещё никто не писал =(</p>" );
			$panel.css ( "visibility", "hidden" );
		}
	}

	function selectMail ( $node, $ch )
	{
		if ( $node.hasClass ( "active" ) )
		{
			$node.removeClass ( "active" );
			$ch.attr ( "checked", false );
			togglePanel();
		} else {
			$node.addClass ( "active" );
			$ch.attr ( "checked", true );
			togglePanel();
		}
	}

}

window.mailList = null;

$(function() {

	window.mailList = new mailListProcessor;

});