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

		if (isset ($this->args[0]))
		{
			$method = $this->args[0];

			if (is_callable("ajax::$method"))
			{
				ajax::$method();
			}
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
