function Popup()
{
	this.onLoaded = null;

	var twilightObj = null;
	var body = $("body");
	var windows = {};
	var showOptions = {};
	
	this.twilight = function()
	{
		if (!twilightObj)
		{
			twilightObj = document.createElement ("div");
			twilightObj.className = "twilight-effect";
			body.append ($(twilightObj));
		} else {
			$(twilightObj).remove();
		}
		
	}
	
	this.closeWindow = function (tplName)
	{
		$(windows[tplName]).remove();
		delete windows[tplName];
		windows[tplName] = null;
		body.attr ( "class", "" );
		//this.twilight();
	}
	
	this.showWindow = function (tplName)
	{
		var self = this;
		
		$.post ( urlBase + "ajax/getTpl/" + tplName + 
			"?fromUrl=" + encodeURI ( window.location ), showOptions,
		function ( data )
		{
			var div = document.createElement ("div");
			div.className = "popup-layer";
			var imgClose = document.createElement ("img");
			imgClose.src = urlBase + "resources/images/close.png";
			imgClose.className = "close-btn";
			imgClose.onclick = function() { self.closeWindow ( tplName ); };
			body.prepend (div);
			windows[tplName] = div;

			div.onclick = function ( e ) 
			{
				if ( !e ) e = window.event;
				var target = e.target || e.srcElement

				if ( target == this )
				{
					self.closeWindow (tplName);
				}
			};
			
			$( div ).html ( "<!--[if lt IE 9]><div class=\"popup-layer popup_overlay_ie\"></div><![endif]--><div class=\"popup\">" + 
				data + "</div><!--[if lt IE 9]><div class=\"popup_valignfix\"></div><![endif]-->" );
		
			body.attr ( "class", "body-lock" );

			div._closePopup = function()
			{
				self.closeWindow ( tplName );	
			}

			div.childNodes[1].insertBefore ( imgClose, div.childNodes[1].firstChild );
			showOptions = {};

			if ( typeof self.onLoaded == "function" )
			{
				self.onLoaded();
				self.onLoaded = null;
			}
		});
	}

	this.setOptions = function ( opts )
	{
		showOptions = opts;
	}

	this.windowExist = function ( wndID )
	{
		if ( typeof windows[wndID] == "undefined" || !windows[wndID] )
			return false;

		return true;
	}
}
