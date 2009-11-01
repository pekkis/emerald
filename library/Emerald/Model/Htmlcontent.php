<?php
class Emerald_Model_Htmlcontent extends Zend_Db_Table_Abstract
{
    protected $_name = 'htmlcontent';
    protected $_primary = array('page_id', 'block_id');
    protected $_rowClass = 'Emerald_Db_Table_Row_Htmlcontent';
    
}
