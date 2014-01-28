<?php
function smarty_modifier_get ($array, $key)
{
	if ( isset ( $array[$key] ) )
		return $array[$key];

	return "";
}
