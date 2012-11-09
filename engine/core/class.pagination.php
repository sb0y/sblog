<?php
/*
 * class.pagination.php
 * 
 * Copyright 2012 ABagrintsev <abagrintsev@topcon.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

class pagination
{
	public $perPage = 10;
	private $page = 1, $pagesCount = 0, $allCount = 0;
	public $mysqlData = array(), $htmlRender = array();
	
	function __construct ($allCount)
	{
		$this->allCount = $allCount;
	}
	
	function readInputData ($page, $perPage=10)
	{
		$this->page = intval ($page);
		$this->perPage = $perPage;
		
		if ($this->page < 1) 
			$this->page = 1;
		
		$this->pagesCount = ceil ($this->allCount / $this->perPage);
		
		if (($this->page < 1 ) || ($this->page > $this->pagesCount))
			$this->page = 1;
	}
	
	function calculateOffset()
	{
		$this->mysqlData["start"] = ($this->perPage * ($this->page-1));
		$this->mysqlData["end"] = $this->mysqlData["start"] + $this->perPage;
		
		//if ($this->mysqlData["end"] <= $this->perPage)
		//	$this->mysqlData["end"] = $this->allCount;
				
		return $this->mysqlData;
	}
	
	function genPages()
	{
		$pn = 0;
		
		for ($x=0; $this->pagesCount > $x; $x++)
		{
			$pn = $x+1;
			if ($this->page == $pn)
			{
				$this->htmlRender[] = array ("value"=>$pn, "options"=>"current");
			} else {
				$this->htmlRender[] = array ("value"=>$pn, "options"=>false);
			}
		}
		
		return $this->htmlRender;
	}
}
