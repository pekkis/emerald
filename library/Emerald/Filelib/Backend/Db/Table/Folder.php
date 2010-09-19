<?php
/**
 * Folder table
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class Emerald_Filelib_Backend_Db_Table_Folder extends Zend_Db_Table_Abstract
{
    protected $_name = 'emerald_filelib_folder';
    protected $_id = array('id');
    protected $_rowClass = 'Emerald_Filelib_Backend_Db_Row_Folder';


    protected $_referenceMap    = array(
        'Folder' => array(
            'columns'           => 'parent_id',
            'refTableClass'     => 'Emerald_Filelib_Backend_Db_Table_Folder',
            'refColumns'        => 'id'
            ),
            );


}
