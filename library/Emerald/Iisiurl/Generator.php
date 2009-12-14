<?php
/**
 * Generates beautifurls for different languages.
 * 
 * @package Emerald_beautifurl
 * @author pekkis
 *
 */
class Emerald_beautifurl_Generator
{

	
	/**
	 * Array of instantiated generator objects
	 *
	 * @var array
	 */
	private $_generators = array();
	
	private function __construct() { }
	
	
	/**
	 * Returns a generator for the specified language.
	 *
	 * @param string $language Language identifier.
	 * @return Emerald_beautifurl_Generator_Interface
	 */
	public function getGenerator($language)
	{
		$language = ucfirst($language);
		if(!isset($this->_generators[$language])) {
			try {
				$className = "Emerald_beautifurl_Generator_{$language}";
								
				@Zend_Loader::loadClass($className);
				$this->_generators[$language] = new $className();
			} catch(Zend_Exception $e) {
				$this->_generators[$language] = new Emerald_beautifurl_Generator_Default();
			}
		}
		
		return $this->_generators[$language];
		
	}
	
	
	
	
    /**
     * Get singleton
     *
     * @return Emerald_beautifurl_Generator
     */
    public static function getInstance()
    {
        static $instance;
        if(!$instance) {
            $instance = new self();
        }
        return $instance;  
    }
		
	
	
	
	public function generate($beautifurl, $language)
	{
		return $this->getGenerator($language)->generate($beautifurl);
	}
	
	
	
}
?>