<?php
class EmCore_Model_DbTable_Permission_Folder_Ugroup extends Zend_Db_Table_Abstract
{
    protected $_name = 'emerald_permission_folder_ugroup';
    protected $_primary = array('folder_id', 'ugroup_id');
    
}
