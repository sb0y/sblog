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
		public $controllerCall = "", $args = array(), $routePath = '/', $get = array(), $routeOptions = array(), 
			$moduleDisplayMode = false, $modelPreload = "", $controllerAction = "", $controllerArg = "";
		public $objectInheritance = array ( "args", "controllerCall", "smarty", "db", "routePath", "mail", "get", "arg" );

		function __construct()
		{
			require ( CORE_PATH . "/config.mainRoute.php" );
			$this->routeOptions = $routeOptions;
		}

		function init()
		{
			//echo "router<br />";
			system::$core = parent::init();
			core::$router = $this;
			$this->setPath ( CTRL_PATH );
			
			try {
				$this->delegate();
			} catch ( Exception $e ) {
				switch ( $e->getCode() )
				{
					case 404:
						system::redirect ("http://".system::param("siteDomain")."/search?text=".urlencode ( $this->routePath ),
							5, "Упс! Такого документа на этом сайте нет. Сейчас мы попробуем поискать что-то похожее.");
						$this->smarty->setCacheID ("REDIRECT|404");
						$this->display();
					break;
					default:
				}
			} 

			$this->handleMails();
			$this->smarty->assign ( "errors", system::$errors );
		}

		public function setPath ( $path )
		{
			if ( !is_dir ( $path ) )
			{
				throw new Exception ("Invalid controller path: '$path'");
			}
			
			$this->path = $path;
		}

		private function saveArgs ( $parts )
		{
			$this->controllerCall = array_shift ( $parts );
			$this->args = $parts;
			
			if ( $this->args )
			{
				$tmp = '';
				for ( $i=0; count ( $parts ) > $i; ++$i )
				{
					$prev = $i-1;
					
					if ( isset ( $parts [ $prev ] ) )
						$tmp = $parts [ $prev ];
					
					if ( !empty ( $tmp ) )
						$this->get [ $tmp ] = $parts[$i];
				}
			}

			if ( !count ( $this->args ) )
				$this->args[] = "index";

			if ( $this->controllerCall )
				$this->smarty->assign ( "calledController", $this->controllerCall );
			else $this->smarty->assign ( "calledController", "" );
		}

		private function getAdvancedRouting ( &$file, &$controller, &$action, &$model = "" )
		{
			$routeSocket = $controller;

			if ( isset ( $this->routeOptions [ $routeSocket ] ) )
			{
				$routePoint = $this->routeOptions [ $routeSocket ];
				$controller = $routePoint [ "controller" ];
				$model = ( ( $routePoint [ "model" ] == "defaultForThisControll" ) ? $controller : $routePoint [ "model" ] );
				//$action = $routePoint [ "action" ];

				if ( isset ( $routePoint [ "isModule" ] ) )
				{
					$this->setPath ( MODULES_PATH . "/" . $routeSocket . "/" . system::$frontController . "/controllers" );
					$this->moduleDisplayMode = true;

				} else {
					//print_r($routePoint);
					//$this->setPath ( CTRL_PATH );
				}

			} else {
				
				if ( !is_readable ( $this->path . "/$controller.php" ) )
					$controller = "";
			}

			$tmpFile = $this->path . "/$controller.php";
			
			if ( !$controller || !is_readable ( $tmpFile ) )
			{
				if ( !is_readable ( TPL_PATH . "/static/$routeSocket.tpl" ) )
				{
					throw new Exception ( "404 Not Found (file $tmpFile is NOT readable)", 404 );
				} else {
                    // staticPage() function must to know what tpl file its need
                    $this->args[0] = $routeSocket;
					$controller = "index";
					$action = "staticPage";
				}
			}

			if ( empty ( $controller ) ) $controller = $this->routeOptions["default"]["controller"];
			if ( empty ( $action ) ) $action = $this->routeOptions["default"]["action"];
			if ( empty ( $model ) ) $model = $this->routeOptions["default"]["model"];

			$this->modelPreload = $model;
			$file = $this->path . "/$controller.php";
		}

		private function getController ( &$file, &$controller, &$action )
		{
			$route = ( isset ( $_GET["act"] ) ) ? $_GET["act"] : "";

			$route = trim ( $route, '/\\' );
			$this->routePath = $route;
			
			$this->smarty->assign ( "routePath", $this->routePath );
			system::setParam ( "routePath", $route );
			
			$parts = explode ( '/', $route );

			if ( !isset ( $parts[0] ) || !$parts[0] )
			{
				$parts[0] = $this->routeOptions["default"]["controller"];
			}

			$this->controllerAction =& $action;

			$this->saveArgs ( $parts );
			
			$controller = array_shift ( $parts );
			$action = array_shift ( $parts );

			$this->getAdvancedRouting ( $file, $controller, $action, $model );

			$this->arg = $action;
		}

		private function runBeforeDisplay()
		{
			require ( ENGINE_PATH . "/function.runBeforeDisplay.php" );
			runBeforeDisplay ( system::$core );
		}

		public function delegate()
		{
			$this->getController ( $controllerFile, $controllerName, $controllerAction );
			require_once ( $controllerFile );
			$class = "controller_$controllerName";

			try {

				$controllerObject = new $class ( $this );

				if ( !is_callable ( array ( $controllerObject, $controllerAction ) ) )
				{
					$controllerAction = $this->routeOptions ["default"]["controller"];
				}

				$modelsNeeded = array();
				$controllerObject->requestModels ( $modelsNeeded );
				$modelsNeeded[] = "index";

				// load models
				$this->loadModels ( $modelsNeeded, $controllerName, $this->moduleDisplayMode );

				if ( $controllerObject->start() !== false )
					$controllerObject->$controllerAction();

			} catch ( _Exception $e ) {

				$errors = $e->criticalError();
				$this->smarty->assign ( "errors", $errors );
				$this->displayErrorPage();
				return false;
			}

			$this->runBeforeDisplay();
			$this->display();
		}
 
		function loadModels ( $models, $controllerName, $isModuleCall = false )
		{
			$arrayLength = count ( $models );
			$modulesModelPath = ENGINE_PATH . "/modules/" . $this->controllerCall . "/" . system::$frontController . "/models";

			$file = $modelName = "";

			if ( $isModuleCall )
			{
				$mdlPrefix = $modulesModelPath;
			} else {
				$mdlPrefix = MDL_PATH;
			}

			if ( $arrayLength == 1 )
			{
				$models[] = $controllerName;
				++$arrayLength ;
			}
			
			for ( $i = 0; $arrayLength > $i; ++$i )
			{
				if ( !$isModuleCall || isset ( $models[$i]["options"]["isCoreModel"] ) )
				{
					$mdlPrefix = MDL_PATH;
				} else {
					$mdlPrefix = $modulesModelPath;
				}

				if ( is_array ( $models [ $i ] ) )
				{
					$modelName = $models [ $i ] [ 0 ];
				} else {
					$modelName = $models [ $i ];
				}

				// loading model from the core controller
				if ( isset ( $models [ $i ] [ "options" ][ "isModuleModel" ] ) )
				{
					$mdlPrefix = MODULES_PATH . "/$modelName/" . system::$frontController . "/models";
				}

				$file = $mdlPrefix . "/model." . $modelName . ".php";

				if ( !file_exists ( $file ) )
				{
					//system::log ( system::WARNING, "Requested not existing model file ('$file')" );
					continue;
				}

				require_once ( $file );
				$modelName::init ( $this );
				$modelName::start();
			}
		}
	
		public function display()
		{
			if ( !system::$display )
				return;
		
			$this->smarty->assign ( "args", $this->args );
			$this->smarty->assign ( "get", $this->get );
			$page = system::param ( "page" );
			$debug = system::param ( "debug" );

			if ( $this->controllerCall && 
				 $this->controllerCall != "index" && 
				 $this->pageWasSet )
			{
				$page = $this->controllerCall;
			}

			$page .= ".tpl";

			if ( $debug && system::param ( "debugSmartyOutput" ) )
			{
				$this->smarty->assign ( "debugOut", $this->db->buildHtmlLog() );
			}

			if ( $this->moduleDisplayMode )
				$this->smarty->moduleDisplay ( $page );
			else $this->smarty->display ( $page );

			if ( $debug && !system::param ( "debugSmartyOutput" ) )
			{
				echo $this->db->buildHtmlLog();
			}
		}

		public function displayErrorPage()
		{
			$this->smarty->setCacheID ( "coreErrorPage" );
			$this->smarty->renderPage ( "coreErrorPage.tpl", "coreErrorPage" );
		}
}