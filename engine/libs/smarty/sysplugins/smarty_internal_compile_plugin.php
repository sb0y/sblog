<?php
/**
 * Smarty Internal Plugin Compile UserAccess
 * 
 * Compiles the {useraccess} {useraccesselse} {/useraccess} tags
 * 
 * @package Smarty
 * @subpackage Compiler
 * @author Paul Peelen
 */

/**
 * Smarty Internal Plugin Compile useraccess Class
 */

class Smarty_Internal_Compile_plugin extends Smarty_Internal_CompileBase {
     /**
    * caching mode to create nocache code but no cache file
    */
    const CACHING_NOCACHE_CODE = 9999;
    /**
    * Attribute definition: Overwrites base class.
    *
    * @var array
    * @see Smarty_Internal_CompileBase
    */
    public $required_attributes = array('exec');
    /**
    * Attribute definition: Overwrites base class.
    *
    * @var array
    * @see Smarty_Internal_CompileBase
    */
    public $shorttag_order = array("_any");
    /**
    * Attribute definition: Overwrites base class.
    *
    * @var array
    * @see Smarty_Internal_CompileBase
    */
    public $option_flags = array('nocache', 'caching');
    /**
    * Attribute definition: Overwrites base class.
    *
    * @var array
    * @see Smarty_Internal_CompileBase
    */
    public $optional_attributes = array('_any');

    public function compile ( $args, $compiler, $parameter = array() )
    {
        // check and get attributes
        $_attr = $this->getAttributes ( $compiler, $args );

        // must whole block be nocache ?
        $compiler->nocache = $compiler->nocache | $compiler->tag_nocache;

        $exec = trim ( $_attr["exec"], "'\" ");
        unset ( $_attr["exec"] );

        $assign = array();
        foreach ( $_attr as $k => $v )
        {
            if ( in_array ( $k, $this->option_flags ) )
                continue;

            $k = trim ( $k, "'\" ");

            if ( is_array ( $v ) )
            {   
                $v = "is_serialized_string|" . serialize ( $v );
            } else {

                $v = trim ( $v, "'\" ");
            }

            $assign[] = "'$k'=>'$v'";
        }

        if ( $assign )
            $assignResult = ", array(" . implode ( ",", $assign ) . ")";
        else $assignResult = "";

        return "<?php plugins_loader::processPluginLoad ( \$_smarty_tpl, '$exec'$assignResult ); ?>";
    }


} 

class Smarty_Internal_Compile_pluginclose extends Smarty_Internal_CompileBase {

    public function compile ( $args, $compiler )
    {
        // check and get attributes
        //$_attr = $this->getAttributes($compiler, $args);
        // must endblock be nocache?
        if ($compiler->nocache)
        {
            $compiler->tag_nocache = true;
        }

        /*$_output = "<?php  ?>";*/

        //exit ("<pre>$_output</pre>");

        return "";
    }
} 