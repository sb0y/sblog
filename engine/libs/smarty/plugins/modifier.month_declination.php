<?php
function smarty_modifier_month_declination ($str) 
{
    require_once (SMARTY_PLUGINS_DIR . 'shared.make_timestamp.php');
    if ($str != '' && $str != '0000-00-00' && $str != '0000-00-00 00:00:00') {
        $timestamp = smarty_make_timestamp($str);
    } else {
        return;
    } 

    $month = date ("m", $timestamp);

    $str = ( $month == 3 || $month == 8 ) ? 
          $str . 'а' : mb_substr ($str, 0, mb_strlen ($str)-1) . 'я';  
    return $str;
} 
