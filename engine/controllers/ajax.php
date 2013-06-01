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
		
	}

	function getTpl()
	{
		system::$display = false;
		$tplName = $this->get["getTpl"];

		$file = TPL_PATH."/ajax/$tplName.tpl";
		$tplContent = "File not found";

		if (isset ($_GET["fromUrl"]) && $_GET["fromUrl"])
		{
			$this->smarty->assign ("routePath", urldecode ($_GET["fromUrl"]));
		}
		
		if (file_exists ($file))
			$tplContent = $this->smarty->fetch ($file);
			
		echo $tplContent;
	}

	function requestModels (&$modelsNeeded)
	{
		$modelsNeeded = array();
	}

}

