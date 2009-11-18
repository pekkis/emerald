<?php
class Emerald_Filelib_DbTable_File extends Zend_Db_Table_Abstract
{
	protected $_name = 'filelib_file';	
	protected $_id = array('id');
	protected $_rowClass = 'Emerald_Filelib_FileRow';
		
	
	
	protected $_referenceMap    = array(
        'Folder' => array(
            'columns'           => 'folder_id',
            'refTableClass'     => 'Emerald_Filelib_DbTable_Folder',
            'refColumns'        => 'id'
        ),
    );
	
	
}
