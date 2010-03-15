<?php
class Core_Model_DbTable_Locale_Option extends Emerald_Db_Table_Abstract
{
    protected $_name = 'locale_option';
    protected $_primary = array('locale_locale', 'identifier');
    
}
?>