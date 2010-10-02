<?php
class EmCore_Model_DbTable_Locale_Option extends Emerald_Common_Db_Table_Abstract
{
    protected $_name = 'emerald_locale_option';
    protected $_primary = array('locale_locale', 'identifier');

}
?>