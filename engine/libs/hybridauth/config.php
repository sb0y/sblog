<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

return 
	array(
		"base_url" => "http://bagrintsev.me/engine/libs/hybridauth/", 

		"providers" => array ( 
			// openid providers
			"OpenID" => array (
				"enabled" => true,
			),
			
			"Vkontakte" => array (
				"enabled" => true,
				"keys"	  => array ( "id" => "000000", "secret" => "secret" ),

			),

			"Yahoo" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" ),
			),

			"AOL"  => array ( 
				"enabled" => true 
			),

			"Google" => array ( 
				"enabled" => true,
				 "keys"    => array ( "id" => "000000.apps.googleusercontent.com", "secret" => "secret" ), 
		         "scope"           => "https://www.googleapis.com/auth/userinfo.profile " .
		                               "https://www.googleapis.com/auth/userinfo.email", // optional
		         "access_type"     => "offline", // optional
		         "approval_prompt" => "force" // optional
			),

			"Facebook" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "00000", "secret" => "secret" ),
                "scope"   => "email, user_about_me"
			),

			"Twitter" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "secret", "secret" => "secret" ) 
			),

			// windows live
			"Live" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" ) 
			),

			"MySpace" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" ) 
			),

			"LinkedIn" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" ) 
			),

			"Foursquare" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" ) 
			),
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => false,

		"debug_file" => "",
	);
