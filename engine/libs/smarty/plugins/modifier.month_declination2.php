<?php
function smarty_modifier_month_declination ($str) 
{
    //function month_declination_ru ( $long_month_name, $month  ) {
   $month = strtotime ($str);
   echo $month;
   $str = ( $month == 3 || $month == 8 ) ? 
          $str . 'а' : mb_substr ($str, 0, mb_strlen ($str)-1) . 'я';  
   return $str;
} 
