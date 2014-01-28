var pupld = null;

function picUpload()
{
	if ( typeof urlBase == "undefined" )
	{
		return alert ( "Переменная urlBase не определена, инициализация скрипта ajaxPicUpload.js не возможна." );
	}

	var self = this;
	this.itemName = "";

	$("#picUpld").bind ("change", function()
	{
		self.observeFileField (this);
	});

	$(".deletePic").bind ("click", function()
	{
		self.deletePicture (this.id, this);
	});

	this.iframe = $("#picFrame");
	this.userForm = $("#form");
	this.picsDiv = $("#picDiv");
	this.picForm = $("#picForm");
	this.extAllowed = ["jpeg", "jpg", "bmp", "png", "gif"];

	function initIframeContent()
	{
		var form = document.createElement ("form");
		form.method = "post";
		form.name = "picRealUpload";
		form.action = urlBase+"ajax/picture";
		form.enctype = "multipart/form-data";

		var formMarker = document.createElement ("input");
		formMarker.name = "ajaxFileUpload";
		formMarker.value = "yes";
		formMarker.type = "text";

		var pageDir = document.createElement ("input");
		pageDir.name = "pageDir";
		pageDir.value = picsDir;
		pageDir.type = "text";

		form.appendChild (formMarker);
		form.appendChild (pageDir);

		self.jbody = self.iframe.contents().find ("body");
		self.jbody.append (form);

		//console.log (jbody);

		return form;
	}

	function formVerification (fileType)
	{
		self.itemName = self.userForm.find ("input[name='title']").val().trim();
		self.slugName = self.userForm.find ("input[name='slug']").val().trim();

		if (self.itemName === "")
		{
			showError ("Заголовок не может быть пустым.");
			return false;
		}

		var ext = fileType.substr (fileType.lastIndexOf('.') + 1);

		if (self.extAllowed.indexOf(ext) == -1)
		{
			if (!confirm ("Файл имеет недопустимое расширение."+
				"\nДопустимые: "+self.extAllowed.join (", ")+"."+
				"\n\nВы можете проигнорировать это предупреждение нажав 'Ok'."+
				"\n\nОднако, не удивляйтесь, если картинка не загрузится."))
			{
				return false;
			}
			
		}

		return true;
	}

	function showError (txt)
	{
		alert (txt);
	}

	function addInputToForm (name, value, type)
	{
		if (!type)
			type = "text";

		var input = document.createElement ("input");
		input.name = name;
		input.value = value;
		input.type = type;

		self.iframeContents.appendChild (input);
	}

	this.observeFileField = function (input)
	{
		if (!formVerification (input.value))
		{
			input.value = "";
			return;
		}

		self.iframeContents = initIframeContent();

		self.iframeContents.appendChild (input);
		self.getThumbSize();
		self.uploadPicture();
	}

	this.deletePicture = function ( picID, object )
	{
		if (self.itemName === "" || !self.itemName)
		{
			self.itemName = self.userForm.find ("input[name='slug']").val().trim();
		}

		var data = {"pictureDelete":picID, "picsDir":picsDir, "dirName":self.itemName};

		$.ajax({
			url: urlBase+"ajax/deletePicture",
			type: "POST",
			data: data,
			success: function (response)
			{
				if (response == "Ok")
				{
					var jobj = $(object).parent();
					jobj.remove();

					if (self.picsDiv.children().length == 1)
					{
						$("#pics").hide ("fast");
					}
				}
			}
		});
	}

	this.uploadPicture = function()
	{
		addInputToForm ("title", self.itemName);
		addInputToForm ("slug", self.slugName);

		self.iframeContents.submit();
	}

	this.uploadFinished = function (json)
	{
		var array = jQuery.parseJSON (json);
		self.itemName = array.itemName;

		//console.log (array);
	
		var div = document.createElement ("div");
		var wrapDiv = document.createElement ("div");
		wrapDiv.className = "pic-windows-wrap";
		div.className = "pic-windows";
		var a = document.createElement ("a");
		a.target = "_blank";
		a.href = "/content/"+picsDir+"/"+self.itemName+"/"+array.small;
		a.className = "showPicLink";
		var imgThumb = document.createElement ("img");
		imgThumb.className = "showPic";

		if (array.small)
			imgThumb.src = "/content/"+picsDir+"/"+self.itemName+"/"+array.small;
		else imgThumb.src = "/content/"+picsDir+"/"+self.itemName+"/"+array.big;

		a.appendChild (imgThumb);
		wrapDiv.appendChild (a);

		var aDelete = document.createElement ("a");
		var imgDelete = document.createElement ("img");
		var picID = parseInt (array.big.replace (/\.[^.]+$/, ''));
		aDelete.className = "deletePic";
		aDelete.href = "javascript:;";
		aDelete.id = picID;
		aDelete.onclick = function() { self.deletePicture (picID, this); };
		imgDelete.src = urlBase+"resources/img/icons/notifier_close.png";
		aDelete.appendChild (imgDelete);
		wrapDiv.appendChild (aDelete);
		wrapDiv.appendChild (document.createElement ("br"));

		if (array.small)
		{
			var b = document.createElement ("b");
			b.innerHTML = "Код для полноразмерной картинки:";
			wrapDiv.appendChild (b);
			wrapDiv.appendChild (document.createElement("br"));
			var smallInput = document.createElement ("input");
			smallInput.className = "auto-select";
			smallInput.size = 70;
			smallInput.value = "<img src=\"/content/"+picsDir+"/"+self.itemName+"/"+array.big+"\" />";
			wrapDiv.appendChild (smallInput);
			wrapDiv.appendChild (document.createElement("br"));
		}

		var b = document.createElement ("b");
		b.innerHTML = "Код для превью:";
		wrapDiv.appendChild (b);
		wrapDiv.appendChild (document.createElement ("br"));
		var bigInput = document.createElement ("input");
		bigInput.className = "auto-select";
		bigInput.size = 70;
		bigInput.value = "<a class=\"fancybox\" href=\"/content/"+picsDir+"/"+self.itemName+"/"+array.big+"\"><img src=\"/content/"+picsDir+"/"+self.itemName+"/"+array.small+"\" /></a>";
		wrapDiv.appendChild (bigInput);
		
		var p = document.createElement ("p");
		var a = document.createElement ("a");
		a.className = "addPicLink";
		a.innerHTML = "Добавить в текст";
		a.href = "javascript:;";
		a.onclick = function() { addPictureByUrl ( this ); };
		p.appendChild ( a );
		wrapDiv.appendChild ( p );

		div.appendChild ( wrapDiv );
		self.picsDiv.append (div);

		bigInput.onclick = function() { this.select(); };
		smallInput.onclick = function() { this.select(); };

		var fup = document.createElement ("input");
		fup.type = "file";
		fup.id = "picUpld";
		fup.name = "picUpld";
		fup.className = "file";
		fup.onchange = function() { self.observeFileField ( this ); };

		$("#inputFileHolder").append (fup);
		$("#pics").show ("slow");
	}

	this.uploadError = function (json)
	{
		var array = jQuery.parseJSON (json);
		console.log (array);
	}

	this.getThumbSize = function()
	{
		var w = self.userForm.find ("input[name='picWidth']").text();
		var h = self.userForm.find ("input[name='picHeight']").text();

		addInputToForm ("picWidth", w);
		addInputToForm ("picHeight", h);
	}

}

$(function()
{
	pupld = new picUpload;
});

