<?php
/*
 * rss.php
 * 
 * Copyright 2012 ABagrintsev <abagrintsev@topcon.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */
class controller_rss extends controller_base 
{
	function index()
	{
		$this->smarty->setCacheID ("RSS|MAINPAGE");

		if (!$this->smarty->isCached (TPL_PATH."/rss/rssMain.tpl", "RSS|MAINPAGE"))
        {
			$sqlData = rss::getLastPosts();
			$items = array_slice ($sqlData->fetchAll(), 0, 10);
			$this->smarty->assign ("items", $items);
		}
		
		echo $this->smarty->fetch (TPL_PATH."/rss/rssMain.tpl", "RSS|MAINPAGE");
	}

	function start()
	{
		
	}
	
	function requestModels (&$modelsNeeded)
	{
		$modelsNeeded = array();
	}
}
