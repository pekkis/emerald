<?php
class Emerald_View_Helper_IconSmall extends Emerald_View_Helper_IconAbstract
{
	public function iconSmall($path, $contextHelp, $classNames = Array(), $iconText = "", $href="", $id = NULL)
	{
		return $this->_getIcon("small", $path, $contextHelp, $classNames, $iconText, $href, $id);
	}
}