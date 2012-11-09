<?php
class _Exception extends Exception 
{
	public $coreErrors = array();
	
	function __construct (array $errors)
	{
		$this->coreErrors = $errors;
	}
	
	public function __toString() 
	{
		return "exception '".__CLASS__ ."' with message '".$this->getMessage()."' in ".$this->getFile().":".$this->getLine()."\nStack trace:\n".$this->getTraceAsString();
	}
	
	public function criticalError()
	{
		if (!empty ($this->coreErrors))
		{
			system::setParam ("page", "coreErrorPage");
			return $this->coreErrors;
		}
		
		return false;
	}
}
