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
		system::$display = false;

		if ( isset ( $this->args[0] ) )
		{
			$method = $this->args[0];

			if (is_callable("ajax::$method"))
			{
				ajax::$method();
			}
		}
	}

	function saveDraft()
	{
		$data = drafts::processDefaultData();

		if ( drafts::save ( $data["contentID"], $_SESSION["user"]["userID"], $data["type"] ) )
		{
			echo "Ok";
		} else {
			echo "Error";
		}
	}

	function deleteDraft()
	{
		//$data = drafts::processDefaultData();
		$id = 0;
		$op = "one";
		$type = "news";

		if ( isset ( $_POST["id"] ) && $_POST["id"] )
			$id = intval ( $_POST["id"] );

		if ( isset ( $_GET["op"] ) && $_GET["op"] )
			$op = preg_replace ( "/[^a-z0-9]/i", '', $_GET["op"] );

		if ( isset ( $_POST["type"] ) && $_POST["type"] )
			$type = preg_replace ( "/[^a-z0-9]/i", '', $_POST["type"] );

		drafts::setDataType ( $type );

		switch ( $op )
		{
			case "one":
				if ( $id && drafts::delete ( $id ) )
				{
					echo "Ok";
				} else {
					echo "Error";
				}
			break;
			case "all":
				if ( drafts::deleteForUser ( intval ( $_SESSION["user"]["userID"] ) ) )
				{
					echo "Ok";
				} else {
					echo "Error";
				}
			break;
			case "article":
				if ( drafts::deleteByContentID ( $id ) )
				{
					echo "Ok";
				} else {
					echo "Error";
				}
			break;
		}
	}

	function getTpl()
	{
		$tplName = $this->get["getTpl"];

		if ( $tplName == "drafts" )
		{
			$contentID = 0;

			if ( isset ( $_POST["contentID"] ) && $_POST["contentID"] )
				$contentID = intval ( $_POST["contentID"] );

			$this->smarty->assign ( "lists", drafts::getDraftsLists ( $contentID ) );
		}

		$file = TPL_PATH . "/ajax/$tplName.tpl";
		$tplContent = "File not found";
		
		if ( file_exists ( $file ) )
			$tplContent = $this->smarty->fetch ($file);
			
		echo $tplContent;
	}

	function requestModels (&$modelsNeeded)
	{
		$modelsNeeded = array();
	}
	
	function start()
	{
		system::$display = false;
	}
}
