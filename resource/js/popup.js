function Popup()
{
	var twilightObj = null;
	var body = $("body");
	var windows = {};
	
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
		body.attr ("class", "");
		//this.twilight();
	}
	
	this.showWindow = function (tplName)
	{
		var self = this;
		
		var div = document.createElement ("div");
		div.className = "popup-layer";
		var imgClose = document.createElement ("img");
		imgClose.src = urlBase+"resource/images/close.png";
		imgClose.className = "close-btn";
		imgClose.onclick = function() { self.closeWindow (tplName); };
		body.prepend (div);
		windows[tplName] = div;

		div.onclick = function (e) 
		{
			if (!e) e = window.event;
			var target = e.target || e.srcElement

			if (target == this)
			{
				self.closeWindow (tplName);
			}
		};
		
		//this.twilight();
		//twilightObj.onclick = function () {t.hideWindow (tplName)};
		
		$.get ("http://"+document.domain+"/ajax/getTpl/"+tplName+"?fromUrl="+encodeURI(window.location), function(data)
		{
			div.innerHTML = "<!--[if lt IE 9]><div class=\"popup-layer popup_overlay_ie\"></div><![endif]--><div class=\"popup\">"+
				data+"</div><!--[if lt IE 9]><div class=\"popup_valignfix\"></div><![endif]-->";
		
			body.attr ("class", "body-lock");

			div.childNodes[1].insertBefore (imgClose, div.childNodes[1].firstChild);
		});
	}
}
