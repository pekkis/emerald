<?php
/**
 * Langlib filter filters langlib placeholders from a string and replaces them with
 * translated language fragments.
 *
 * @todo: optimize, cache maybe?
 */
class Emerald_Filter_Langlib implements Zend_Filter_Interface 
{

	
	/**
	 * Locale
	 *
	 * @var Zend_Locale
	 */
	private $_locale;
	
	/**
	 * Constructor, doh
	 *
	 * @param string $languageId char-2 language id.
	 */
	public function __construct(Zend_Locale $locale)
	{
		$this->_locale = $locale;
		
	}
			
	
	/**
	 * Does the actual langlib filtering
	 *
	 * @param string $value Original string
	 * @return string Filtered string
	 */
	public function filter($value)
	{
		$translate = Emerald_Application::getInstance()->getTranslate(); 
		
		// $regex = '/\{l\:([a-z,_,\-,A-Z,0-9\/]+)*\}/';
		$regex = "/\{l\:(([a-z,0-9\/_])*)\}/i";
		preg_match_all($regex, $value, $matches, PREG_PATTERN_ORDER);
		// $langlib = Emerald_Localization_Langlib::getInstance($this->_languageId);
				
		foreach($matches[0] as $key => $toReplace) {
			$value = str_ireplace($toReplace, $translate->translate($matches[1][$key], $this->_locale), $value);
		}
		return $value;
	}
	
}


?>