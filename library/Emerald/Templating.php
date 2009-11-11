<?php
/*
 * A class for managing user templates
 */
class Emerald_Templating
{
	public function getLayouts()
	{
		$layoutPath = Zend_Registry::get('Emerald_Customer')->getRoot('layouts');
		return $this->_getFiles($layoutPath);
	}
	
	private function _getFiles($path)
	{
		$res = Array();
		foreach(scandir($path) as $file)
		{
			if(substr($file,-4) == ".php") $res[] = basename($file, '.php');
		}
		return $res;
	}
}