<?php
abstract class Emerald_View_Helper_IconAbstract
{
	static $_iconPath = "/lib/gfx/nuvola/";
	static $_sizes = Array
	(
		"x-small"=>"16x16",
		"small"=>"22x22",
		"medium"=>"32x32",
		"large"=>"48x48"
	);
	
	protected function _getIcon($size, $path, $contextHelp = false, $classNames = Array(), $iconText = "", $href="", $id = NULL)
	{
		
		$strIcon ="<img align=\"absmiddle\" class=\"Emerald_Icon\" src=\"".self::$_iconPath.self::$_sizes[$size]."/".$path.".png\" alt=\"icon\" />";
		if($iconText) $strIcon .="<span>{$iconText}</span>";
		if($contextHelp) $strIcon = '<a href="'.$href.'" class="'.implode(" ", $classNames).'" title="{l:'.$contextHelp.'}"'.($id ? " id=\"{$id}\"":"").'>'.$strIcon."</a>";
		return $strIcon;
	}
}