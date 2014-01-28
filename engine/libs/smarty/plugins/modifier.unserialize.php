<?php

function smarty_modifier_unserialize ( $string, $key = "" ) 
{
	if ( !$string )
		return "";

	if ( $key )
	{
		$array = unserialize ( $string );

		if ( isset ( $array[$key] ) )
			return $array[$key];
		else return "";
	}

	return unserialize ( $string );
}