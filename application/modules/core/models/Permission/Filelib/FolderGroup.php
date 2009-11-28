<?php
class Emerald_Model_Permission_Filelib_FolderGroup extends Zend_Db_Table_Abstract
{
	protected $_name = 'permission_filelib_folder_ugroup';
    protected $_primary = array('folder_id', 'ugroup_id');
}
