<?php
class Filelib_Model_Acl_Zend implements Filelib_Model_Acl_Interface
{

	private $_acl;
	
	private $_anonymousRole;
		
	public function setAcl(Zend_Acl $acl)
	{
		$this->_acl = $acl;
	}
	
	
	/**
	 * @return Zend_Acl
	 */
	public function getAcl()
	{
		return $this->_acl;
	}
	
	
	public function setRole($role)
	{
		$this->_role = $role;
	}
	
	
	public function getRole()
	{
		return $this->_role;
	}
	
	
	public function setAnonymousRole($anonymousRole)
	{
		$this->_anonymousRole = $anonymousRole;
	}
	
	
	public function getAnonymousRole()
	{
		return $this->_anonymousRole;
	}
	
	
	
	public function isReadable($resource)
	{
		return $this->getAcl()->isAllowed($this->getRole(), $resource, 'read');
	}
	
	
	public function isWriteable($resource)
	{
		return $this->getAcl()->isAllowed($this->getRole(), $resource, 'read');
	}

	
	public function isAnonymousReadable($resource)
	{
		return $this->getAcl()->isAllowed($this->getAnonymousRole(), $resource, 'read');
	}
	
	
	
	
	
}