<?php
class Emerald_beautifurl_Generator_Default implements Emerald_beautifurl_Generator_Interface 
{
	
	public function generate($beautifurl)
	{
		$beautifurl = htmlentities(mb_strtolower($beautifurl, 'utf8'), ENT_COMPAT, 'utf-8');
		$beautifurl = preg_replace("/&(.)(acute|cedil|circ|ring|tilde|uml);/", "$1", $beautifurl);
		$beautifurl = preg_replace("/([^a-z0-9]+)/", "_", html_entity_decode($beautifurl));
    	$beautifurl = trim($beautifurl, "_");

    	return $beautifurl;
	}
	
	
	
	
	
}
?>