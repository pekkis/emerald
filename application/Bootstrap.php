<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	public function addOptions($options)
	{
	    return $this->setOptions($this->mergeOptions($this->getOptions(), $options));
	}
	
	
	private $_translate;
	    
    /**
     * Returns translator
     *
     * @return Zend_Translate
     */
    public function getTranslate()
    {
    	if(!$this->_translate) {
    		
    		if(Emerald_Server::getInstance()->getConfig()->cache == 'true') {
				Zend_Translate::setCache(Emerald_Cache::getGeneric());	
			}		
			$this->_translate = new Zend_Translate('Emerald_Translate_Adapter_Langlib', Emerald_Server::getInstance()->getDb(), 'en');
			// If we have langlib cached, we don't poop languages to it.
			if(!$this->_translate->getList()) {
				$this->_translate->addTranslation(Emerald_Server::getInstance()->getDb(), 'fi');
				$this->_translate->addTranslation(Emerald_Server::getInstance()->getDb(), 'en');
			}
    	}
    	return $this->_translate;
    	
    }
	
	
	
	protected function _initCustomer()
	{
		$this->bootstrap('puuppa');
		$this->bootstrap('frontcontroller');
		
		$server = Emerald_Server::getInstance();
		$customer = $server->findCustomer(Zend_Controller_Front::getInstance()->getRequest());
						
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
	
	
	
	protected function _initRouter()
	{
		$bootstrap = $this;
		$bootstrap->bootstrap('frontcontroller');
		$front = $bootstrap->getContainer()->frontcontroller;
				
		// TODO: Where do the routes semantically go? To the server? App?
	    $router = new Zend_Controller_Router_Rewrite();
	    $router->addDefaultRoutes();
	        
		$router->addRoute('locale',
		new Zend_Controller_Router_Route_Regex(
		'([a-z]{2,3}(_[A-Z]{2})?)',
		array('module' => 'emerald' , 'controller' => 'index', 'action' => 'index'),
		array(1 => 'locale')
		)
		);
	        
		$router->addRoute('iisiurl',
		new Zend_Controller_Router_Route_Regex(
		'(([a-z]{2,3}(_[A-Z]{2})?)/(.*?))(\.(html|xml))?$',
		array('module' => 'emerald', 'controller' => 'page', 'action' => 'view', 'render_type' => 'html'),
		array(1 => 'iisiurl', 6 => 'render_type')
		)
		);
	        
		$router->addRoute('loginlocale',
		new Zend_Controller_Router_Route_Regex(
		'login(/([a-z]{2,3}))?',
		array('module' => 'emerald', 'controller' => 'login', 'action' => 'index', 'locale' => 'en_US'),
		array(2 => 'locale')
		));        
	    
		$front->setRouter($router);
				
		return $router;
		
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
		// $adapter = new Zend_Auth_Adapter_DbTable($db, 'user', 'id', 'passwd', "MD5(?) AND status = 1");
				
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
	
	
	
	
	protected function _initPuuppa()
	{
		$this->bootstrap('modules');
								
		$server = Emerald_Server::getInstance();
		date_default_timezone_set($server->getConfig()->timezone);
			
		$request = new Zend_Controller_Request_Http();
		$response = new Zend_Controller_Response_Http();
		
		$front = Zend_Controller_Front::getInstance();
	    $front->setRequest($request);
	    $front->setResponse($response);
				    	    
			        
	    $front->throwExceptions(false);
	    
	    

	    // Zend_Json::$useBuiltinEncoderDecoder = true;
	    
	      Zend_Layout::startMvc();	
        Zend_layout::getMvcInstance()->disableLayout();
        
        
        $front = Zend_Controller_Front::getInstance();
        // $front->registerPlugin(new Emerald_Controller_Plugin_Filter());
                
        
        Zend_Registry::set('Zend_Translate', $this->getTranslate());
		
        
	    
		
		
	}
	
	
	
	
}