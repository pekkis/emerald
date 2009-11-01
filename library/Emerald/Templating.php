<?php
/*
 * A class for managing user templates
 */
class Emerald_Templating
{
	public function getTemplates()
	{
		$templatePath = Zend_Registry::get('Emerald_Customer')->getRoot('templates');
		return $this->_getFiles($templatePath);
	}
	
	public function getInnertemplates()
	{
		$innerTemplatePath = Zend_Registry::get('Emerald_Customer')->getRoot('innertemplates');
		return $this->_getFiles($innerTemplatePath);
	}
	private function _getFiles($path)
	{
		$res = Array();
		foreach(scandir($path) as $file)
		{
			if(substr($file,-6) == ".phtml") $res[] = $file;
		}
		return $res;
	}
}