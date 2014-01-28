function textEditor()
{
	var textArea = $(".comment-area");
	var self = this;
	var activeEditor = null;
	var lastReplyUserName = "";

	function replyPost ( baseNode )
	{
		var $rootNode = $( baseNode );
		var replyTo = $rootNode.parent().parent().find (".authorName").text().trim();
		var txt = $.trim ( activeEditor.value );
		var $textbody = $rootNode.find ( ".post-data#textbody" );
		var userID = parseInt ( baseNode.getAttribute ( "data-userid" ) );
		var commentID = parseInt ( baseNode.getAttribute ( "data-id" ) );

		if ( !txt.length || ( lastReplyUserName + "," ) == txt )
		{
			activeEditor.value = replyTo + ", ";
			lastReplyUserName = replyTo;
		}

		$("#reply_ID_placeholder").html ( "<input type=\"hidden\" name=\"replyID\" value=\"" + commentID + "\" />\n" +
		 "<input type=\"hidden\" name=\"replyUID\" value=\"" + userID + "\" />");

		$( "#replyTo" ).html ( "Ответ пользователю <a href=\"" + urlBase + "user/profile/" + userID + "\" target=\"_blank\">" + 
			replyTo + "</a>" );

		window.scrollTo ( 0, document.getElementsByTagName ( "body" )[0].scrollHeight );
	}

	function quotePost ( baseNode )
	{
		var rootNode = baseNode.parentNode.parentNode.parentNode;
		var $rootNode = $(rootNode);
		var $div = $rootNode.find ( "div" );
		var quoteAuthor = $rootNode.find (".authorName").text().trim();
		var quotedText = $div.html();
		// не знаю какого х*я, но сказать просто (.*?) не удалось, на хроме символ \n не выцеплялся
		var re = new RegExp ( /<blockquote><p><span id="(.*?)" class="quoteAuthor".*?>.*?<\/span>.*?<\/p>([\n\s\S]*?)<\/blockquote>/ );

		while ( quotedText.search ( re ) != -1 )
		{
			quotedText = quotedText.replace ( re, "[quote=$1]$2[/quote]" );
		}

		var re = new RegExp ( /<blockquote>([\n\s\S]*?)<\/blockquote>/ );

		while ( quotedText.search ( re ) != -1 )
		{
			quotedText = quotedText.replace ( re, "[quote]$1[/quote]" );
		}

		$("#quote_ID_placeholder").append ( "<input type=\"hidden\" name=\"quotedUID[]\" value=\"" + 
			baseNode.getAttribute ( "data-userid" ) + "\" />" );

		quotedText = $.trim ( quotedText.replace (/<\/?[^>]+>/g, '') );

		insertTag ( "quote", activeEditor, quoteAuthor, quotedText, true );
		window.scrollTo ( 0, document.getElementsByTagName ( "body" )[0].scrollHeight );
	}

	function insertTagWithText ( tagName, targetObj )
	{ 
		if ( document.getSelection ) 
		{ 
			var selection = document.getSelection() 
		} else if (document.selection) { 
			var selection = document.selection.createRange().text; 
		} else { 
			var selection = '';
		}

		insertTag ( tagName, targetObj, false, selection );
	}

	function insertLink ( targetObj )
	{
		var href = prompt ( "Введите URL ссылки", "http://" ); 
		if ( href )
		{ 
			insertTag ( "url", targetObj, href );
		} 
	}

	function insertTag ( tag, targetObj, param, content, newline )
	{
		var preSym = "";

		if ( newline )
			preSym = "\n";

		var startTag = "[" + tag;

		if ( param )
		{
			startTag += "=" + param;
		}

		startTag += "]" + preSym;

		if ( content )
		{
			startTag += content;
		}

		var endTag = preSym + "[/" + tag + "]" + preSym;

		targetObj.focus(); 
		var scrtop = targetObj.scrollTop;
		var cursorPos = getCursor ( targetObj );
		var txt_pre = targetObj.value.substring ( 0, cursorPos.start );
		var txt_sel = targetObj.value.substring ( cursorPos.start, cursorPos.end ); 
		var txt_aft = targetObj.value.substring ( cursorPos.end );

		var nuCursorPos = 0;

		if ( tag == "quote" )
		{
			nuCursorPos = String ( txt_pre + startTag + txt_sel + endTag ).length;
		} else {
			if ( cursorPos.start == cursorPos.end )
			{ 
				nuCursorPos = cursorPos.start + startTag.length;
			} else { 
				nuCursorPos = String ( txt_pre + startTag + txt_sel + endTag ).length;
			}
		}

		targetObj.value = txt_pre + startTag + txt_sel + endTag + txt_aft; 
		setCursor ( targetObj, nuCursorPos, nuCursorPos ); 
				
		if ( scrtop ) targetObj.scrollTop = scrtop;
	}

	function setCursor ( targetObj, start, end )
	{
		if ( targetObj.createTextRange )
		{
			var range = targetObj.createTextRange();
			range.move ("character",start);
			range.select();
		} else if (targetObj.selectionStart) {
			targetObj.setSelectionRange ( start,end );
		}
	}

	function getCursor (input) 
	{
		var result = { "start":0 ,"end":0 };

		if ( input.setSelectionRange ) 
		{
			result.start = input.selectionStart;
			result.end = input.selectionEnd;
		} else if ( !document.selection ) { 
			return false;
		} else if ( document.selection && document.selection.createRange ) {

			var range = document.selection.createRange();
			var stored_range = range.duplicate();
			stored_range.moveToElementText ( input );
			stored_range.setEndPoint ( "EndToEnd", range );
			result.start = stored_range.text.length - range.text.length;
			result.end = result.start + range.text.length;
		}

		return result;
	}

	function init ( textArea )
	{
		var parent = textArea.parentNode;
		var targetObj = $( parent );

		activeEditor = textArea;

		targetObj.find ("a#insertBold").bind ("click", function()
		{
			insertTag ("b", textArea);
		});

		targetObj.find ("a#insertItalic").bind ("click", function()
		{
			insertTag ("i", textArea);
		});

		targetObj.find ("a#insertUnderline").bind ("click", function()
		{
			insertTag ("u", textArea);
		});

		targetObj.find ("a#insertStrike").bind ("click", function()
		{
			insertTag ("s", textArea);
		});

		targetObj.find ("a#insertLink").bind ("click", function()
		{
			insertLink ( textArea );
		});

		targetObj.find ("a#insertBlockquote").bind ("click", function()
		{
			insertTagWithText ( "quote", textArea );
		});

	}

	textArea.each ( function ( k, v )
	{
		if ( typeof v != "object" && 
			 v.tagName != "textarea" ) return true;

		init ( v );
	});

	$(".quoteThisComment").bind ( "click", function ()
	{
		if ( !publicSession.userID )
		{
			suggestionLogin();
			return false;
		}
		
		quotePost ( this );
	});

	$(".replyAction").bind ( "click", function()
	{
		if ( !publicSession.userID )
		{
			suggestionLogin();
			return false;
		}

		if ( this.className.search ( /replyAction/ ) == -1 ||
			 this.id == "authorNickHref" )
		{
			return false;
		}

		replyPost ( this );
	});

	$(".baloonRating").bind ( "mouseover", function()
	{
		var self = this;

		if ( this.innerHTML == 0 )
			return false;

		var request = $.ajax(
		{
		  type: "GET",
		  url: "/ajax/baloonRatingInfo?commentID=" + parseInt ( this.getAttribute ( "data-id" ) ),
		  dataType: "json"
		});

		return request.done ( function ( res )
		{
			if ( typeof res.error != "undefined" )
			{
				$.jGrowl ( "Произошла ошибка. Код:<br />" + res, { theme: "error" } );
				return;
			}

			var txt = document.createElement ( "span" );
			var p = m = r = 0;
			for ( var i in res )
			{
				if ( res[i].voteType == "+" )
					++p;
				else if ( res[i].voteType == "-" )
					++m
			}

			r = p - m;
			txt.style.cssText = "font-size:16px; font-weight: bold";
			txt.innerHTML = "+ " + p + " - " + m + " = " + r;

			baloons.showBaloon ( self, txt );
		});
	});

	$("#comment-textarea").keypress ( function ( e ) 
	{
		var keycode = ( event.keyCode ? event.keyCode : event.which );
		if ( keycode == 13 )
		{
			e.preventDefault();
			$( "#commentForm" ).submit();
		} else if ( keycode == 10 ) {
			activeEditor.focus(); 
			activeEditor.value += "\n";
		}
	});

}

var textAreaMain;
$(function() 
{
	textAreaMain = new textEditor;
});