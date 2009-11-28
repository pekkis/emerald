<?php
class Core_Model_Form extends Zend_Db_Table_Abstract
{
    protected $_name = 'form';
    protected $_primary = 'id';
    protected $_rowClass = 'Emerald_Db_Table_Row_Form';
    
}
