<?php
class EmCore_Model_DbTable_CustomContent extends Zend_Db_Table_Abstract
{
    protected $_name = 'emerald_customcontent';
    protected $_primary = array('page_id', 'block_id');
    
}
