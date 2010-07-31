<?php
interface Emerald_Filelib_Acl_Interface
{

    public function isReadable($resource);

    public function isWriteable($resource);

    public function isAnonymousReadable($resource);


}