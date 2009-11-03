<?php
class Emerald_Model_User extends Zend_Db_Table_Abstract
{
    protected $_name = 'user';
    protected $_rowClass = 'Emerald_User'; 
    
    
    
    public function getGroupIds($user_id)
    {
    	return $this->getAdapter()->fetchCol("SELECT group_id FROM user_ugroup WHERE user_id = :user_id", array('user_id' => $user_id));
    }
    
    public function findIndexed($sort, $start, $end, &$totalCount = false)
    {
    	$start = (int)$start;
    	$end = (int)$end;
    	$count = $end - $start +1;
    	if($count < 1)
    	{
    		$count = null;
    	}
    	$data = $this->fetchAll(array(), "$sort DESC", $count, (int)$start);
    	if(func_num_args() > 3)
    	{
    		$totalCount = $this->getAdapter()->fetchOne("SELECT COUNT(*) FROM {$this->_name}");
    	}
    	return $data;
    }
    
}
?>