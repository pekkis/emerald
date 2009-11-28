<?php
class Emerald_View_Helper_Icon extends Zend_View_Helper_Abstract
{
	static $_iconPath = "/lib/gfx/nuvola/";
	static $_sizes = Array
	(
		"x-small"=>"16x16",
		"small"=>"22x22",
		"medium"=>"32x32",
		"large"=>"48x48"
	);
	
	public function icon()
	{
		return $this;
	}
		
	
	protected function _getIcon($size, $path, $contextHelp = false, $classNames = Array(), $iconText = "", $href="", $id = NULL)
	{
		
		$strIcon ="<img align=\"absmiddle\" class=\"Emerald_Icon\" src=\"".self::$_iconPath.self::$_sizes[$size]."/".$path.".png\" alt=\"icon\" />";
		if($iconText) $strIcon .="<span>{$iconText}</span>";
		if($contextHelp) $strIcon = '<a href="'.$href.'" class="'.implode(" ", $classNames).'" title="{l:'.$contextHelp.'}"'.($id ? " id=\"{$id}\"":"").'>'.$strIcon."</a>";
		return $strIcon;
	}

	public function large($path, $contextHelp, $classNames = Array(), $iconText = "", $href="", $id = NULL)
	{
		return $this->_getIcon("large", $path, $contextHelp, $classNames, $iconText, $href, $id);
	}
		
	
	public function small($path, $contextHelp, $classNames = Array(), $iconText = "", $href="", $id = NULL)
	{
		return $this->_getIcon("small", $path, $contextHelp, $classNames, $iconText, $href, $id);
	}
	
	
	
	public function xSmall($path, $contextHelp, $classNames = Array(), $iconText = "", $href="", $id = NULL)
	{
		return $this->_getIcon("x-small", $path, $contextHelp, $classNames, $iconText, $href, $id);
	}
	
	public function medium($path, $contextHelp, $classNames = Array(), $iconText = "", $href="", $id = NULL)
	{
		return $this->_getIcon("medium", $path, $contextHelp, $classNames, $iconText, $href, $id);
	}
	
}