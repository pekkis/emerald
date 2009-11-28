<?php
class Core_Model_User_Option extends Zend_Db_Table_Abstract
{
    protected $_name = 'user_option';
    protected $_primary = array('user_id', 'identifier');
    
}
?>