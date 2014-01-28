<?php
function smarty_function_fromPost ($params, &$smarty)
{
	$key = array_shift ( $params );
	if ( !$array = array_shift ( $params ) )
	{
		$array = $_POST;	
	}
	
	if ( $key == "dt" )
	{
		$tms = strtotime ( $array["dt"] );
		return date ("d-m-Y", $tms);
	}

	if (isset ($array[$key]))
		return $array[$key];
		
	return '';
}
