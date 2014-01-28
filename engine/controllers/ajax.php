<?php
/*
 *      ajax.php
 *
 *      Copyright 2010 Andrei Aleksandovich Bagrintsev <a.bagrintsev@imedia.ru>
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */
class controller_ajax extends controller_base 
{
	function index()
	{	

	}

	function start()
	{
		system::$display = false;
	}

	function getTpl()
	{
		$tplName = $this->get["getTpl"];

		$file = TPL_PATH."/ajax/$tplName.tpl";
		$tplContent = "File not found";

		if ( isset ( $_GET["fromUrl"] ) && $_GET["fromUrl"] )
		{
			$this->smarty->assign ("routePath", urldecode ($_GET["fromUrl"]));
		}
		
		if ( file_exists ( $file ) )
			$tplContent = $this->smarty->fetch ( $file );
			
		echo $tplContent;
	}

	function commentsCount()
	{
		if ( !isset ( $_POST ) || !$_POST )
			return;

		$result = array();

		foreach ( $_POST as $v )
		{
			$v = trim ( core::generateSlug ( $v ) );
			$res = $this->db->query ( "SELECT COUNT(*) FROM `comments` as co,`content` as c WHERE co.`contentID`=c.`contentID` 
				AND c.`slug`='?'", $v );
			
			$tmp = $res->fetch();

			$result[$v] = array_shift ( $tmp );
		}

		if ( !$result )
			return;

		echo json_encode ( $result );
	}

	function favContent()
	{
		if ( ( !isset ( $_SESSION["user"] ) || !$_SESSION["user"] ) )
		{
			echo "Login Error";
			return;
		}

		if ( ( !isset ( $_POST ) || !$_POST ) || ( !isset ( $_POST["contentID"] ) || !$_POST["contentID"] ) )
		{
			echo "Error";
			return;
		}

		$userID = intval ( $_SESSION["user"]["userID"] );
		$contentID = intval ( $_POST["contentID"] );

		if ( isset ( $_POST["isActive"] ) && $_POST["isActive"] == "true" )
		{
			if ( $this->db->query ( "DELETE FROM `favorites` WHERE `contentID`=? AND `userID`=?", $contentID, $userID ) )
				echo "Ok";

		} else if ( isset ( $_POST["isActive"] ) && $_POST["isActive"] == "false" ) {

			$contentRes = $this->db->query ( "SELECT `slug`, `type` FROM `content` WHERE `contentID`=?", $contentID );

			if ( !$contentRes->getNumRows() )
			{
				echo "Post not found";
				return;
			}

			$content = $contentRes->fetch();

			if ( $this->db->query ( "INSERT INTO `favorites` (`userID`,`contentID`,`slug`,`author`,`type`) VALUES (?,?,'?','?','?')",
				$userID, $contentID, $content["slug"], $_SESSION["user"]["nick"], $content["type"] ) )
			{
				$this->smarty->clearCache ( null, "USER|USERFAVS|userfav_$userID" );
				echo "Ok";
			} else {
				echo "DB Error";
			}
		}
	}


	function changeRate()
	{
		if ( ( !isset ( $_SESSION["user"] ) || !$_SESSION["user"] ) )
		{
			echo "Login Error";
			return;
		}

		if ( ( !isset ( $_POST ) || !$_POST ) || ( !isset ( $_POST["commentID"] ) || !$_POST["commentID"] ) )
		{
			echo "Error";
			return;
		}

		$userID = intval ( $_SESSION["user"]["userID"] );
		$commentID = intval ( $_POST["commentID"] );
	
		if ( isset ( $_POST["type"] ) && $_POST["type"] ) 
		{
			$type = preg_replace ( "/[^a-z]/i", '', $_POST["type"] );

			$commentRes = $this->db->query ( "SELECT *,".
				"(SELECT COUNT(*) FROM `comment_change` WHERE `voteType`='+' AND `userID`=$userID AND `commentID`=$commentID) as p,".
				"(SELECT COUNT(*) FROM `comment_change` WHERE `voteType`='-' AND `userID`=$userID AND `commentID`=$commentID) as m ".
				"FROM `comments` as c WHERE c.`commentID`=? LIMIT 1", $commentID );
			
			if ( $type && $commentRes->getNumRows() )
			{
				$cacheID = "NEWS";
				$comment = $commentRes->fetch();

				if ( $comment["userID"] == $userID )
				{
					echo "Vote for himself disallowed";
					return;
				}

				$post = $this->db->query("SELECT `contentID`,`slug`,DATE_FORMAT (`dt`,'%d-%m-%Y') as `dt` FROM `content` WHERE `contentID`=?", $comment['contentID'])->fetch();
				
				if ( $post )
				{
					$cacheID = "{$post["dt"]}_newsdate|{$post["slug"]}";
				}

				$plusUpdateMode = $minusUpdateMode = false;

				switch ( $type ) 
				{
					case 'up':
						$rate = $comment["rate"]+1;

						// свой минус можно не только удалить, но и влепить за место него новый плюс
						if ( $comment["m"] == 1 )
						{
							$this->db->query ( "DELETE FROM `comment_change` WHERE `voteType`='-' AND `userID`=$userID AND `commentID`=$commentID" );							
							$minusUpdateMode = true;
						}

						if ( $comment["p"] == 1 )
						{
							echo "Vote twice";
							return;
						}

						if ( !$minusUpdateMode )
						{
							$this->db->query ( "INSERT INTO `comment_change` (`userID`,`dt`,`commentID`,`contentID`,`voteType`) VALUES (?,NOW(),?,?,'?')",
							$userID, $commentID, $post["contentID"], "+" );
						}

						$this->db->query ( "UPDATE `comments` SET `rate` = ? WHERE `commentID` = ? ",$rate, $commentID);
						$this->smarty->clearCache ( null, $cacheID );
						echo $rate;
						return;
					break;	
					case 'down':
						$rate = $comment['rate']-1;

						// свой плюс можно не только удалить, но и влепить за место него новый минус
						if ( $comment["p"] == 1 )
						{
							$this->db->query ( "DELETE FROM `comment_change` WHERE `voteType`='+' AND `userID`=$userID AND `commentID`=$commentID" );							
							$plusUpdateMode = true;
						}

						if ( $comment["m"] == 1 ) 
						{
							echo "Vote twice";
							return;
						}

						if ( !$plusUpdateMode )
						{
							$this->db->query ( "INSERT INTO `comment_change` (`userID`,`dt`,`commentID`,`contentID`,`voteType`) VALUES (?,NOW(),?,?,'?')",
							$userID, $commentID, $post["contentID"], "-" );
						}

						$this->smarty->clearCache ( null, $cacheID );
						$this->db->query ( "UPDATE `comments` SET `rate` = ? WHERE `commentID` = ?", $rate, $commentID );
						echo $rate;
						return;
					break;	
					default:
						echo 'Error type';
						return;
					break;
				}
			}
		}
		else
		{
			echo 'Error global type';
			return;
		}
	
	}

	function userList()
	{
		if ( !isset ( $_SESSION["user"] ) && !$_SESSION["user"] )
		{
			echo json_encode ( array ( "Error"=>"Must be authorized." ) );
			return;
		}

		$possiblyNick = "";
		$rarr = array();
		$res = null;

		if ( isset ( $_GET["nick"] ) && $_GET["nick"] )
		{
			$possiblyNick = urldecode ( $_GET["nick"] );
			$possiblyNick = mb_ereg_replace ( "/[^a-zа-яё0-9.,\-+=)(\*\?\^\%\\$\#\@\!\~\`\[\]\|\>\<\&\\\/\}\{ ]/i", 
				'', $possiblyNick );
		
			$res = $this->db->query ( "SELECT * FROM `users` WHERE `nick` LIKE '%?%'", $possiblyNick );
		} else if ( isset ( $_GET["userID"] ) && $_GET["userID"] ) {

			$possiblyNick = intval ( $_GET["userID"] );
			$res = $this->db->query ( "SELECT * FROM `users` WHERE `userID`=?", $possiblyNick );
		}

		if ( $res && $res->getNumRows() )
			$rarr = $res->fetchAll();
		else $rarr["error"] = "Empty";

		echo json_encode ( $rarr );
	}

	function deleteMailMessages()
	{
		if ( !is_array ( $_POST ) || !$_POST )
			return;

		$readed = 0;
		$keys = array_keys ( $_POST );
		$readedArr = array_count_values ( $_POST );

		if ( isset ( $readedArr["1"] ) && $readedArr["1"] )
			$readed = intval ( $readedArr["1"] );

		if ( user::deleteMailMessages ( $keys, $readed ) )
		{
			$this->smarty->clearCache ( null, "USER|USERMAIL|usermail_" . intval ( $_SESSION["user"]["userID"] ) );
			echo "Ok";
		} else {

			echo "Error";
		}
	}

	function markMails()
	{
		if ( !isset ( $_POST ) || !$_POST || !isset ( $_SESSION["user"] ) )
			return false;

		if ( user::markMails ( $_POST ) )
		{
			$this->smarty->clearCache ( null, "USER|USERMAIL|usermail_" . intval ( $_SESSION["user"]["userID"] ) );
			echo "Ok";
		} else {
			echo "Error";
		}
	}

	function getMailHistory()
	{
		$receiverID = 0;
		$senderID = 0;
		$arr = array();

		if ( isset ( $_GET["senderID"] ) && $_GET["senderID"] )
			$senderID = intval ( $_GET["senderID"] );
		else $arr["error"] = "Unknown senderID";

		if ( isset ( $_GET["receiverID"] ) && $_GET["receiverID"] )
			$receiverID = intval ( $_GET["receiverID"] );
		else $arr["error"] = "Unknown receiverID";

		if ( !isset ( $arr["error"] ) )
			$arr = user::mailUserHistory ( $receiverID, $senderID )->fetchAll();

		if ( empty ( $arr ) )
			$arr["error"] = "No data";

		if ( !$arr )
			return false;

		echo json_encode ( $arr );
	}

	function friendMngr()
	{
		if ( !isset ( $_SESSION["user"]["userID"] ) ||
			 !isset ( $_GET["userID"] ) ||
			 !$_SESSION["user"]["userID"] || 
			 !$_GET["userID"] ||
			 !isset ( $_GET["action"] ) )
		{
			return false;
		}

		$u1 = intval ( $_SESSION["user"]["userID"] );
		$u2 = intval ( $_GET["userID"] );

		if ( $u1 == $u2 )
			return false;
	
		switch ( $_GET["action"] )
		{
			case "add":
				if ( $this->db->query ( "INSERT INTO `friends` (`u1`,`u2`) VALUES (?,?)", $u1, $u2 ) )
				{
					$this->smarty->clearCache ( null, "USERPROFILE|USERPANEL|user_" . $u2 );
					$user = $this->db->query ( "SELECT `nick` FROM `users` WHERE `userID`=? LIMIT 1", $u2 )->fetch();
					echo "Ok|" . $user["nick"];
				} else {
					echo "Error";
				}
			break;

			case "remove":
				if ( $this->db->query ( "DELETE FROM `friends` WHERE `u1`=? AND `u2`=?", $u1, $u2 ) )
				{
					$this->smarty->clearCache ( null, "USERPROFILE|USERPANEL|user_" . $u2 );
					echo "Ok";
				} else {
					echo "Error";
				}
			break;
		}
	}

	function baloonRatingInfo()
	{
		$arr = array();

		if ( !isset ( $_GET["commentID"] ) && !$_GET["commentID"] )
			$arr["error"] = "commentID required";

		if ( !isset ( $arr["error"] ) )
		{
			$commentID = intval ( $_GET["commentID"] );

			$res = $this->db->query ( "SELECT `avatar_small`,`nick`,u.`userID`,`voteType` FROM `comment_change` as cc, `users` as u WHERE ".
				"cc.`userID`=u.`userID` AND cc.`commentID`=?", $commentID );

			if ( $res->getNumRows() )
			{
				$arr = $res->fetchAll();
			} else {
				$arr["warning"] = "Nothing found";
			}
		}

		echo json_encode ( $arr );
	}

	function requestModels ( &$modelsNeeded )
	{
		$modelsNeeded = array();
	}
}

