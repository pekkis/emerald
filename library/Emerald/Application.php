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
        throw new Exception('Obsolete');
    	
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
    	
		        
        // $response = $front->dispatch();
		// return $response;
        
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
    	throw new Exception('Obso');
    	
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
