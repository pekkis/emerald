<?php
interface Filelib_Model_Acl_Interface
{
	
	public function isReadable($resource);

	public function isWriteable($resource);
	
	public function isAnonymousReadable($resource);
	
	
}