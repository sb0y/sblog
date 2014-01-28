<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
define ("VKEXPORT_ROOT_PATH", dirname(__FILE__));
include (VKEXPORT_ROOT_PATH."/class.vklib.php");

$config['secret_key'] = 'R1AeSu97PgCDEpf99s7w';
$config['client_id'] = 2919631; // номер приложения
$config['user_id'] = 86253; // id текущего пользователя (не обязательно)
$config['scope'] = 'wall,photos,video'; // права доступа к методам (для генерации ток

$v = new Vk($config);
$url = $v->get_code_token();
echo $url;