<?php
class plugin_video extends plugins_loader
{

	public function run()
	{
		// your luxury code
		$trigger = 'Y';
		$sqlData = $this->db->query ("SELECT * FROM `video` WHERE `showOnSite` = '?'", $trigger)->fetchAll();
		$this->smarty->assign ("videos", $sqlData);
	}
};