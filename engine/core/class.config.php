<?php
class config extends core
{
	private $core = null;

	function __construct (&$core)
	{
		$this->core = $core;
	}

	public function processArray ($array)
	{
		foreach ($array as $sectionName => $value)
		{	
			if (is_array ($value))
			{
				$this->processSection ($sectionName, $value);
				continue;
			}

			if (isset ($this->core->$sectionName))
			{
				$this->core->$sectionName = $value;
			} else {
				//$this->core->params[$sectionName] = $value;
				system::setParam ($sectionName, $value);
			}
		}
	}

	private function processSection ($keyName, $sectionArray)
	{
		$test;

		foreach ($sectionArray as $sectionName => $value)
		{
			$test = $this->core->$keyName;

			if (is_array ($value))
			{
				$this->processSection ($sectionName, $value);
				continue;
			}

			if (method_exists ($this->core->$keyName, $sectionName) )
			{
				$test->$sectionName ($value);
				continue;
			}

			if (isset ($this->core->config ["{$keyName}Config"]) )
			{
				$this->core->config ["{$keyName}Config"][$sectionName] = $value;
				continue;
			}

			$test = isset ($this->core->$keyName->$sectionName) ? $this->core->$keyName->$sectionName : null;

 			if (isset ($test)) 
 			{
				$this->core->$keyName->$sectionName = $value;
			} else {
				system::setParam ($sectionName, $value);
			}
		}

		//print_r ($conf);
	}
	
}
