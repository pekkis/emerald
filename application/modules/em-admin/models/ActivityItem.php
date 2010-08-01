<?php
class EmAdmin_Model_ActivityItem extends Emerald_Model_AbstractItem implements Emerald_Acl_ResourceInterface
{
    static private $_filter;

    static public function getFilter()
    {
        if(!self::$_filter) {
            $filter = new Zend_Filter();
            $filter->addFilter(new Zend_Filter_StringToLower('UTF-8'));
            $filter->addFilter(new Zend_Filter_Word_SeparatorToSeparator(' ', '_'));
            	
            self::$_filter = $filter;
        }

        return self::$_filter;
    }




    public function getResourceId()
    {
        return 'Emerald_Activity_' . $this->category . '___' . $this->name;
    }



    public function autoloadAclResource(Zend_Acl $acl)
    {

        if(!$acl->has($this)) {
            $acl->addResource($this);
            $model = new EmCore_Model_DbTable_Permission_Page_Ugroup();
            $sql = "SELECT ugroup_id FROM emerald_permission_activity_ugroup WHERE activity_id = ?";
            $res = $model->getAdapter()->fetchAll($sql, $this->id);
            foreach($res as $row) {
                $role = "Emerald_Group_{$row->ugroup_id}";
                	
                if(!$acl->hasRole($role)) {
                    $acl->addRole($role);
                }

                $acl->allow($role, $this);
            }
        }
    }




}
