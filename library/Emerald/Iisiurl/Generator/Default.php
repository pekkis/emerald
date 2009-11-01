<?php
class Emerald_Iisiurl_Generator_Default implements Emerald_Iisiurl_Generator_Interface 
{
	
	public function generate($iisiurl)
	{
		$iisiurl = htmlentities(mb_strtolower($iisiurl, 'utf8'), ENT_COMPAT, 'utf-8');
		$iisiurl = preg_replace("/&(.)(acute|cedil|circ|ring|tilde|uml);/", "$1", $iisiurl);
		$iisiurl = preg_replace("/([^a-z0-9]+)/", "_", html_entity_decode($iisiurl));
    	$iisiurl = trim($iisiurl, "_");

    	return $iisiurl;
	}
	
	
	
	
	
}
?>