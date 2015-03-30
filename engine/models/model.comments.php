<?php
class comments extends model_base
{
	public static function start ()
	{
		//var_dump (self::$smarty);
	}
	
	public static function ex_strip_tags ($str)
	{
		$read_html_tag = $read_meta_param = 
		$force_read = $read_meta_tag = false;
		$m_param = $res = '';
		$len = mb_strlen ($str);
		$count_meta_tag_close = 0;

		for ($i=0; $len>$i; $i++)
		{
			$s = mb_substr ($str, $i, 1);

			# end reading then html tag is beginning
			if ($s=='<' && !$read_html_tag && !$force_read)
			{
				$read_html_tag = true;
				continue;
			}

			# we want leave body of tag
			if ($s=='>' && $read_html_tag && !$force_read)
			{
				$read_html_tag = false;
				continue;
			}

			if ($s=='[')
			{
				if (mb_substr ($str, ($i+1), 4)=='code')
				{
					$read_meta_param = 0;
				}
			}

			if ($read_meta_param===0 && $s=='=')
				$read_meta_param = true;

			if ($s!='[' && $read_meta_param)
			{
				if ($s==']')
				{
					$read_meta_param = false;
				}
				
				if ($s!='=' && $read_meta_param)
				{
					$m_param .= $s;
				}
			}

			if ($m_param=='html4strict' || $m_param=='html')
			{
				$force_read = true;
			}

			if ($s==']')
			{
				$count_meta_tag_close++;

				if ($count_meta_tag_close == 2)
				{
					$read_html_tag = $read_meta_param = 
					$force_read = $read_meta_tag = false;
					$count_meta_tag_close = 0;
				}
			}

			if (!$read_html_tag && !$read_meta_tag)
				$res .= $s;
		}

		//echo "<code>$res</code>";

		return $res;
	}

	public static function _url_bbcode_preg ( $m )
	{
		$base = system::param ( "siteDomain" );

		if ( !isset ( $m[1] ) || !$m[1] )
			return $m[0];

		return "<a target=\"_blank\" href=\"http://$base/away?url=" . urlencode ( $m[1] ) . "\">{$m[2]}</a>";
	}

	public static function bbcodes ($text)
	{

		while ( preg_match ( "/\[quote(.*?)\](.*?)\[\/quote\]/ise", $text, $match ) )
		{
			$quoteUserName = "";

			if ( isset ( $match[1] ) && $match[1] )
				$quoteUserName = trim ( str_replace ( '=', '', $match[1] ) );

			$search = "~(".preg_quote ($match[0])."?)~si";
			$replace = "<blockquote>";

			if ( $quoteUserName )
			{
				$replace .= "<p><span id=\"$quoteUserName\" class=\"quoteAuthor\" style=\"font-weight:bold;\">Сообщение от пользователя $quoteUserName</span></p>";
			}

			$replace .= $match[2] . "</blockquote>";
			$text = preg_replace ( $search, $replace, $text );
		}
		
		$bbcode = array(
			"/\[b\](.*?)\[\/b\]/uis" => "<span style=\"font-weight:bold;\">$1</span>",
			"/\[u\](.*?)\[\/u\]/uis" => "<span style=\"text-decoration:underline;\">$1</span>",
			"/\[i\](.*?)\[\/i\]/uis" => "<span style=\"font-style:italic;\">$1</span>",
			"/\[s\](.*?)\[\/s\]/uis" => "<span style=\"text-decoration:line-through;\">$1</span>",
			//"/\[url\=(.*?)\](.*?)\[\/url\]/uis" => "<a target=\"_blank\" href=\"http://$base/away?url=$1\">$2</a>",
			//"/\[size\=(.*?)\](.*?)\[\/size\]/uis" => "<span style=\"font-size:$1;\">$2</span>",
			"/\[color\=(.*?)\](.*?)\[\/color\]/uis" => "<span style=\"color:$1;\">$2</span>",
			"/\[code\=(.*?)\](.*?)\[\/code\]/uis" => "<pre lang=$1>$2</pre>"
		);

		$text = preg_replace ( array_keys ( $bbcode ), array_values ( $bbcode ), $text );
		$text = core::url2href ( $text );
		$text = preg_replace_callback ( "/\[url\=(.*?)\](.*?)\[\/url\]/uis", array ( "comments", "_url_bbcode_preg" ), $text );

		return $text;
	}
	
	public static function get ( $contentID )
	{
		$sqlData = self::$db->query ( "SELECT *, c.`dt`, IF ( c.`reply_to`=0, '', " . 
			"(SELECT u2.`nick` FROM `users` as u2, `comments` as c2 WHERE c2.`userID`=u2.`userID` AND c2.`commentID`=c.`reply_to`) ) as replyNick," .
			"IF ( c.`reply_to`=0, '', (SELECT c2.`commentID` FROM `users` as u2, `comments` as c2 WHERE c2.`userID`=u2.`userID` AND c2.`commentID`=c.`reply_to`) ) as replyCommentID," .
			"IF ( c.`reply_to`=0, '', (SELECT u2.`userID` FROM `users` as u2, `comments` as c2 WHERE c2.`userID`=u2.`userID` AND c2.`commentID`=c.`reply_to`) ) as replyUserID " .
			"FROM `comments` as c, `users` as u WHERE `contentID`=? AND u.`userID`=c.`userID` ORDER BY c.`dt`", $contentID )->fetchAll();

		$sqlData = array_filter ( $sqlData, create_function ( "\$a", 
			"return ( ( isset ( \$a['avatar_small'] ) && \$a['avatar_small'] == 'NULL' ) ? false : true );" ) );

		return $sqlData;
	}

	public static function addCommentQueue ( $contentID, $comments )
	{
		if ( empty ( $comments ) )
		{
			return false;
		}

		foreach ( $comments as $key => $value ) 
		{
			if ( !$comments [ $key ] )
			{
				continue;
			}

			comments::add ( $contentID, $value );
		}

		return true;
	}

	public static function add ( $contentID, $comment = "", $replyUID = 0, $replyID = 0, $replyCommentID = 0 )
	{
		if ( !isset ( $_SESSION["user"] ) || !$comment )
			return false;

		$comment = comments::ex_strip_tags ( $comment );
		$comment = trim ( comments::bbcodes ( $comment ) );
		$insip = system::getClientIP();
		$userID = intval ( $_SESSION [ "user" ] [ "userID" ] );
		$replyUID = intval ( $replyUID );
		$replyCommentID = intval ( $replyCommentID );

		if ( !$comment )
			return false;

		$replyCommentID = 0;
		$article = array();

		if ( $replyCommentID && $replyUID && $_SESSION["user"]["userID"] != $replyUID )
		{
			$rusers_res = self::$db->query ( "SELECT * FROM `users` WHERE `userID`=? LIMIT 1", $replyUID );

			$article_res = self::$db->query ( "SELECT `title`,`type` FROM `content` WHERE `contentID`=? LIMIT 1", $contentID );
			$article = $article_res->fetch();
			$ruser = $rusers_res->fetch();

			$ruser["article_title"] = $article["title"];
			$ruser["article_returnPath"] = self::$routePath;
			$ruser["type"] = $article["type"];
			$ruser["commentID"] = $commentID;

			self::$mail->assign ( "data", $ruser );
			self::$mail->sendMail ( TPL_PATH . "/mail/mailNotifyReply.tpl", $ruser["email"] );
		}

		self::$db->query ("INSERT `comments` SET `contentID`=?, `userID`=?, `dt`=NOW(), `email`='?', `author`='?', `body`='?', `guest`='N', `ip`=INET_ATON('?'), `type`='?', `reply_to`=?", 
			$contentID, $_SESSION["user"]["userID"], $_SESSION["user"]["email"], $_SESSION["user"]["nick"], $comment, $insip, self::$controllerCall, $replyCommentID );

		$commentID = self::$db->insert_id();

		self::$db->query ("UPDATE `content` SET `comments_count`=`comments_count`+1 WHERE `contentID`=? AND `type`='?'", $contentID, self::$controllerCall );

		if ( isset ( $_POST["quotedUID"] ) && $_POST["quotedUID"] )
		{
			$qip = array_filter ( $_POST["quotedUID"], create_function ( "\$a", "return ( $userID == \$a ? false : true );" ) );
			$qip = array_diff ( $qip, array ( $replyUID ) );

			if ( $qip )
			{
				$qip = array_map ( "intval", $qip );
				$qusers_res = self::$db->query ( "SELECT * FROM `users` WHERE `userID` IN (" . implode ( ",", $qip ) . ")" );

				if ( $qusers_res->getNumRows() )
				{
					if ( $article )
					{
						$article_res = self::$db->query ( "SELECT `title`,`type` FROM `content` WHERE `contentID`=? LIMIT 1", $contentID );
						$article = $article_res->fetch();
					}

					$qusers = $qusers_res->fetchAll();

					foreach ( $qusers as $k => $v )
					{
						$v["article_title"] = $article["title"];
						$v["article_returnPath"] = self::$routePath;
						$v["type"] = $article["type"];
						$v["commentID"] = $commentID;
						self::$mail->assign ( "data", $v );
						self::$mail->sendMail ( TPL_PATH . "/mail/mailNotifyQuote.tpl", $v["email"] );
					}
				}
			}
		}

		self::$smarty->clearCurrentCache();
		system::redirect ( "/" . self::$routePath . "/#comment_$commentID" );

		return $commentID;
	}

}
