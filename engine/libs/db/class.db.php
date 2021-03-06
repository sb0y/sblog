<?php

class db
{
	public $runAfterFetchAll = array(), $runAfterFetch = array();
	public $database = "", $connect_host = "", $user = "", $password = "", $codepage = "utf8";
	private $inited = false, $log = array(), $initTime = 0, $lastQueryTime = 0, $dbObj = null, $uniqueQueryKey = "",
	$startTime = 0;

	const ERROR = 0;
	const SUCCESS = 1;
	const INFO = 2;

	public function __construct ($configArray)
	{
		$this->conf ($configArray);
		
		if (!extension_loaded ("mysqli"))
			throw new exception("mysqliIsNotFound", "PHP can't load MySQLi extension");
	}

	public function __destruct()
	{
		if ($this->inited)
			$this->dbObj->close();
	}
	
	private function get_mt()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	public function conf ($configArray)
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

	private function generateRandomString ($length = 10) 
	{
        return substr ( MD5 ( microtime() ), 0, $length );
	}

	public function init ($configArray=null)
	{
		if ($this->inited)
			return;
		
		if ($configArray)
			$this->conf ($configArray);

		$this->dbObj = new MySQLi ($this->connect_host, $this->user, $this->password, $this->database);
		$this->dbObj->set_charset ($this->codepage);
		$this->dbObj->use_result();
		
		if (mysqli_connect_errno())
		{
			printf("Can't connect to MySQL server. Error Message: %s\n", mysqli_connect_error());
			throw new exception(mysqli_error($this->dbObj), mysqli_errno($this->dbObj));
		}

		$this->initTime = $this->get_mt();
		$this->log (db::INFO, array ("message"=>"Starting generic Database layer with MySQLi extension. Version 1.0."));

		$this->inited = true;
	}

	public function log ($queryType, $data=false)
	{		
		$data["type"] = $queryType;
		$data["time"] = $this->get_mt();
		$data["realQueryTime"] = $this->lastQueryTime;
		
		if (isset ($this->error))
			$data["errorMessage"] = $this->error;
		
		array_push ($this->log, $data);
	}

	private function replaceVars (&$query, $args)
	{
		$query = str_replace ( '?', $this->uniqueQueryKey, $query );

		$tmp = explode ( $this->uniqueQueryKey, $query );
			
		foreach ($args as $k=>$v)
		{
			$v = $this->escapeString ($v);
			$tmp[$k] .= $v;	
		}
				
		$query = implode ('', $tmp);
	}

	public function query (/*...*/)
	{
		$this->startTime = microtime (true);
		$this->uniqueQueryKey = "{QUERYKEY_".$this->generateRandomString()."}";
		
		// init system if needed
		$this->init();
		$args = func_get_args();
		$query = array_shift ($args);
		
		if (count ($args) >= 1)
		{
			$this->replaceVars ($query, $args);
		}

		try 
		{
			//echo $query;
			$this->dbObj->real_query ($query);

			if (mysqli_error ($this->dbObj))
			{
				throw new exception (mysqli_error($this->dbObj), mysqli_errno($this->dbObj));
			}

			$endtime = microtime (true);
			$this->lastQueryTime = $endtime - $this->startTime;

			$res = new db_result ( $this->dbObj );

			$infoArray = array ( "query" => $query );

			if ( $res )
			{
				$infoArray [ "rowsNum" ] = $res->getNumRows();
			}

			$this->log ( db::SUCCESS, $infoArray );

			$res->runAfterFetchAll = $this->runAfterFetchAll;
			$res->runAfterFetch = $this->runAfterFetch;
			return $res;
		} catch (Exception $e) {
			echo "<b>" . $e->getMessage() . "</b><br />\r\n";
			var_dump ($e->getTrace());
			$this->log (db::ERROR, array("query"=>$query, "message"=>$e->getTrace()));
		}

		return true;
	}

	public function insert_id()
	{
		return mysqli_insert_id ($this->dbObj);
	}
	
	public function escapeString ( $string )
	{
		$this->init();
		return $this->dbObj->real_escape_string ( $string );
	}
	
	public function updateTable ($table, $data=array(), $idName, $id)
	{
		$tmp = array();
		
		if (!empty ($data))
		{
			foreach ($data as $k=>$v)
			{
				$v = $this->escapeString ( $v );

				if ( is_numeric ( $v ) )
					$tmp[] = "`$k`=$v";
				else $tmp[] = "`$k`='$v'";
			}
			
			if ( !empty ($tmp) )
			{
				if ( is_numeric ( $id ) )
					$q = intval ( $id );
				else $q = "'" . $this->escapeString ( $id ) . "'";

				return $this->query ("UPDATE `$table` SET ".implode (",", $tmp)." WHERE `$idName`=$id");
			}
		}

		return false;
	}

	public function buildHtmlLog ($show_types = array (db::INFO, db::ERROR, db::SUCCESS))
	{
		$out = '<table cellpadding="3" cellspacing="0" border="1" width="100%">';
		$out .= '<tr><th align="center">#</th><th align="center">Type</th><th>Message Time</th><th>Query Time</th><th>Record</th>';
		
		foreach ($this->log as $n=>$rec)
		{
			if (!in_array($rec["type"], $show_types))
				continue;

			if ( !isset ( $rec [ "rowsNum" ] ) )
				$rec [ "rowsNum" ] = 0;
						
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

					if ( $rec [ "rowsNum" ] != -1 )
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
	public $runAfterFetchAll = array(), $runAfterFetch = array();
	private $availableForFree = false;

	public function __destruct()
	{
		if ( $this->availableForFree )
            $this->free();
	}

    public function __call ( $funcName, $args )
    {
		$navArray =& $this->$funcName;
		$navArray2 = array_shift ( $navArray );

		foreach ( $args as $k => $v )
				$args [ $k ] = &$v;

		$runFunc = $navArray2[0];

		if ( count ( $navArray2 ) > 1 )
		{
			$runFunc .= "::" . $navArray2 [ 1 ];
		}

		return call_user_func_array ( $runFunc, $args );
    }
	
	public function fetch()
    {
        $result = $this->fetch_assoc();

		while ( !empty ( $this->runAfterFetch ) ) 
		{
			if ( !$result )
				break;

			// вот это нужно, чтобы обработчики для одиночных запросов БД,
			// работали так же, как и запросы выдаваемые списками
			$result = $this->runAfterFetch ( array ( $result ) );
			$result = array_shift ( $result );
		}

        $this->availableForFree = true;

        return $result;
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

		while ( !empty ( $this->runAfterFetchAll ) )
		{
			if ( !$rows )
				break;

			$rows = $this->runAfterFetchAll ($rows);
		}

		//for ($i=0; (!empty ($this->runAfterFetch) && count ($this->runAfterFetch) > $i); ++$i)
		//	$rows = $this->runAfterFetch ($rows);
		
        $this->availableForFree = true;

		return $rows;
	}

	public function getNumRows()
	{
		if ( !@isset ( $this->num_rows ) )
			return -1;

		return $this->num_rows;
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
