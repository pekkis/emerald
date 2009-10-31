<?php
class Emerald_Model_Shard extends Zend_Db_Table_Abstract
{
    protected $_name = 'shard';
    
    public function findAllowed()
    {
        return $this->fetchAll('status & 1 AND status & 2');
    }
}
?>