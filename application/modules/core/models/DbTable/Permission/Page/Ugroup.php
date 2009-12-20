<?php
class Core_Model_DbTable_Permission_Page_Ugroup extends Zend_Db_Table_Abstract
{
    protected $_name = 'permission_page_ugroup';
    protected $_primary = array('page_id', 'ugroup_id');
    
}
