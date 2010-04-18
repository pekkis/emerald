<?php
class Emerald_Beautifurl_Default
{
	private $_options = array();
	
	public function __construct($options = array()) {
		$this->_options = $options;
	}
	
		
	/**
	 * Make a beautifurl from a string
	 * 
	 * @param string $str Source string
	 * @param string $beautifier String to use for spacing
	 * @return string
	 */
	public function fromString($str, $beautifier = '-')
	{
		
		if(isset($this->_options['skip']) && $this->_options['skip'] == 1) {
			return $str;
		}
		
		$beautifurl = htmlentities(mb_strtolower($str, 'utf8'), ENT_COMPAT, 'utf-8');
		$beautifurl = preg_replace('/&(.)(acute|cedil|circ|ring|tilde|uml);/', "$1", $beautifurl);
		$beautifurl = preg_replace('/([^a-z0-9]+)/', $beautifier, html_entity_decode($beautifurl));
    	$beautifurl = trim($beautifurl, $beautifier);

    	return $beautifurl;
	}
		
	
	public function fromArray(array $fragments, $prepend = null, $beautifier = '-')
	{
		$beautifulFragments = array();
		foreach($fragments as $fragment) {
			$beautifulFragments[] = $this->fromString($fragment, $beautifier);
		}

		if($prepend) {
			array_unshift($beautifulFragments, $prepend);
		}
		
		$beautifurl = implode('/', $beautifulFragments);
		return $beautifurl;
		
	}
	
	
	
	
}