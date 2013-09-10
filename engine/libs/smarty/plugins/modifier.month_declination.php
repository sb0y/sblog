<?php
function smarty_modifier_month_declination ($dt) 
{
    require_once (SMARTY_PLUGINS_DIR . 'shared.make_timestamp.php');
    if ($dt != '' && $dt != '0000-00-00' && $dt != '0000-00-00 00:00:00' && $dt) 
    {
        $timestamp = smarty_make_timestamp ($dt);
    } else {
        return '';
    } 

    $str = smarty_modifier_date_format ($timestamp, "%B");
    $month = intval ( date ("m", $timestamp) );

    $str = ( $month == 3 || $month == 8 ) ? 
          $str . 'а' : mb_substr ($str, 0, mb_strlen ($str)-1) . 'я';  
    return $str;
} 
