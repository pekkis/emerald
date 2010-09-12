<?php
class EmCore_Model_FileItem extends Emerald_Filelib_FileItem implements Emerald_Acl_ResourceInterface
{


    public function getResourceId()
    {
        return "Emerald_Filelib_File_{$this->id}";
    }



    public function autoloadAclResource(Zend_Acl $acl)
    {
        $acl->add($this);
        return;     
        
        
        if(!$acl->has($this)) {
            $folder = $this->findFolder();
            if(!$acl->has($folder)) {
                $folder->autoloadAclResource($acl);
            }
            $acl->addResource($this, $folder);
        }
    }


}