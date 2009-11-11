<?php
class Filelib_Model_DbTable_File extends Zend_Db_Table_Abstract
{
	protected $_name = 'filelib_file';	
	protected $_id = array('id');
	protected $_rowClass = 'Filelib_Model_FileRow';
		
	
	
	protected $_referenceMap    = array(
        'Folder' => array(
            'columns'           => 'folder_id',
            'refTableClass'     => 'Filelib_Model_DbTable_Folder',
            'refColumns'        => 'id'
        ),
    );
	
	
}
