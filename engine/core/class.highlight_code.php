<?php
/*
 *      class.highlight_code.php
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

class highlight_code 
{
	public $geshi = null;

	function init (&$html)
	{
		$html = preg_replace_callback ('~<pre([^>]+)>(.*)</pre\s*>~Umis', array($this, 'geshi'), $html);
	}
	
	function geshi ($arr)
	{
		$code = trim ($arr[2]);

		if (!$code)
			return $code;

		$tagParams = $this->paramsHandler ($arr[1]);
		
		if (!$tagParams["lang"])
			return false;

		$intFormat = true;

		if (isset ($tagParams["autoFormat"]))
			$intFormat = strtolower ($tagParams["autoFormat"])=="true"?true:false;

		$lang = $tagParams["lang"];
		
		if ($intFormat)
			$code = $this->intelligentFormat ($code, $lang);

		if (is_null ($this->geshi))
		{
			$this->geshi = new geshi ($code, $lang);
			$this->geshi->enable_classes();
		} else {
			$this->geshi->set_source ($code);
		}

		if (strpos ($code, "\n", 1) > 1)
			$this->geshi->enable_line_numbers (GESHI_FANCY_LINE_NUMBERS, 1);
		else $this->geshi->enable_line_numbers (GESHI_NO_LINE_NUMBERS);

		return trim ($this->geshi->parse_code());
	}
	
	
	function paramsHandler ($string)
	{
		$paramsParts = preg_split ("/\s+/is", $string);

		if (empty ($paramsParts))
			return false;

		$parts = $result = array();
		$string = '';
		foreach ($paramsParts as $k=>$v)
		{
			if (!$v)
				continue;

			$v = str_replace (array('"', "'", ' '), '', $v);
			$parts = explode ('=', $v);

			if (!$parts)
				continue;

			$result[$parts[0]] = $parts[1];
		}
			
		return $result;
	}

	function highlightCode (&$data, $key="body")
	{
		if (empty ($data))
			return false;
				
		if (is_array ($data) && count ($data) > 0)
		{
			foreach ($data as $k=>$v)
			{
				$this->init ($data[$k][$key]);
			} 
		} else if (is_array ($data) && isset ($data[$key])) {
			$this->init ($data[$key]);
		} else {
			$this->init ($data);
		}
	}

	function intelligentFormat ($string, $lang=false)
	{
		$blockOpenSymbols = $blockCloseSymbols = $blockOpenWords = 
		$blockCloseWords = $constructSymbols = array();

		switch ($lang)
		{
			case "bash":
				$blockOpenWords = array ("then", "do");
				$blockCloseWords = array ("fi", "done");
				$constructSymbols = array ("else");
			break;

			//case ($lang == "cpp-qt" || $lang == "cpp"):


			case "html4strict":
			break;

			default:
				$blockOpenSymbols = array ('{');
				$blockCloseSymbols = array ('}');
		}

		$parseMode = '';

		$string = preg_replace ("/[\t\r]/ums", '', $string);
		$string = preg_replace (array ("/(\n)*\{(\n){0,1}/ums", "/(\n){0,1}\}(\n)*/ums"), array ('{', '}'), $string);
		$string = preg_replace (array ("/({)/ums", "/(})/ums"), array ("\n{\n", "\n}\n"), $string);

		if (preg_match ("/^array.*(.*)$/Uuims", $string))
		{
			$parseMode = "arrayBlock";
			$blockOpenSymbols = array ('(');
			$blockCloseSymbols = array (')');
		}

		$result = $lineRes = $inc = $blockName = '';
		$blockShift = $i = 0;

		$lines = explode ("\n", $string);
		$linesCnt = count ($lines);

		foreach ($lines as $key => $line) 
		{
			$line = trim ($line);

			switch ($inc) 
			{
				case '+':
					++$blockShift;
					$inc = '';
					if (preg_match ("/^([a-z]{2,})[ \\)]+/Uuis", $line, $matches))
					{
						 $blockName = trim (array_pop ($matches));
					} 

				break;
					
				case '-':
					--$blockShift;
					$inc = '';
				break;
			}

			if (in_array($line, $blockOpenWords))
			{
				$inc = '+';
			}

			if (in_array($line, $blockCloseWords))
			{
				--$blockShift;
			}

			$len = mb_strlen ($line);
			for ($i=0; $len>$i; ++$i)
			{
				$s = mb_substr ($line, $i, 1);
				$sp1 = mb_substr ($line, $i+1, 1);
				$sp2_size2 = mb_substr ($line, $i+2, 2);
				$sm1 = mb_substr ($line, $i-1, 1);

				if (in_array(trim($s), $blockOpenSymbols))
				{
					$inc = '+';
				}

				//  offset from the block
				if (($s=='}' || $s=='{') && ($sp1!="\n" && $sp1==';'))
				{
					$s .= "\n";
				}

				if ($blockName == "if" && $sp2_size2 == "if")
				{
					$s = "\n$s";
				}

				if (in_array(trim($s), $blockCloseSymbols))
				{
					--$blockShift;
				}

				$lineRes .= $s;
			}

			if (!trim ($lineRes)) // do not hanling empty strings
				continue;

			if (in_array ($line, $constructSymbols))
				$blockShift--;

			if ($blockShift > 0)
			{
				$lineRes = str_repeat ("\t", $blockShift) . $lineRes;
			}

			if (in_array ($line, $constructSymbols))
				$blockShift++;

			if ($len == $i && $blockName != "class")
			{
				$lineRes .= "\n";
			}

			$result .= $lineRes;
			$lineRes = '';
		}

		//echo nl2br($result);

		return trim ($result);
	}
		
}
