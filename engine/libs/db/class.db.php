<?php
class db extends MySQLi
{
	public $runAfterFetchAll = array(), $runAfterFetch = array();
	public $database = "", $connect_host = "", $user = "", $password = "", $codepage = "utf8";
	private $inited = false, $log = array(), $initTime = 0, $lastQueryTime = 0;
	const ERROR = 0;
	const SUCCESS = 1;
	const INFO = 2;

	function __construct ($configArray)
	{
		$this->conf ($configArray);
		
		if (!extension_loaded ("mysqli"))
			throw new exception("mysqliIsNotFound", "PHP can't load MySQLi extension");
	}

	function __destruct()
	{
		if ($this->inited)
			$this->close();
	}
	
	private function get_mt()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	function conf ($configArray)
	{
		if ($configArray)
   		{
	   		foreach ($configArray as $k=>$v)
	   		{
	   			if (isset ($this->$k) && !is_null ($this->$k))
	   			{
	   				$this->$k = $v;
	   			}
	   		}
	   	}
	   	
	   	//mysqli_report (MYSQLI_REPORT_ALL);
	}

	function hex2string ($str)
	{
		return str_replace ("%3F", "?", $str);
	}

	function string2hex ($str)
	{
		return str_replace ("?", "%3F", $str);
	}

	function init ($configArray=null)
	{
		if ($this->inited)
			return;
		
		if ($configArray)
			$this->conf ($configArray);

		parent::__construct ($this->connect_host, $this->user, $this->password, $this->database);
		parent::set_charset ($this->codepage);
		
		if (mysqli_connect_errno())
		{
			printf("Can't connect to MySQL server. Error Message: %s\n", mysqli_connect_error());
			throw new exception(mysqli_error($this), mysqli_errno($this));
		}

		$this->initTime = $this->get_mt();
		$this->log (db::INFO, array ("message"=>"Starting generic Database layer with MySQLi extension. Version 1.0."));

		$this->inited = true;
	}

	public function log ($queryType, $data=false)
	{		
		$data["type"] = $queryType;
		$data["time"] = $this->get_mt();
		$data["rowsNum"] = $this->field_count;
		$data["realQueryTime"] = $this->lastQueryTime;
		
		if (isset ($this->error))
			$data["errorMessage"] = $this->error;
		
		array_push ($this->log, $data);
	}

	private function replaceVars (&$query, $args)
	{
		$tmp = explode ('?', $query);
			
		foreach ($args as $k=>$v)
		{	
			$v = $this->string2hex ($v);
			$this->escape_string ($v);
			$tmp[$k] .= $this->string2hex ($v);	
		}
				
		$query = implode ('', $tmp);
	}

	public function query (/*...*/)
	{
		$starttime = microtime (true);
		
		// init system if needed
		$this->init();
		$args = func_get_args();
		$query = array_shift ($args);
		
		if (count ($args) >= 1)
		{
			foreach ($args as $k=>$v)
			{
				$args[$k] = $this->hex2string ($args[$k]);
			}
							
			$this->replaceVars ($query, $args);

		}

		try 
		{
			$this->real_query ($query);

			if (mysqli_error ($this))
			{
				throw new exception (mysqli_error($this), mysqli_errno($this));
			}

			$endtime = microtime (true);
			$this->lastQueryTime = $endtime - $starttime;

			$this->log (db::SUCCESS, array("query"=>$query));
			$res = new db_result ($this);
			$res->runAfterFetchAll = $this->runAfterFetchAll;
			$res->runAfterFetch = $this->runAfterFetch;
		} catch (Exception $e) {
			echo "<b>" . $e->getMessage() . "</b><br />\r\n";
			var_dump ($e->getTrace());
			$this->log (db::ERROR, array("query"=>$query, "message"=>$e->getTrace()));
		}
		
		return $res;
	}

	public function insert_id()
	{
		return mysqli_insert_id ($this);
	}
	
	public function escape_string (&$string)
	{
		$this->init();
		$string = $this->real_escape_string ($string);
		return $string;
	}
	
	public function updateTable ($table, $data=array(), $idName, $id)
	{
		$tmp = array();
		
		if (!empty ($data))
		{
			foreach ($data as $k=>$v)
			{
				$tmp[] = "`$k`='".$this->escape_string ($v)."'";
			}
			
			if (!empty ($tmp))
			{
				$this->query ("UPDATE `$table` SET ".implode (", ", $tmp)." WHERE `$idName`=?", $id);
			}
		}
	}

	public function buildHtmlLog ($show_types = array (db::INFO, db::ERROR, db::SUCCESS))
	{
		$out = '<table cellpadding="3" cellspacing="0" border="1" width="100%">';
		$out .= '<tr><th align="center">#</th><th align="center">Type</th><th>Message Time</th><th>Query Time</th><th>Record</th>';
		
		foreach ($this->log as $n=>$rec)
		{
			if (!in_array($rec["type"], $show_types))
				continue;
						
			$out .= '<tr><td align="center">'.($n+1).'</td>';
						
			switch ($rec["type"])
			{
				case db::INFO:
					$out .= '<td align="center" style="color:navy">Info</td>';
					$out .= '<td>+ '.round($rec["time"]-$this->initTime,4).' sec</td>';
					$out .= '<td><b>Time</b> : <code>'.round($rec["realQueryTime"],4).'</code> sec</td>';
					$out .= '<td><code>'.$rec["message"].'</code></td>';
					
				break;
				case db::ERROR:
					$out .= '<td align="center" style="color:red">Error</td>';
					$out .= '<td><b>Time</b> : <code>'.round($rec["realQueryTime"],4).'</code> sec</td>';
					$out .= '<td>+ '.round($rec["time"]-$this->initTime,4).' sec';
		
					if (isset ($rec["errorMessage"]))
					{
						$out .= '<b>Error</b> : <code style="color:red;">'.$rec["errorMessage"].'</code>';
					}
					
					$out .= '</td>';
				break;
				case db::SUCCESS:
					$out .= '<td align="center" style="color:green">Query</td>';
					$out .= '<td>+ '.round($rec["time"]-$this->initTime,4).' sec</td>';
					$out .= '<td><b>Time</b> : <code>'.round($rec["realQueryTime"],4).'</code> sec</td>';
					
					// Build query info
					$out .= '<td><b>Query</b> : <code>'.(isset ($rec["query"])?$rec["query"]:'').'</code><br />';
					$out .= '<b>Result</b> : <b><code style="font-weight:bold;font-size:large;">'.$rec["rowsNum"].'</code></b> rows<br /></td>';
				break;
			}
				
			$out .= '</tr>';
		}
		
		$out .= '</table>';
	
		
		return $out;
	}
}

class db_result extends mysqli_result
{
	public $runAfterFetchAll = array();
	
    public function __call ($funcName, $args)
    {
		$navArray =& $this->$funcName;
		$navArray2 = array_shift ($navArray);
		
		foreach ($args as $k=>$v)
				$args[$k] = &$v;

		$runFunc = $navArray2[0];
		if (count ($navArray2) > 1)
		{
			$runFunc .= "::".$navArray2[1];
		}

		return call_user_func_array ($runFunc, $args);
 
    }
	
	public function fetch()
    {		
		$array = $this->fetch_assoc();

        return $array;
    }
        
    public function fetchAll ($limit=false)
    {
		$rows = array();
		
		while ($row = $this->fetch())
		{
			if ($limit!==false && $limit <= 0)
				break;
			
			$rows[] = $row;
			
			if ($limit !== false)
				--$limit;
		}

		/*for ($i=0; (count ($this->runAfterFetchAll) > $i); ++$i)
		{
			$rows = $this->runAfterFetchAll ($rows);
		}*/

		while (!empty ($this->runAfterFetchAll))
		{
			$rows = $this->runAfterFetchAll ($rows);
		}

		//for ($i=0; (!empty ($this->runAfterFetch) && count ($this->runAfterFetch) > $i); ++$i)
		//	$rows = $this->runAfterFetch ($rows);
		
		return $rows;
	}
}

function mysqli_establish_connect ($cdb='', $usr="root", $pass='', $chost="localhost")
{
	$sql = new db ($chost, $usr, $pass, $cdb);

	if (mysqli_connect_errno())
	{
		printf("Can't connect to MySQL server. Error Message: %s\n", mysqli_connect_error());
		return false;
	}

	return $sql;
}
