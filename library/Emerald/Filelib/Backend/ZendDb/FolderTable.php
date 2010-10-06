<?php

namespace Emerald\Filelib\Backend\ZendDb;

/**
 * Folder table
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class FolderTable extends \Zend_Db_Table_Abstract
{
    protected $_name = 'emerald_filelib_folder';
    protected $_id = array('id');
    protected $_rowClass = '\Emerald\Filelib\Backend\ZendDb\FolderRow';


    protected $_referenceMap    = array(
        'Folder' => array(
            'columns'           => 'parent_id',
            'refTableClass'     => '\Emerald\Filelib\Backend\ZendDb\FolderTable',
            'refColumns'        => 'id'
            ),
            );


}
