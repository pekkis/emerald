<?php
class Emerald_View_Helper_IconMedium extends Emerald_View_Helper_IconAbstract
{
	public function iconMedium($path, $contextHelp, $classNames = Array(), $iconText = "", $href="", $id = NULL)
	{
		return $this->_getIcon("medium", $path, $contextHelp, $classNames, $iconText, $href, $id);
	}
}