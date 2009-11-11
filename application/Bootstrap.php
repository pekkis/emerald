<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	public function addOptions($options)
	{
	    return $this->setOptions($this->mergeOptions($this->getOptions(), $options));
	}
		
	private $_translate;

	
	protected function _initFilelib()
	{
		$filelib = new Filelib_Model_Filelib();
		$filelib->setRoot("/wwwroot/emerald/customers/default/files");
		$filelib->setPublicRoot("/wwwroot/emerald/customers/default/public/files");
		$filelib->setPublicDirectoryPrefix("/files");
		$filelib->setDb($this->getResource('db'));
		$filelib->setAcl($this->getResource('acl'));
		$filelib->setMagic("/usr/share/file/magic");
		
		Zend_Registry::set('Emerald_Filelib', $filelib);
		
	}
	
	
	
	protected function _initCustomer()
	{
		$this->bootstrap('frontcontroller');
		
		$server = Emerald_Server::getInstance();
		$customer = $server->findCustomer($_SERVER['HTTP_HOST']);
						
		$front = $this->getResource('frontcontroller');
		
		$config = $customer->getConfig();
		
		$config = $config->toArray();
		
		$this->addOptions($config);
		
		try {
		   	$front->addModuleDirectory($customer->getRoot() . '/modules');	
	    } catch(Exception $e) {
	       	// There aint no customer specific modules
	    }
		
	    Zend_Registry::set('Emerald_Customer', $customer);
	    
		return $customer;
	}
	
	public function _initAcl()
	{
		$this->bootstrap('customer')->bootstrap('db');
		
		$customer = $this->getResource('customer');
		
		$acl = new Zend_Acl(); 
        Zend_Registry::set('Emerald_Acl', $acl);
        
		Emerald_Acl::initialize($acl, $customer); 
                                
        return $acl;
		
		
	}
	
	
	
	
	protected function _innitCache()
	{
		// If we cache, we cache table metadata.
        if($app->getConfig()->cache == 'true') {
        	$cache = Emerald_Cache::getGeneric(); 
        	Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
        }        
		
	}
	
	
	protected function _initUser()
	{
		$auth = Zend_Auth::getInstance();
						
		$db = $this->getResource('db');
				
		$userTbl = Emerald_Model::get('User');
		
		if($auth->hasIdentity()) {
			$id = $auth->getIdentity();
			$user = $userTbl->find($id)->current();
		} else {
			$userId = Emerald_User::USER_ANONYMOUS;
			$user = $userTbl->find($userId);
			if(!$user = $user->current()) {
				throw new Emerald_Exception('Something wrong with ur user');
			}
			$auth->getStorage()->write($user->id);
		}
				
		Zend_Registry::set('Emerald_User', $user);
		
		return $user;		
		
	}
	
	
	
	
	
}