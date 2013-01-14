<?php
/*
 *      portfolio.php
 *
 *      Copyright 2013 Andrei Aleksandovich Bagrintsev <andrei@bagrintsev.me>
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
class controller_portfolio extends controller_base 
{
	function index()
	{
		$this->items();
	}

	function requestModels (&$modelsNeeded)
	{
		$modelsNeeded = array("blog");
	}

	function items()
	{
		system::setParam ("page", "portfolioItems");
		blog::buildList ("portfolio");
	}

	function addItem()
	{
		system::setParam ("page", "addPortfolioItem");
		$fill = array();
		$doRedirect = false;

		if (isset ($_POST["savePost"]))
		{
			$savedItem = portfolio::addItem ($_POST);
			if ($savedItem)
				$doRedirect = true;
		}

		if ($doRedirect)
			system::redirect (system::param ("urlBase")."portfolio");
	}

	function editItem()
	{
		$id = intval ($_GET["id"]);
		$doRedirect = false;

		if (isset ($_POST["savePost"]))
		{
			blog::updatePost ($id, $_POST);
			$doRedirect = true;
		}

		if (isset ($_POST["uploadPicture"]))
		{
			$uploadedPics = blog::uploadOnePicture ($_POST["slug"]);
		}

		system::setParam ("page", "editPortfolioItem");
		$sqlData = blog::buildForm ("portfolio", "AND `id`=$id");
				
		blog::showAttachedPics ($sqlData, "portfolioPics");
    
        if ($doRedirect)
        	system::redirect (system::param ("urlBase")."items");
	}

}
