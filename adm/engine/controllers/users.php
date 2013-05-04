<?php
/*
 *      users.php
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
class controller_users extends controller_base 
{
	function index()
	{
		system::setParam ("page", "users");
		blog::buildList ("users");
	}
	
	function start()
	{
		
	}

	function edit()
	{
		if (empty ($_GET["userID"]))
			return false;

		$doRedirect = false;

		system::setParam ("page", "userEdit");

		if (isset ($_POST["savePost"]))
		{
			$data = $_POST;
			unset ($data["savePost"]);

			if (!empty ($data["password"]))
			{
				$data["password"] = md5(md5($data["password"]));
			} else unset ($data["password"]);

			$this->db->updateTable ("users", $data, "userID", $_GET["userID"]);
			$doRedirect = true;
		}

		blog::buildForm ("users", "AND `userID`=".$this->args[0]);

		if ($doRedirect)
			system::redirect ("/adm/users");
	}

	function add()
	{
		system::setParam ("page", "addUser");

		$doRedirect = false;

		if (isset ($_POST["savePost"]))
		{
			$data = $_POST;
			unset ($data["savePost"]);

			$this->db->query ("INSERT INTO `users` SET `nick`='?', `email`='?', `password`=md5(md5('?')), 
				`source`='?', `profileURL`='?'", 
				$data["nick"], $data["email"], $data["password"], $data["source"], $data["profileURL"]);

			$doRedirect = true;
		}

		if ($doRedirect)
			system::redirect ("/adm/users");
	}
	
	function requestModels (&$modelsNeeded)
	{
		$modelsNeeded = array("blog");
	}

}
