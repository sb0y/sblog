<?php
error_reporting(E_ALL); ini_set('display_errors', 1);

define ("ROOT_PATH_ADM", dirname(__FILE__));
define ("ROOT_PATH", "..");
define ("CORE_EXTERNAL_INIT", ROOT_PATH_ADM);

require (ROOT_PATH."/engine/core/init.php");

system::chmod (system::AUTH_REQUIRED|system::ADMIN_ONLY);
system::init ("backend");
