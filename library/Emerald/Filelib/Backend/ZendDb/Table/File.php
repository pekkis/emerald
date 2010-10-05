<?php
/**
 * File table
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class Emerald_Filelib_Backend_ZendDb_Table_File extends Zend_Db_Table_Abstract
{
    protected $_name = 'emerald_filelib_file';
    protected $_id = array('id');
    protected $_rowClass = 'Emerald_Filelib_Backend_ZendDb_Row_File';

    protected $_referenceMap    = array(
        'Folder' => array(
            'columns'           => 'folder_id',
            'refTableClass'     => 'Emerald_Filelib_Backend_ZendDb_Table_Folder',
            'refColumns'        => 'id'
               ),
            );


}
