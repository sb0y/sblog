<?php
function runBeforeDisplay ( &$core )
{
	// for all controllers and all modules
	$core->smarty->runBeforeDisplay[] = array ( "index", "::", "loadCatsMenu" );
}
