<?php
function smarty_function_checkFormError ( $params, &$smarty )
{
	if ( !isset ( $params["field"] ) && !$params["field"] )
		return "";

	$vars = system::$errors;
	$probe = $params["field"];
	$prefix = "";
	$txt = "error";

	if ( isset ( $params["class"] ) && $params["class"] )
		$txt = "class=\"error\"";

	if ( isset ( $params["space"] ) && $params["space"] )
		$prefix = " ";

	if ( isset ( $vars [ $probe ] ) && $vars [ $probe ] )
	{
		return $prefix . $txt;
	}

	return "";
}
