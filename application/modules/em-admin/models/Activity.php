<?php
class EmAdmin_Model_Activity extends Emerald_Model_Cacheable
{
    protected static $_table = 'EmAdmin_Model_DbTable_Activity';

    /**
     * Returns table
     *
     * @return Zend_Db_Table_Abstract
     */
    public function getPermissionTable()
    {
        static $table;
        if(!$table) {
            $table = new EmAdmin_Model_DbTable_Permission_Activity_Ugroup();
        }
        return $table;
    }


    public function getActivities()
    {
        $res = $this->getTable()->fetchAll(array(), array('category ASC', 'name ASC'));

        $activities = array();
        foreach($res as $row) {
            $activities[] = new EmAdmin_Model_ActivityItem($row->toArray());
        }

        return new ArrayIterator($activities);
    }



    public function getActivitiesByCategory()
    {
        $cats = array();
        foreach($this->getActivities() as $activity) {
            $cats[$activity->category][] = $activity;
        }
        return $cats;

    }


    public function getPermissions(EmAdmin_Model_ActivityItem $activity)
    {
        $acl = Zend_Registry::get('Emerald_Acl');

        $groupModel = new EmCore_Model_Group();
        $groups = $groupModel->findAll();
        foreach($groups as $group) {
            if($acl->isAllowed($group, $activity)) {
                $perms[] = $group->id;
            }
        }
        return $perms;

    }


    public function updatePermissions($activityPermissions)
    {
        $tbl = $this->getPermissionTable();


        foreach($activityPermissions as $id => $groups) {
            	
            $activity = $this->find($id);
            	
            $tbl->delete($tbl->getAdapter()->quoteInto("activity_id = ?", $id));
            	
            foreach($groups as $groupId) {
                $tbl->insert(array('activity_id' => $id, 'ugroup_id' => $groupId));
            }

            $acl = Zend_Registry::get('Emerald_Acl');
            if($acl->has($activity)) {
                $acl->remove($activity);
            }
            	
            	
        }



    }


    public function findByCategoryAndName($category, $name)
    {
        if(!$ret = $this->findCached(array($category, $name))) {
            	
            $res = $this->getTable()->fetchRow(array('category = ?' => $category, "name = ?" => $name));
            $ret = ($res) ? new EmAdmin_Model_ActivityItem($res->toArray()) : false;
            	
            if($ret) {
                $this->storeCached(array($ret->category, $ret->name), $ret);
            }
        }

        return $ret;

    }


    /**
     * Finds item with primary key
     *
     * @param $id
     * @return EmAdmin_Model_ActivityItem
     */
    public function find($id)
    {
        if(!$ret = $this->findCached($id)) {
            $tbl = $this->getTable();
            $row = $tbl->find($id)->current();
            $ret = ($row) ? new EmAdmin_Model_ActivityItem($row->toArray()) : false;
            if($ret) {
                $this->storeCached($id, $ret);
            }
        }
        return $ret;
    }


    public function getCacheIdentifier($id)
    {
        if(is_array($id)) {
            $id = implode('_', $id);
        }

        $filter = new Zend_Filter_Word_SeparatorToCamelCase();

        return $this->_cachePrefix . '_' . $filter->filter($id);
    }



}
