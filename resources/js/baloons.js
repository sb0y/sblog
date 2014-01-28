function baloon()
{
	var div = null;
	var wrap = null;
	var internalID = 0;
	var isItHovered = true;
	this.callObj = null;

	function initDiv()
	{
		var arrow = document.createElement ( "div" );
		arrow.className = "arrow down";
		wrap = document.createElement ( "div" );
		div = document.createElement ( "div" );
		div.className = "baloon";
		wrap.className = "baloonWrap";
		div.style.cssText = "position: absolute;";
		wrap.style.cssText = "pointer-events: auto;";

		div.appendChild ( wrap );
		div.appendChild ( arrow );
	}

	function fadeOut()
	{

	}

	function fadeIn()
	{

	}

	function closeBaloon ( obj )
	{
		if ( !div )
			return false;

		//fadeOut();
		div.parentNode.removeChild ( div );
		delete baloons.array [ internalID ];
		baloons.array [ internalID ] = null;
		obj.onmouseout = null;

		var blne = false;
		for ( var i = 0; baloons.array.length > i; ++i )
		{
			if ( baloons.array[i] !== null )
			{
				blne = true;
			}

			if ( !blne )
			{
				baloons.array = [];
				break;
			}
		}
	}

	function setBaloonID ( id )
	{
		internalID = id;
	}

	function showBaloon ( obj, html )
	{
		if ( !div )
			initDiv();

		var tms = 0;
		var parent = obj.parentNode;
		parent.insertBefore ( div, parent.firstChild );

		var txt = document.createElement ( "div" );
		txt.className = "baloonText";

		if ( typeof html == "string" )
			txt.innerHTML = html;
		else txt.appendChild ( html );

		wrap.insertBefore ( txt, wrap.firstChild );

		div.onmouseout = function()
		{
			tms = setTimeout ( function()
			{
				closeBaloon ( obj );
			}, 1500 );
		}

		obj.onmouseout = function()
		{
			tms = setTimeout ( function()
			{
				closeBaloon ( obj );
			}, 1500 );
		}

		div.onmouseover = function()
		{
			clearTimeout ( tms );
		}

		obj.onmouseover = function()
		{
			clearTimeout ( tms );
		}

		this.callObj = obj;
		div.style.marginTop = "-" + ( obj.offsetHeight + div.offsetHeight - 10 ) + "px";
		div.style.left =  ( obj.offsetLeft - 27 ) + "px";
		//div.style.marginLeft = "-25px";

	}

	this.showBaloon = showBaloon;
	this.closeBaloon = closeBaloon;
	this.setBaloonID = setBaloonID;
}

var baloons = 
{
	array: [],

	showBaloon:function ( obj, html )
	{
		for ( var i = 0; this.array.length > i; ++i )
		{
			if ( this.array[i] && this.array[i].callObj == obj )
				return true;
		}

		var bl = new baloon;
		bl.setBaloonID ( this.array.length );
		bl.showBaloon ( obj, html );
		this.array.push ( bl );
	}
}