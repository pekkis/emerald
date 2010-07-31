<?php
class EmCore_Model_PageItem extends Emerald_Model_AbstractItem implements Emerald_Acl_Resource_Interface, Emerald_Model_TaggableItemInterface
{
    public function __toString()
    {
        return $this->id;
    }

    public function getResourceId()
    {
        return 'Emerald_Page_' . $this->id;
    }



    public function getLocaleItem()
    {
        $localeModel = new EmCore_Model_Locale();
        return $localeModel->find($this->locale);
    }



    public function getLayoutObject($action = null)
    {
        $layout = Zend_Registry::get('Emerald_Customer')->getLayout($this->layout);
        if($action) {
            $layout->setAction($action);
        }
        return $layout;
    }


    public function getShardObject()
    {
        static $shardModel;
        if(!$shardModel) {
            $shardModel = new EmCore_Model_Shard();
        }
        return $shardModel->find($this->shard_id);
    }



    public function autoloadAclResource(Zend_Acl $acl)
    {
        if(!$acl->has($this)) {
            	
            if($this->parent_id) {
                $parent = "Emerald_Page_{$this->parent_id}";
            } else {
                $parent = "Emerald_Locale_{$this->locale}";
            }
            $acl->addResource($this);
            $model = new EmCore_Model_DbTable_Permission_Page_Ugroup();
            $sql = "SELECT ugroup_id, permission FROM emerald_permission_page_ugroup WHERE page_id = ?";
            $res = $model->getAdapter()->fetchAll($sql, $this->id);

            foreach($res as $row) {
                foreach(Emerald_Permission::getAll() as $key => $name) {
                    if($key & $row->permission) {
                        $role = "Emerald_Group_{$row->ugroup_id}";
                        if($acl->hasRole($role)) {
                            if($acl->isAllowed($role, $parent, 'read')) {
                                $acl->allow($role, $this, $name);
                            }
                        }
                    }
                }
            }
        }
    }

    public function getTaggable()
    {
        $taggableModel = new EmCore_Model_Taggable();
        return $taggableModel->findFor($this);
    }


    public function getTaggableId()
    {
        return $this->taggable_id;
    }


    public function setTaggableId($taggableId)
    {
        $this->taggable_id = $taggableId;
    }



    public function getType()
    {
        return 'EmCore_Model_Page';
    }




}