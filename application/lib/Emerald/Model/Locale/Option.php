<?php
class Emerald_Model_Locale_Option extends Zend_Db_Table_Abstract
{
    protected $_name = 'locale_option';
    protected $_primary = array('locale_locale', 'identifier');
    
}
?>