<?php
class EmCore_Model_User extends Emerald_Cms_Model_AbstractModel
{
    const USER_ANONYMOUS = 0;


    static private $_hashAlgorithm = 'sha512';

    static private $_hashSalt = '';

    protected static $_table = 'EmCore_Model_DbTable_User';

    static public function setHashAlgorithm($algo)
    {
        self::$_hashAlgorithm = $algo;
    }


    static public function setHashSalt($salt)
    {
        self::$_hashSalt = $salt;
    }


    public function hash($context, $password)
    {
        if(is_object($context)) {
            $context = $context->email;
        } elseif(!is_string($context)) {
            throw new Emerald_Common_Exception('Invalid context for user password hashing');
        }

        return hash(self::$_hashAlgorithm, self::$_hashSalt . $context . $password);
    }

    public function findAll()
    {
        $rows = $this->getTable()->fetchAll(array(), 'email ASC');
        $iter = new ArrayIterator();
        foreach($rows as $row) {
            $iter->append(new EmCore_Model_UserItem($row));
        }
        return $iter;
    }


    public function find($id)
    {
        $tbl = $this->getTable();
        $row = $tbl->find($id)->current();
        return ($row) ? new EmCore_Model_UserItem($row->toArray()) : false;
    }


    public function findAnonymous()
    {
        $user = new EmCore_Model_UserItem();
        $user->id = EmCore_Model_User::USER_ANONYMOUS;
        $user->setGroups(array(new EmCore_Model_GroupItem(array('id' => EmCore_Model_Group::GROUP_ANONYMOUS))));

        return $user;
    }


    public function getGroupsFor(EmCore_Model_UserItem $user)
    {
        $tbl = new EmCore_Model_DbTable_Ugroup();
        $sel = $tbl->getAdapter()->select()->from("emerald_ugroup", "*");
        $sel->join('emerald_user_ugroup', "emerald_ugroup.id = emerald_user_ugroup.ugroup_id AND emerald_user_ugroup.user_id = {$user->id}", null);


        $res = $tbl->getAdapter()->fetchAll($sel, null, Zend_Db::FETCH_ASSOC);

        $groups = array();
        foreach($res as $row) {
            $groups[] = new EmCore_Model_GroupItem($row);
        }

        return $groups;


    }


    public function save(EmCore_Model_UserItem $user)
    {

        if(!is_numeric($user->id)) {
            $user->id = null;
        }

        $tbl = $this->getTable();

        $row = $tbl->find($user->id)->current();
        if(!$row) {
            $row = $tbl->createRow();
            $row->setFromArray($user->toArray());
            $row->passwd = $this->hash($user, uniqid());
        } else {
            $row->setFromArray($user->toArray());
        }

        $row->setFromArray($user->toArray());
        $row->save();

        $acl = Zend_Registry::get('Emerald_Acl');
        if($acl->hasRole($user)) {
            $acl->removeRole($user);
        }


        $user->id = $row->id;

    }


    public function delete(EmCore_Model_UserItem $user)
    {
        $tbl = $this->getTable();
        $row = $tbl->find($user->id)->current();
        if(!$row) {
            throw new Emerald_Cms_Model_Exception('Could not delete');
        }

        $row->delete();

        $acl = Zend_Registry::get('Emerald_Acl');
        if($acl->hasRole($user)) {
            $acl->removeRole($user);
        }


    }



    public function setPassword(EmCore_Model_UserItem $user, $password)
    {
        $password = $this->hash($user, $password);
        $user->passwd = $password;
        $this->save($user);
    }


    public function setGroups(EmCore_Model_UserItem $user, $groups)
    {

        $tbl = new EmCore_Model_DbTable_UserGroup();
        	
        $tbl->getAdapter()->beginTransaction();

        $tbl->delete($tbl->getAdapter()->quoteInto("user_id = ?", $user->id));

        if($groups) {
            foreach($groups as $key => $groupId) {
                $tbl->insert(array('user_id' => $user->id, 'ugroup_id' => $groupId));
            }
        }

        $tbl->getAdapter()->commit();

        $acl = Zend_Registry::get('Emerald_Acl');
        if($acl->hasRole($user)) {
            $acl->removeRole($user);
        }
        	


    }



    public function authenticate($identity, $credential)
    {
        $auth = Emerald_Cms_Auth::getInstance();

        $adapter = new Zend_Auth_Adapter_DbTable($this->getTable()->getAdapter(), 'emerald_user', 'email', 'passwd', '? and status = 1');
        $adapter->setIdentity($identity);


        $adapter->setCredential($this->hash($identity, $credential));
        	
        $result = $auth->authenticate($adapter);
        if($result->isValid()) {
            $userModel = new EmCore_Model_User();
            $user = $userModel->find($adapter->getResultRowObject()->id);
            $auth->getStorage()->write($user);
            	
            return true;
            	
        }

        return false;
    }



}