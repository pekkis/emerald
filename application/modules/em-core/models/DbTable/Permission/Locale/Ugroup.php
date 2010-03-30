<?php
class EmCore_Model_DbTable_Permission_Locale_Ugroup extends Zend_Db_Table_Abstract
{
    protected $_name = 'emerald_permission_locale_ugroup';
    protected $_primary = array('locale_locale', 'ugroup_id');
    
}
