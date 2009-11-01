<?php
class Emerald_View_Helper_IconLarge extends Emerald_View_Helper_IconAbstract
{
	public function iconLarge($path, $contextHelp, $classNames = Array(), $iconText = "", $href="",  $id = NULL)
	{
		return $this->_getIcon("large", $path, $contextHelp, $classNames, $iconText, $href,  $id);
	}
}