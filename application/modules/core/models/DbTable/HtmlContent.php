<?php
class Core_Model_DbTable_HtmlContent extends Zend_Db_Table_Abstract
{
    protected $_name = 'htmlcontent';
    protected $_primary = array('page_id', 'block_id');
    
}