<?php
class Emerald_Filelib_DbTable_Folder extends Zend_Db_Table_Abstract
{
	protected $_name = 'filelib_folder';	
	protected $_id = array('id');
	protected $_rowClass = 'Emerald_Filelib_FolderRow';
		
	
	protected $_referenceMap    = array(
        'Folder' => array(
            'columns'           => 'parent_id',
            'refTableClass'     => 'Emerald_Filelib_DbTable_Folder',
            'refColumns'        => 'id'
        ),
    );
	
	
}
