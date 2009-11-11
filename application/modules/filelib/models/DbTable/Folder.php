<?php
class Filelib_Model_DbTable_Folder extends Zend_Db_Table_Abstract
{
	protected $_name = 'filelib_folder';	
	protected $_id = array('id');
	protected $_rowClass = 'Filelib_Model_FolderRow';
		
	
	protected $_referenceMap    = array(
        'Folder' => array(
            'columns'           => 'parent_id',
            'refTableClass'     => 'Filelib_Model_DbTable_Folder',
            'refColumns'        => 'id'
        ),
    );
	
	
}
