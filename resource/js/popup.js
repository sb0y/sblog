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
	
	this.hideWindow = function (tplName)
	{
		$(windows[tplName]).remove();
		delete windows[tplName];
		windows[tplName] = null;
		body.attr ("class", "");
		this.twilight();
	}
	
	this.showWindow = function (tplName)
	{
		var t = this;
		
		var div = document.createElement ("div");
		div.className = "popup-layer";
		var imgClose = document.createElement ("img");
		imgClose.src = urlBase+"resource/images/close.png";
		imgClose.className = "close-btn";
		imgClose.onclick = function() {t.hideWindow (tplName)};
		var span = document.createElement ("span");
		div.appendChild (imgClose);
		div.appendChild (span);
		body.append (div);
		windows[tplName] = div;
		this.twilight();
		twilightObj.onclick = function () {t.hideWindow (tplName)};
		body.attr ("class", "body-lock");
		
		
		$.get ("http://"+document.domain+"/ajax/getTpl/"+tplName+"?fromUrl="+encodeURI(window.location), function(data)
		{
			span.innerHTML = data;
		});
	}
}
