<?php
class Emerald_Beautifurl
{
	
	public static function fromString($str)
	{
		$beautifurl = htmlentities(mb_strtolower($str, 'utf8'), ENT_COMPAT, 'utf-8');
		$beautifurl = preg_replace("/&(.)(acute|cedil|circ|ring|tilde|uml);/", "$1", $beautifurl);
		$beautifurl = preg_replace("/([^a-z0-9]+)/", "_", html_entity_decode($beautifurl));
    	$beautifurl = trim($beautifurl, "_");

    	return $beautifurl;
	}
	
	
	
	public static function fromArray(array $fragments, $prepend = null)
	{
		$beautifulFragments = array();
		foreach($fragments as $fragment) {
			$beautifulFragments[] = self::fromString($fragment);
		}

		if($prepend) {
			array_unshift($beautifulFragments, $prepend);
		}
		
		$beautifurl = implode("/", $beautifulFragments);
		return $beautifurl;
		
	}
	
	
	
	
	
	
}