<?php
/*
 *      init.php
 *
 *      Copyright 2011 Andrei Aleksandovich Bagrintsev <a.bagrintsev@imedia.ru>
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

//~ if (!defined ('URL_BASE'))
//~ {
	//~ $base_url = rtrim(dirname($_SERVER['PHP_SELF']), '/\\').'/';
	//~ $base_url = "http://" . $GLOBALS["config"]["domain"] . $base_url;
	//~ define ('URL_BASE', $base_url);
//~ }

define ("ENGINE_PATH", ROOT_PATH."/engine");
define ("LIB_PATH", ENGINE_PATH."/libs");
define ("CORE_PATH", ENGINE_PATH."/core");

if (defined ("CORE_EXTERNAL_INIT"))
{
	define ("TPL_PATH", CORE_EXTERNAL_INIT."/tpl");
	define ("MDL_PATH", CORE_EXTERNAL_INIT."/engine/models");
	define ("CTRL_PATH", CORE_EXTERNAL_INIT."/engine/controllers");
	
} else {
	
	define ("TPL_PATH", ROOT_PATH."/tpl");
	define ("MDL_PATH", ENGINE_PATH."/models");
	define ("CTRL_PATH", ENGINE_PATH."/controllers");
}

define ("TIME_COOKIE", (time()+(86400*365)) );
define ("CONTENT_PATH", ROOT_PATH."/content" );

define ("TMP_PATH", ROOT_PATH."/tmp" );


#error_reporting(E_ALL); ini_set('display_errors', 1);

date_default_timezone_set ("Europe/Moscow");
setlocale (LC_ALL, "ru_RU.UTF-8");

session_start();

function engine_autoload ($class_name)
{	
	if ($class_name == "Smarty")
		return false;
	
		
	$filename = 'class.'.$class_name.'.php';

	$paths = array(
	LIB_PATH.'/'.$filename,
	LIB_PATH.'/'.$class_name.'/'.$filename,
	MDL_PATH."/model.$class_name.php",
	CORE_PATH.'/'.$filename);

	foreach ($paths as $p)
	{
		if (file_exists ($p))
		{
			$file = $p;
			break;
		}
	}
	if (!isset($file))
	{
		return false;
	}

	include ($file);
}

spl_autoload_register ("engine_autoload");

require (LIB_PATH."/smarty/Smarty.class.php");
require (CORE_PATH."/class.system.php");
