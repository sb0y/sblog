vkExportAuth = {
	
	http:null,
	targetURL:"",
	status:0,
	sendBackURL:"",
	wnd:null,

	getHttp:function()
	{
		if (typeof this.xmlhttp == "undefined")
		{
			try {
				this.xmlhttp  = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					this.xmlhttp  = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (E) {
					this.xmlhttp  = false;
				}
			}
			
			if (!this.xmlhttp && typeof XMLHttpRequest != "undefined")
			{
				this.xmlhttp = new XMLHttpRequest();
			}
		}
		
		return this.xmlhttp;
	},

	createWindow:function ( targetURL )
	{
		if ( !targetURL )
		{
			alert ( "Не могу получить ссылку для запроса привелегий доступа у VK." );
			return;
		}

		var h = 100;
		var w = 100;
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		this.wnd = window.open ( targetURL, "Авторизация в вконтаче", 
			'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w
			+', height='+h+', top='+top+', left='+left );

	},

	click:function()
	{
		if ( this.targetURL == "ok" )
		{
			if ( confirm ( "Уже сконфигурировано. Ещё раз?" ) )
			{
				this.init ( this.sendBackURL, "?get_auth_url=1&clear=1" );
				this.createWindow ( "https://oauth.vk.com/blank.html" );
			}

			return;
		}

		this.createWindow ( this.targetURL );
	},

	clear:function()
	{
		this.wnd.close();
		this.targetURL = "ok";
		this.status = 0;
		//this.sendBackURL = "";
		this.wnd = null;
	},

	init:function ( sendBackURL, addURLParams )
	{
		var self = this;
		var rurl = "";
		this.sendBackURL = sendBackURL; // for reuse

		if ( addURLParams )
		{
			sendBackURL += addURLParams;
		} else {
			sendBackURL += "?get_auth_url=1";
		}

		this.http = this.getHttp();
		this.http.open ( "GET", sendBackURL, true );
		this.http.onreadystatechange = function()
		{
			if ( self.http.readyState == 4 && self.http.status == 200 )
			{
				rurl = self.http.responseText.replace ( /^\s+|\s+$/g, '' ); // trim
				self.status = 1;

				if ( self.targetURL == "ok" && self.wnd !== null )
				{
					self.wnd.location = rurl;
				}

				self.targetURL = rurl;
			}
		}
		
		this.http.send();
	}
}
