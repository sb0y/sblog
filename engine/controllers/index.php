<?php
/*
 *      index.php
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
class controller_index extends controller_base 
{
	function index()
	{
		if ($this->args[0] == "index")
		{
			$this->smarty->setCacheID ("MAINPAGE|offset_0");
			if (!$this->smarty->isCached ("main.tpl", "MAINPAGE|offset_0"))
			{
				$sqlData = index::mainPage();
				$this->smarty->assign ("posts", $sqlData);
			}

			$this->smarty->assign ("pagination", true);
		} else {
			system::setParam ("page", "static/".$this->args[0]);
		}
	}

	function start()
	{
		
	}
	
	function registration()
	{
		system::setParam ("page", "userRegistration");
	}
	
	function requestModels (&$modelsNeeded)
	{
		$modelsNeeded = array("blog");
	}

}

