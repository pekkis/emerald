<?php
class EmCore_Model_GroupItem extends Emerald_Cms_Model_AbstractItem implements Emerald_Common_Acl_RoleInterface
{
    private $_users;

    public function getRoleId()
    {
        return 'Emerald_Group_' . $this->id;
    }


    public function autoloadAclRole(Zend_Acl $acl)
    {
        $acl->addRole($this, 'Emerald_Group_' . EmCore_Model_Group::GROUP_ANONYMOUS);
    }


    public function getUsers()
    {
        if(!$this->_users) {
            $model = new EmCore_Model_Group();
            $this->_users = $model->getUsersIn($this);
        }
        return $this->_users;

    }


}