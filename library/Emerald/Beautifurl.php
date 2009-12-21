<?php
class Emerald_Beautifurl
{
	/**
	 * Make a beautifurl from a string
	 * 
	 * @param string $str Source string
	 * @param string $beautifier String to use for spacing
	 * @return string
	 */
	public static function fromString($str, $beautifier = '_')
	{
		$beautifurl = htmlentities(mb_strtolower($str, 'utf8'), ENT_COMPAT, 'utf-8');
		$beautifurl = preg_replace("/&(.)(acute|cedil|circ|ring|tilde|uml);/", "$1", $beautifurl);
		$beautifurl = preg_replace("/([^a-z0-9]+)/", $beautifier, html_entity_decode($beautifurl));
    	$beautifurl = trim($beautifurl, $beautifier);

    	return $beautifurl;
	}
	
	
	
	public static function fromArray(array $fragments, $prepend = null, $beautifier = '_')
	{
		$beautifulFragments = array();
		foreach($fragments as $fragment) {
			$beautifulFragments[] = self::fromString($fragment, $beautifier);
		}

		if($prepend) {
			array_unshift($beautifulFragments, $prepend);
		}
		
		$beautifurl = implode("/", $beautifulFragments);
		return $beautifurl;
		
	}
}
