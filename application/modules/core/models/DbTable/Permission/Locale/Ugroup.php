<?php
class Core_Model_DbTable_Permission_Locale_Ugroup extends Zend_Db_Table_Abstract
{
    protected $_name = 'permission_locale_ugroup';
    protected $_primary = array('locale_locale', 'ugroup_id');
    
}