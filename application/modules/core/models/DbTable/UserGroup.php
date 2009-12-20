<?php
class Core_Model_UserGroup extends Zend_Db_Table_Abstract 
{
	protected $_name = 'user_ugroup';
	protected $_primary = array('user_id', 'group_id');
	
	protected $_referenceMap    = array(
        'User' => array(
            'columns'           => array('user_id'),
            'refTableClass'     => 'Core_Model_DbTable_User',
            'refColumns'        => array('id')
        ),
        'Group' => array(
            'columns'           => array('group_id'),
            'refTableClass'     => 'Core_Model_DbTable_Ugroup',
            'refColumns'        => array('id')
        )
    );
    
	
	
	
	
	
}
