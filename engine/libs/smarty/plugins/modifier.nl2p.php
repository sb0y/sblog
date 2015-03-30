<?php
function smarty_modifier_nl2p ( $string, $nl2br = false )
{
    $string = trim ( $string, "\n\r" );

    // Normalise new lines
    //$string = str_replace ( array ( "\r\n", "\r" ), "\n", $string );
    
    // Extract paragraphs
    $parts = preg_split ( "/\\n\\n/", $string );

    // Put them back together again
    $string = '';
    
    foreach ( $parts as $part ) 
    {
        $part = trim ( $part );

        if ( $part ) 
        {
            $string .= "<p>$part</p>";
        }
    }
    
    return $string;
}