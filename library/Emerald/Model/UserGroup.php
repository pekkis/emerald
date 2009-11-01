<?php
class Emerald_Model_UserGroup extends Zend_Db_Table_Abstract 
{
	protected $_name = 'user_ugroup';
	protected $_primary = array('user_id', 'group_id');
	
	protected $_referenceMap    = array(
        'User' => array(
            'columns'           => array('user_id'),
            'refTableClass'     => 'Emerald_Model_User',
            'refColumns'        => array('id')
        ),
        'Group' => array(
            'columns'           => array('group_id'),
            'refTableClass'     => 'Emerald_Model_Group',
            'refColumns'        => array('id')
        )
    );
    
	public function setUserGroups($userId, Array $groupIds)
	{
		$userId = (int)$userId;
		
		$db = $this->getAdapter();
		$db->delete($this->_name, 'user_id = '.$userId);
		
		$data = Array("user_id" => $userId);
		foreach($groupIds as $gid)
		{
			$data['group_id'] = (int)$gid;
			$db->insert($this->_name, $data);					
		}
	}
	
	
	
	
}
