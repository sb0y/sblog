<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
define ("VKEXPORT_ROOT_PATH", dirname(__FILE__));
include (VKEXPORT_ROOT_PATH."/class.vklib.php");

$config['secret_key'] = '';
$config['client_id'] = 0; // номер приложения
$config['user_id'] = 0; // id текущего пользователя (не обязательно)
$config['scope'] = 'wall,photos,video'; // права доступа к методам (для генерации ток

$v = new Vk($config);
$response = $v->get_token('7d9c2d5aa12efb25fc');
var_dump($response);
