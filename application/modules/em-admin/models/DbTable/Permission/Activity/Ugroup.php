<?php
class EmAdmin_Model_DbTable_Permission_Activity_Ugroup extends Zend_Db_Table_Abstract
{
    protected $_name = 'emerald_permission_activity_ugroup';
    protected $_primary = array('activity_id', 'ugroup_id');

}
