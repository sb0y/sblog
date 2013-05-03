<?php
/*
 * model.server_status.php
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

class server_status extends model_base
{
	public static function start ()
	{

	}
	
	public static function installedSoft()
	{
		$list[] = array("name"=>"Apache", "type"=>"<abbr title=\"HTTP (HyperText Transfer Protocol — «протокол передачи гипертекста») — протокол прикладного уровня передачи данных (изначально — в виде гипертекстовых документов\">HTTP</abbr> Сервер", "portageName"=>"www-servers/apache");
		$list[] = array("name"=>"MySQL", "type"=>"База данных", "portageName"=>"dev-db/mysql");
		$list[] = array("name"=>"PHP", "type"=>"Серверный язык программирования", "portageName"=>"dev-lang/php");
		$list[] = array("name"=>"GCC", "type"=>"Набор компиляторов", "portageName"=>"sys-devel/gcc");
		$list[] = array("name"=>"GlibC", "type"=>"Cтандартная библиотека языка C", "portageName"=>"sys-libs/glibc");
		$list[] = array("name"=>"Postfix", "type"=>"Сервер электронной почты", "portageName"=>"mail-mta/postfix");
		$list[] = array("name"=>"EJabberD", "type"=>"Сервер мгновенных XMPP сообщений", "portageName"=>"net-im/ejabberd");
		
		foreach ($list as $k=>$v)
		{
			$matches = array();
			exec ("eix -Ic ".$v["portageName"], $r);
			
			if ( preg_match ("/([a-zA-Z\/]+) \(([0-9.]+).*\)/", $r[0], $matches) )
			{
				$list[$k]["version"] = $matches[2];
			}
			
			unset ($matches, $r);
		}
			
		return $list;
	}
	
	public static function scaleData ($data)
	{
		$all = 100;
		$out = array();
		while ($all)
		{
			if ($data >= $all)
			{
				if (80 <= $all)
				{
					$out[] = array("active"=>true, "critical"=>true);
				} else {
					$out[] = array("active"=>true, "critical"=>false);
				}
			
			} else {
				$out[] = array("active"=>false);
			}
			
			--$all;
		}
		
		return $out;
	}

	//Free RAM Fuction
	public static function memory() 
	{
		foreach(file('/proc/meminfo') as $ri)
			$m[strtok($ri, ':')] = strtok('');

		return 100 - round(($m['MemFree'] + $m['Buffers'] + $m['Cached']) / $m['MemTotal'] * 100);
	}

	public static function totalMemory() 
	{
		$result = "";
		exec ("free -h", $a);

		if ($start = strpos($a[1], 'G')) 
		{
			$b = substr($a[1], $start-3, 3);
			$result = $b;
			unset ($a, $b);
		} else $result = "Error getting total RAM";


		return $result;
	}

	//HDD Free Space Function
	public static function hdd()
	{
		$result = "";

		exec("df -h", $a);
		
		if ($start = strpos($a[1], '%')) 
		{
			$b = substr($a[1], $start-2, 3);
			$result = $b;
			unset ($a, $b);
		} else $result = "Error getting HDD space";

		return $result;
	}

	//CPU Model Info Function
	public static function cpuInfo() 
	{
		$result = "";
		exec ("cat /proc/cpuinfo | grep 'model name'", $a);
		
		if ( $start = strpos($a[0], ':') ) 
		{
			$end = strlen($a[0])-$start;
			$b = substr($a[0], $start+1, $end);
			$result = $b;
			unset ($a, $b);
		} else $result = "Error getting cpuinfo";

		return $result;
	}

	//Load test Function
	public static function getLoad()
	{
		$result = "";

		exec("uptime", $load);
		
		if ( $start=strpos($load[0], 'age:') ) 
		{
			$end = strlen($load[0])-$start;
			$b = substr($load[0], $start+5, $end);
			$result = $b;
			unset ($a, $b);
		} else $result = "Error getting load";

		return $result;
	}

	//Uptime Function
	public static function uptime()
	{ 
		$uptime = shell_exec("cut -d. -f1 /proc/uptime");
		$days = floor($uptime/60/60/24);
		$hours = $uptime/60/60%24;
		$mins = $uptime/60%60;
		$secs = $uptime%60;
		$result = "$days days $hours hours $mins minutes and $secs seconds";

		return $result;
	}

	public static function cpuLoad ()
	{
		$cpuUsage = 0;
	    exec('ps -aux', $processes);
	    foreach($processes as $process)
	    {
	        $cols = explode (' ', preg_replace("/ +/", " ", $process) );
	        if (strpos($cols[2], '.') > -1)
	        {
	            $cpuUsage += floatval($cols[2]);
	        }
	    }

		return ($cpuUsage > 100) ? 100 : $cpuUsage;
	}


	public static function kernel ()
	{
		exec ("uname -mor", $a);
		return $a[0];
	}
}
