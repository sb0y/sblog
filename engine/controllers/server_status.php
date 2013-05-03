<?php
/*
 *      server_status.php
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
class controller_server_status extends controller_base 
{
	function index()
	{
		system::setParam ("page", "server-status");

		$this->smarty->setCacheID ("SERVER_STATUS");
		if (!$this->smarty->isCached ("server-status.tpl", "SERVER_STATUS"))
		{
			$this->smarty->assign ("cpu", server_status::cpuInfo());
			$this->smarty->assign ("totalMemory", server_status::totalMemory());
			$this->smarty->assign ("uptime", server_status::uptime());
			$this->smarty->assign ("load", server_status::getLoad());
			$this->smarty->assign ("kernelVersion", server_status::kernel());
			
			$cpuLoad = server_status::cpuLoad();
			$memory = server_status::memory();
			
			$this->smarty->assign ("cpuLoad", $cpuLoad);
			$this->smarty->assign ("memory", $memory);
			$this->smarty->assign ("memoryScale", server_status::scaleData ( round ($memory) ) );
			$this->smarty->assign ("cpuScale", server_status::scaleData ( round ($cpuLoad) ) );
			
			$this->smarty->assign ("programms", server_status::installedSoft () );
		}
	}
	
	function requestModels (&$modelsNeeded)
	{
		$modelsNeeded = array();
	}

	function start()
	{
		
	}

}

