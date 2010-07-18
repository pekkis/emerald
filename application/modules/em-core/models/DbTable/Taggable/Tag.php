<?php
class EmCore_Model_DbTable_Taggable_Tag extends Zend_Db_Table_Abstract
{
    protected $_name = 'emerald_taggable_tag';
    protected $_primary = array('taggable_id', 'tag_id');
    
}
