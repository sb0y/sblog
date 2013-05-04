<?php
/*
 *      class.router.php
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

class router extends core {

		private $path; 
		public $controllerCall = "", $args = array(), $routePath = '/', $get = array();
		public $objectInheritance = array ("args", "controllerCall", "smarty", "db", "routePath", "mail", "get");

		function __construct()
		{

		}

		function init()
		{
			//echo "router<br />";
			parent::init();

			$this->setPath (CTRL_PATH);
			
			try {
				$this->delegate();
			} catch (Exception $e) {
				switch ($e->getCode())
				{
					case 404:
						system::redirect ("http://".system::param("siteDomain")."/blog/search?text=".urlencode($this->routePath),
							5, "Упс! Такого документа на этом сайте нет. Сейчас мы попробуем поискать что-то похожее.");
						$this->smarty->setCacheID ("REDIRECT|404");
						system::setParam ("page","redirect");
						$this->loadModels (array("index"), "index");
						index::loadCatsMenu();
						$this->display();
					break;
				}
			}

			$this->handleMails();
			$this->smarty->assign ("errors", system::$errors);
		}

		function setPath ($path)
		{
			if (!is_dir($path))
			{
				throw new Exception ("Invalid controller path: `$path`");
			}
			
			$this->path = $path;
		}

		private function saveArgs ($parts)
		{
			$this->controllerCall = array_shift ($parts);
			//if ($this->controllerCall=="index") $this->controllerCall = "";
			$this->args = $parts;
			
			if ($this->args)
			{
				$tmp = '';
				for ($i=0; count ($parts) > $i; ++$i)
				{
					$prev = $i-1;
					
					if (isset ($parts[$prev]))
						$tmp = $parts[$prev];
					
					if (!empty ($tmp))
						$this->get[$tmp] = $parts[$i];
				}
			}
		}

		private function getController (&$file, &$controller, &$action, &$args)
		{
			$route = (isset ($_GET["act"])) ? $_GET["act"] : "";

			$route = trim ($route, '/\\');
			$this->routePath .= $route;
			
			$this->smarty->assign ("routePath", $this->routePath);
			system::setParam ("routePath", $route);
			
			$parts = explode ('/', $route);	
			$this->saveArgs ($parts);
			
			$controller = array_shift ($parts);

			if (empty($controller)) $controller = "index";

			$action = array_shift ($parts);
			
			if (empty($action)) $action = "index";
			
			$file = $this->path."/$controller.php";

			$this->args[] = $controller;

			if (!is_readable ($file))
			{
				
				if (!is_readable (TPL_PATH."/static/$controller.tpl"))
				{
					throw new Exception ("404 Not Found (file $file is NOT readable)", 404);
				} else {
					$this->smarty->setCacheID ("STATIC|".$controller);
					$controller = "index";
					$file = $this->path."/index.php";
				}
				
			}

			$args = $parts;
		}

		function delegate()
		{
			$this->getController ($controllerFile, $controllerName, $controllerAction, $controllerArgs);

			require ($controllerFile);
			
			$class = "controller_$controllerName";

			try
			{
				$controllerObject = new $class ($this);
				
				if (!is_callable (array ($controllerObject, $controllerAction)))
				{
					$param = $controllerAction;
					$controllerAction = "index";
				}

				$controllerObject->requestModels ($modelsNeeded);
				$controllerObject->start();
				$modelsNeeded[] = "index";
				$this->loadModels ($modelsNeeded, $controllerName);

				$controllerObject->$controllerAction();

			} catch (_Exception $e) {
				$errors = $e->criticalError();
				$this->smarty->assign ("errors", $errors);
			}

			$this->display();
		}
 
		function loadModels ($models, $controllerName)
		{
			$arrayLength = count ($models);
			$file = "";
			
			if ($arrayLength === 1)
			{
				array_push ($models, $controllerName);
				++$arrayLength ;
			}
			
			for ($i=0; $arrayLength > $i; ++$i)
			{
				$file = MDL_PATH."/model.".$models[$i].".php";
				
				if (!file_exists ($file))
				{
					system::log (system::WARNING, "Requested not existing model file ('$file')");
					continue;
				}
				
				require_once ($file);
				$models[$i]::init ($this);
				$models[$i]::start();
			}
		}
	
		function checkComponent()
		{
			
		}
	
		function display()
		{
			if (!system::$display)
				return;
			
			$this->smarty->assign ("args", $this->args);
			$this->smarty->assign ("get", $this->get);

			$page = system::param ("page");
			$page .= ".tpl";
			$this->smarty->display ($page);
		}
}
