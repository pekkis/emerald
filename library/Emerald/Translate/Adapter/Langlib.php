<?php
/**
 * Zend_Translate adapter for Emerald's db langlib
 *
 */
class Emerald_Translate_Adapter_Langlib extends Zend_Translate_Adapter 
{
	
    /**
     * Loads translation data from specified db.
     *
     * @param Zend_Db_Adapter_Abstract $data Db adapter
     * @param string $locale Locale
     * @param array $options Options
     * 
     * @todo Implement options 
     */
    protected function _loadTranslationData($data, $locale, array $options = array())
    {
    	$db = Emerald_Server::getInstance()->getDb();
    	
    	$sql = "SELECT path, translation FROM langlib JOIN langlib_translation ON(langlib.id = langlib_translation.langlib_id AND langlib_translation.language_id = ?) ORDER BY language_id";
    	
    	$res = $db->fetchAll($sql, $locale);
    	    	    	
    	foreach($res as $row) {
    		$this->_translate[$locale][$row->path] = $row->translation;
    	}
    }
    
    public function toString()
    {
    	return 'Langlib';
    }
	
	
}
?>