<?php
/*
 *      class.controller_base.php
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

abstract class controller_base
{
	protected $smarty, $db, $controllerCall = array(), $args = array(), $get = array();

	function __construct (&$core)
	{
		$errors = $core->checkPermissions();

		if ( $errors )
		{
			throw new _Exception ( $errors );
		}

		foreach ($core->objectInheritance as $property)
		{
			if (property_exists ($core, $property))
			{
				$this->$property =& $core->$property;
			}
		}
	}

	private function runPreloadHandlers()
	{
		
	}

	public function search()
	{
		$sp = "";

		if ( isset ( $_GET["text"] ) && $_GET["text"] )
			$sp = "?text=" . urlencode ( $_GET["text"] );

		return system::redirect ( "/search$sp" );
	}

	abstract function index();
	abstract function requestModels ( &$modelsNeeded );
	abstract function start();
}
