function commentsCount()
{
	var countList = $(".dynamicCount");
	var requestListObj = [];
	var self = this;
	var isAnimate = true;

	function update ( array )
	{
		for ( var a in array )
		{
			for ( var b in requestListObj )
			{
				if ( a == requestListObj[b].div.id )
				{
					var pint = parseInt ( array[a] );

					if ( pint )
					{
						if ( parseInt ( requestListObj[b].a.innerHTML.replace ( /[^0-9]/g, '' ) ) != pint )
						{
							requestListObj[b].a.innerHTML = "Комментарии (" + pint + ")";
						
							if ( isAnimate )
								$( requestListObj[b].a ).animate ( { backgroundColor: "#ECDF9A" }, 0 )
								.delay(100).animate ( { backgroundColor: "#FFFFFF" }, 2000 );
						}
					} else {
						requestListObj[b].a.innerHTML = "Откомментировать";
					}
				}
			}
		}
	}

	function fetchData ( requestListObj )
	{
		var toSend = {};

		for ( var i = 0; requestListObj.length > i; ++i )
		{
			toSend[i] = requestListObj[i].div.id;
		}

		$.post( urlBase+"ajax/commentsCount/", toSend, function ( res ) 
		{
			var array = jQuery.parseJSON ( res );
			update ( array );
		});
	}

	this.countAllOnPage = function ( animate )
	{
		isAnimate = animate;

		if ( requestListObj.length )
			fetchData ( requestListObj );
	}

	countList.each (function ( i )
	{
		var obj = { "div" : this.parentNode.parentNode.parentNode.parentNode, "a" : this };
		requestListObj.push ( obj );
	});
}

var cc = null;
$(function() 
{
	cc = new commentsCount;
	cc.countAllOnPage ( false );
	setInterval ( function() { cc.countAllOnPage ( true ); }, 50000 );
});
