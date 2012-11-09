<?php
function smarty_function_buildHttpQuery ($params, &$smarty)
{
	$arr = array_shift ($params);

	if (isset ($arr["act"]))
		unset ($arr["act"]);

	return http_build_query ($arr);
}
