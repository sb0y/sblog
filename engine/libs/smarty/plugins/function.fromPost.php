<?php
function smarty_function_fromPost ($params, &$smarty)
{
	$key = array_shift ($params);
	if (!$array = array_shift ($params))
	{
		$array = $_POST;	
	}
	
	if (isset ($array[$key]))
		return $array[$key];
		
	return '';
}
