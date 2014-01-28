<?php
/*
 *      index.php
 *
 *      Copyright 2010 Andrei Aleksandovich Bagrintsev <a.bagrintsev@imedia.ru>
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */
class controller_index extends controller_base 
{
    function start()
    {
        system::$display = false;
    }

	function index()
	{
        if ( isset ( $this->args[0] ) && $this->args[0] != "index" )
        {
            $code = preg_replace ( "/[^a-z0-9]/i", '', $this->args[0] );

            $res = $this->db->query ( "SELECT * FROM `redirect` WHERE `code`='?' LIMIT 1", $code );
            if ( $res->getNumRows() )
            {
                $url = $res->fetch();
                $url = $url["URL"];
                system::redirect ( $url );
            } else {
                system::redirect ( "/" );
            }
        } else {
            system::redirect ( "/" );
        }
	}

	function requestModels ( &$modelsNeeded )
	{
		$modelsNeeded = array ();
	}

}