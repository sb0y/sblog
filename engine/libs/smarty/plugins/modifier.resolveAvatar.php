<?php
function smarty_modifier_resolveAvatar ( $array ) 
{
	if ( isset ( $array["avatar_small"] ) && $array["avatar_small"] && $array["avatar_small"] != "NULL" )
		return ( "content/avatars/" . $array["avatar_small"] );
	else if ( isset ( $array["avatar"] ) && $array["avatar"] && $array["avatar"] != "NULL" )
		return ( "content/avatars/" . $array["avatar"] );
	else return "resources/images/no-avatar-small.png";
} 
