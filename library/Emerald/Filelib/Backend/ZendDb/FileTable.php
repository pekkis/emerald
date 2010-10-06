<?php

namespace Emerald\Filelib\Backend\ZendDb;

/**
 * File table
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class FileTable extends \Zend_Db_Table_Abstract
{
    protected $_name = 'emerald_filelib_file';
    protected $_id = array('id');
    protected $_rowClass = '\Emerald\Filelib\Backend\ZendDb\FileRow';

    protected $_referenceMap    = array(
        'Folder' => array(
            'columns'           => 'folder_id',
            'refTableClass'     => '\Emerald\Filelib\Backend\ZendDb\FolderTable',
            'refColumns'        => 'id'
               ),
            );


}
