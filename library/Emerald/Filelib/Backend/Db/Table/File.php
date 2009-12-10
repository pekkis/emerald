<?php
class Emerald_Filelib_Backend_Db_Table_File extends Zend_Db_Table_Abstract
{
	protected $_name = 'filelib_file';	
	protected $_id = array('id');
	protected $_rowClass = 'Emerald_Filelib_Backend_Db_Row_File';
		
	
	
	protected $_referenceMap    = array(
        'Folder' => array(
            'columns'           => 'folder_id',
            'refTableClass'     => 'Emerald_Filelib_Backend_Db_Table_Folder',
            'refColumns'        => 'id'
        ),
    );
	
	
}
