<?php
class EmCore_Model_FileItem extends Emerald\Filelib\FileItem implements Emerald_Common_Acl_ResourceInterface
{


    public function getResourceId()
    {
        return "Emerald_Filelib_File_{$this->id}";
    }



    public function autoloadAclResource(Zend_Acl $acl)
    {
        if(!$acl->has($this)) {
            $folder = $this->findFolder();
            if(!$acl->has($folder)) {
                $folder->autoloadAclResource($acl);
            }
            $acl->addResource($this, $folder);
        }
    }


}