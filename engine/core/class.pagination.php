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
	public $perPage = 10, $range = 10;
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
		
		$this->pagesCount = ceil ($this->allCount / $this->perPage);

		if ($this->page > $this->allCount) 
		{
		   $this->page = $this->allCount;
		}

		if ($this->page < 1) 
		{
		   $this->page = 1;
		}
	}
	
	function calculateOffset()
	{
		$this->mysqlData["start"] = (($this->page-1) * $this->perPage);
		$this->mysqlData["end"] = $this->perPage;
		
		return $this->mysqlData;
	}
	
	function genPages()
	{
		for ($x = ($this->page - $this->range); 
			 $x < (($this->page + $this->range) + 1);
			 ++$x) 
		{
			if (($x > 0) && ($x <= $this->pagesCount)) 
			{
				if ($x == $this->page) 
				{
					$this->htmlRender[] = array ("value"=>$x, "options"=>"current");
				} else {
					$this->htmlRender[] = array ("value"=>$x, "options"=>false);
				} 
			}
		}
		
		return $this->htmlRender;
	}
}
