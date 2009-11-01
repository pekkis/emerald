<?php
class Emerald_View_Helper_IconXSmall extends Emerald_View_Helper_IconAbstract
{
	public function iconXSmall($path, $contextHelp, $classNames = Array(), $iconText = "", $href="", $id = NULL)
	{
		return $this->_getIcon("x-small", $path, $contextHelp, $classNames, $iconText, $href, $id);
	}
}