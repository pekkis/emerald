<?php
/**
 * Zend DB's metadata cache is so incredibly stupid that is almost unbelievable. This fixes the stupidity.
 *
 * @author pekkis
 * @package Emerald_Common_Db
 *
 */
class Emerald_Common_Db_Table_Abstract extends Zend_Db_Table_Abstract
{

    static private $_metadataData = array();


    protected function _setupMetadata()
    {
        if ($this->metadataCacheInClass() && (count($this->_metadata) > 0)) {
            return true;
        }

        if(isset(self::$_metadataData[get_class($this)])) {
            $this->_metadata = self::$_metadataData[get_class($this)];
            return;
        }

        parent::_setupMetadata();
        self::$_metadataData[get_class($this)] = $this->_metadata;


    }

}