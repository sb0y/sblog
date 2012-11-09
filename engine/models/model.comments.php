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

	public static function bbcodes ($text)
	{
		while (preg_match("/\[quote\](.*?)\[\/quote\]/ise", $text, $match))
		{
			#print_r ($match);
			$search = "~".preg_quote ($match[0])."~si";
			$replace = "<blockquote>".$match[1]."</blockquote>";
			$text = preg_replace ($search, $replace, $text);
			$match = "";
		}
		
		$bbcode = array(
		"/\[b\](.*?)\[\/b\]/is" => "<span style=\"font-weight: bold\">$1</span>",
		"/\[u\](.*?)\[\/u\]/is" => "<span style=\"text-decoration: underline\">$1</span>",
		"/\[i\](.*?)\[\/i\]/is" => "<span style=\"font-style: italic\">$1</span>",
		"/\[s\](.*?)\[\/s\]/is" => "<span style=\"text-decoration: line-through\">$1</span>",
		"/\[url\=(.*?)\](.*?)\[\/url\]/is" => "<a href=\"$1\">$2</a>",
		"/\[size\=(.*?)\](.*?)\[\/size\]/is" => "<span style=\"font-size:$1;\">$2</span>",
		"/\[color\=(.*?)\](.*?)\[\/color\]/is" => "<span style=\"color:$1;\">$2</span>",
		"/\[code\=(.*?)\](.*?)\[\/code\]/is" => "<pre lang=$1>$2</pre>"
		);

		$text = preg_replace (array_keys($bbcode), array_values($bbcode), $text);

		return $text;
	}
	
	public static function get ($contentID)
	{
		$sqlData = self::$db->query ("SELECT *, c.`dt` FROM `comments` as c, `users` as u WHERE `contentID`=? AND u.`userID`=c.`userID` ORDER BY c.`dt`", $contentID)->fetchAll();
		blog::highlightCode ($sqlData);

		foreach ($sqlData as $key => $value) 
		{
			$sqlData[$key]["body"] = nl2br($value["body"]);
		}

		return $sqlData;
	}

}
