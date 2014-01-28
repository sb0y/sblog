<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
define ("VKEXPORT_ROOT_PATH", dirname(__FILE__));
include (VKEXPORT_ROOT_PATH."/class.webexport.php");

$obj = new vkExport (vkExport::mode_server);
$obj->init();