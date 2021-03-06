<?php
class EmCore_Model_DbTable_UserGroup extends Zend_Db_Table_Abstract
{
    protected $_name = 'emerald_user_ugroup';
    protected $_primary = array('user_id', 'group_id');

    protected $_referenceMap    = array(
        'User' => array(
            'columns'           => array('user_id'),
            'refTableClass'     => 'EmCore_Model_DbTable_User',
            'refColumns'        => array('id')
    ),
        'Group' => array(
            'columns'           => array('group_id'),
            'refTableClass'     => 'EmCore_Model_DbTable_Ugroup',
            'refColumns'        => array('id')
    )
    );






}
