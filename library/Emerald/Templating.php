<?php
/*
 * A class for managing user templates
 */
class Emerald_Templating
{
	public function getTemplates()
	{
		$templatePath = Emerald_Application::getInstance()->getCustomer()->getRoot('templates');
		return $this->_getFiles($templatePath);
	}
	
	public function getInnertemplates()
	{
		$innerTemplatePath = Emerald_Application::getInstance()->getCustomer()->getRoot('innertemplates');
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