<?php
class Core_Model_Permission_FolderGroup extends Zend_Db_Table_Abstract
{
	protected $_name = 'permission_folder_ugroup';
    protected $_primary = array('folder_id', 'ugroup_id');
	
}
