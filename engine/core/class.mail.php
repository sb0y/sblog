<?php
/*
 *      class.mail.php
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

 class mail extends core
 {
	public $smtpServer = "localhost", $smtpPort = 25, $smtpUser = '', $smtpPass = '', $fromTitle = "noreply", $codepage = "UTF-8", 
	$fromEmail = "noreply@bagrintsev.me", $contentType = "text/html", $emailTitle = '';
	private $headers = array(), $userHeaders = array(), $smtpConnection = null;
	
	function __construct()
	{
		parent::initSmarty();
		$this->smarty->setCaching (Smarty::CACHING_OFF);
	}
	
	function __destruct()
	{
		if (!is_null ($this->smtpConnection))
		{
			fputs ($this->smtpConnection, "QUIT\r\n"); 
			fclose ($this->smtpConnection); 
			$this->smtpConnection = null;
		}
	}

	function assign ($key, $value)
	{
		return $this->smarty->assign ($key, $value);
	}

	function init()
	{	
		$this->smtpConnection = fsockopen ($this->smtpServer, $this->smtpPort);
	}

	function encode_utf8 ($in)
	{
		return "=?UTF-8?B?".base64_encode ($in)."?=";
	}

	function getHeaders ($to, $appeal=false)
	{		
		$this->headers = array ();
		
		$h[] = "To: ".$this->encode_utf8 ($appeal)." <$to>";
		$h[] = "From: ".$this->encode_utf8 ($this->fromTitle)." <".$this->fromEmail.">";
		$h[] = "Subject: ".$this->emailTitle;
		$h[] = "Reply-To: ".$this->encode_utf8 ($this->fromTitle)." <".$this->fromEmail.">";
		$h[] = "X-Mailer: Sbl0g Mail System";
		$h[] = "MIME-Version: 1.0";
		$h[] = "Content-type: {$this->contentType}; charset = \"{$this->codepage}\"";
		$h[] = "Content-Transfer-Encoding: base64";

		if ($this->userHeaders)
		{
			foreach ($this->userHeaders as $k=>$v)
			{
				$h[] = $v;
			}
		}

		$this->headers = $h;
		return $this->headers;
	}

	function sendMail ($tplFile, $to, $title='', $mailVars=false)
	{
		if (!$this->smtpConnection)
			$this->init();

		if (isset ($_SESSION["user"]["nick"]))
			$appeal = $_SESSION["user"]["nick"];
		else $appeal = $this->smarty->getTemplateVars ("appeal");

		$message = base64_encode ($this->smarty->fetch ($tplFile));
		$ttl = $this->smarty->getTemplateVars ("title");
	
		if ($ttl)
			$title = array_shift ($ttl);
			
		$this->emailTitle = $this->encode_utf8 ($title);
		$this->getHeaders ($to, $appeal);

		$this->realSend ($to, $this->fromEmail, $message);
		$this->fromEmail = '';
		$this->fromTitle = '';
		$this->emailTitle = '';
		$this->userHeaders = array();
	}

	public function addHeaders (array $headers)
	{
		foreach ($headers as $k=>$v)
		{
			$this->userHeaders[] = $v;
		}
	}
	
	private function realSend ($to, $from, $body)
	{
		$talk = array();
		
		if ($this->smtpConnection) 
		{

			$headers = implode ("\r\n", $this->headers);
			$ip = gethostbyname ($this->smtpServer);
			$host = gethostbyaddr ($ip);
			//$ehlo = explode ('@', $to);
			//$ehlo = trim ($ehlo[1]);
			$ehlo = explode ('@', $this->fromEmail);
			$ehlo = $ehlo[1];
			
			fputs ($this->smtpConnection, "EHLO $ehlo\r\n"); 
			$talk["hello"] = fgets ( $this->smtpConnection, 1024 );
			
			if ($this->smtpUser)
			{
				fputs($this->smtpConnection, "auth login\r\n");
				$talk["res"] = fgets($this->smtpConnection,1024);
				fputs($this->smtpConnection, $this->smtpUser."\r\n");
				$talk["user"] = fgets($this->smtpConnection,1024);
			}
			
			if ($this->smtpPass)
			{
				fputs($this->smtpConnection, $this->smtpPass."\r\n");
				$talk["pass"] = fgets($this->smtpConnection,256);
			}
			
			fputs ($this->smtpConnection, "MAIL FROM: <".$from.">\r\n"); 
			$talk["From"] = fgets ($this->smtpConnection, 1024); 
			fputs ($this->smtpConnection, "RCPT TO: <".$to.">\r\n"); 
			$talk["To"] = fgets ($this->smtpConnection, 1024); 
			fputs ($this->smtpConnection, "DATA\r\n");
			$talk["data"] = fgets ( $this->smtpConnection, 1024);
			fputs ($this->smtpConnection, $headers."\r\n\r\n\r\n".$body."\r\n.\r\n");
			$talk["send"] = fgets ($this->smtpConnection, 256);
		}

		//print_r($talk);
				
		return $talk;
	}
}
