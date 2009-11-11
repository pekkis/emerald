<?php
class Filelib_Model_Acl_Simple implements Filelib_Model_Acl_Interface
{

	public function isReadable($resource)
	{
		return true;
	}
	
	
	public function isWriteable($resource)
	{
		return true;
	}

	
	public function isAnonymousReadable($resource)
	{
		return true;
	}
	
	
	
	
	
}