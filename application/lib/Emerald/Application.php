<?php
/**
 * Emerald application
 *
 */
class Emerald_Application
{
    
    /**
     * Customer info
     *
     * @var Emerald_Customer
     */
    private $_customer;
    
    
    /**
     * User object
     *
     * @var Emerald_User
     */
    private $_user;
    
    /**
     * Access Control List
     *
     * @var Zend_Acl
     */
    private $_acl;
    
    
    /**
     * Returns session
     *
     * @var Zend_Db_Table_Row
     */
    private $_session;
        
    /**
     * Returns translator
     *
     * @var unknown_type
     */
    private $_translate;
    
    
    /**
     * App settings
     *
     * @var Emerald_Options_Application
     */
    private $_options;
        
    
    /**
     * The final word in locales is the setted locale of the Emerald app itself.
     *
     * @var Zend_Locale
     */
    private $_locale;
    
    
    
    /**
     * Get singleton
     *
     * @return Emerald_Application
     */
    public static function getInstance()
    {
        static $instance;
        if(!$instance) {
            $instance = new self();
        }
        return $instance;  
    }
    
    
    
    /**
     * Runs the application, hopefully poops some output to screen!
     *
     * @return bool true on success
     * 
     */
    public function run()
    {
    	
        if(!$this->getCustomer()) {
        	throw new Emerald_Exception('Customer not found');
        }
        
        $db = $this->getDb();
        if(!Emerald_Server::getInstance()->inProduction() && Emerald_Server::getInstance()->getConfig()->profiler === 'true') {
        	$profiler = new Zend_Db_Profiler(true);
        	$db->setProfiler($profiler);        	        	
        }
        
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        Zend_Db_Table_Abstract::setDefaultAdapter($db);
        
		// If we cache, we cache table metadata.
        if($this->getConfig()->cache == 'true') {
        	$cache = Emerald_Cache::getGeneric(); 
        	Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
        }        
		
        // Skidily kei but hey, whatchagonnado... 
        $this->getDb()->getConnection()->exec("SET names utf8");
    	        
        $this->_options = new Emerald_Options_Application($this->getCustomer());
        
        // TODO: ACL must be cached, cached and CACHED!
        $this->_acl = new Zend_Acl(); 
        Emerald_Acl::initialize($this->_acl); 
        
        if(Zend_Session::sessionExists()) {
        	$this->initializeSession();
			$userId = $this->getSession()->user_id;
        } else {
        	$userId = Emerald_User::USER_ANONYMOUS;
        }
		
		// Fetch user object and store it.
		$userTbl = Emerald_Model::get('User');
		$user = $userTbl->find($userId);
		if(!$user = $user->current()) {
			throw new Emerald_Exception('Something wrong with ur user');
		}
		
		$this->_user = $user;
		$this->setLocale($user->getOption('locale') ? $user->getOption('locale') : 'en');
		
		// Delete obsolete sessions

		$time = new DateTime();
		$sessionTbl = Emerald_Model::Get('Session');		
		$time->modify('-1 hours');
		$where = $sessionTbl->getAdapter()->quoteInto('refreshed <= ?', array($time->format('Y-m-d H:i:s')));
		$sessionTbl->delete($where);		
		
    	Zend_Json::$useBuiltinEncoderDecoder = true;
                        
        $view = new Emerald_View(array('encoding' => 'UTF-8'));
        $view->getHelper('headMeta')->appendName('Generator', 'Emerald Content Management Server');
        $view->getHelper('headMeta')->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        $view->addHelperPath(dirname(__FILE__).'/View/Helper', 'Emerald_View_Helper');
		$view->addHelperPath($this->getCustomer()->getRoot() . '/application/helpers', 'Emerald_View_Helper');
                        
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
                
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
        
        Zend_Layout::startMvc();	
        Zend_layout::getMvcInstance()->disableLayout();
        
        $front = Zend_Controller_Front::getInstance();
        // $front->registerPlugin(new Emerald_Controller_Plugin_Filter());
                
        
        Zend_Registry::set('Zend_Translate', $this->getTranslate());
        
        $response = $front->dispatch();
		return $response;
        
    }
    
    
    
    /**
     * Returns config
     *
     * @return Zend_Config_Xml
     */
    public function getConfig()
    {
    	return $this->getCustomer()->getConfig();
    }
    
    
    
    
    /**
     * Returns DB instance
     *
     * @return Zend_Db_Adapter_Pdo_Mysql
     */
    public function getDb()
    {
    	return $this->getCustomer()->getDb();
    }
    
    
    
    /**
     * Returns customer 
     *
     * @return Emerald_Application_Customer
     */
    public function getCustomer()
    {
    	return $this->_customer;
    }
    
    
    
    /**
     * Sets customer
     *
     * @param Emerald_Application_Customer $customer
     */
    public function setCustomer(Emerald_Application_Customer $customer)
    {
    	$this->_customer = $customer;
    }
    
    
    
    /**
     * Returns customers acl
     *
     * @return Zend_Acl
     */
    public function getAcl()
    {
    	return $this->_acl;
    }
    
    /**
     * Returns session
     *
     * @return Zend_Db_Table_Row
     */
    public function getSession()
    {
    	return $this->_session;
    }
    
    /**
     * Returns user
     *
     * @return Emerald_User
     */
    public function getUser()
    {
    	return $this->_user;
    }
    
    
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
    
    
    
    /**
     * Gets app option
     *
     * @param string $key Key
     * @param mixed $default Returned if not found
     * @return mixed
     */
    public function getOption($key)
    {
    	return $this->_options->get($key);
    }
    
    
    /**
     * Sets app option
     *
     * @param string $key Key
     * @param mixed $value Value
     * @return boolean Success or not
     */
    public function setOption($key, $value)
    {
    	return $this->_options->set($key, $value);
    }
    

    /**
     * Returns locale
     *
     * @return Zend_Locale
     */
    public function getLocale()
    {
    	return $this->_locale;
    }
    
    
    
    /**
     * Sets locale
     *
     * @param mixed $locale Locale name or Zend_Locale object
     */
    public function setLocale($locale)
    {
    	if(!$locale instanceof Zend_Locale)
    		$locale = new Zend_Locale($locale);
    		
    	$this->_locale = $locale;
    	
    }
    
    
    public function initializeSession()
    {
    	static $initialized;

    	$time = new DateTime();
    	
    	if($initialized)
	    	throw new Emerald_Exception('Session already initialized', 500);

	    $sessionTbl = Emerald_Model::Get('Session');
	    	
    	Zend_Session::start();
		$id = Zend_Session::getId();
		// $session = new Zend_Session_Namespace('user', true);
				
		// Find existing user for session id or initialize a new anonymous one. 
		
		$session = $sessionTbl->find($id);
		if(!$session = $session->current()) {
			$session = $sessionTbl->createRow();
			$session->id = Zend_Session::getId();
			$session->user_id = Emerald_User::USER_ANONYMOUS;
		}
		$session->refreshed = $time->format('Y-m-d H:i:s');
		$session->save();
		$this->_session = $session;

		$initialized = true;	

						
    }
    
    
}
