<?php
/*
 * A class for managing user templates
 */
class Emerald_Templating
{
	public function getTemplates()
	{
		$layoutPath = Zend_Registry::get('Emerald_Customer')->getRoot('layouts');
		return $this->_getFiles($layoutPath);
	}
	
	private function _getFiles($path)
	{
		$res = Array();
		foreach(scandir($path) as $file)
		{
			if(substr($file,-6) == ".php") $res[] = basename($file);
		}
		return $res;
	}
}