<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	public function addOptions($options)
	{
	    return $this->setOptions($this->mergeOptions($this->getOptions(), $options));
	}
		
	private $_translate;
	
	protected function _initServer() {
		
		$options = $this->getOptions();
		
		$options = $options['resources']['server'];
		
		$server = Emerald_Server::getInstance($options); 

		
		
		
		
	}

	protected function _initMisc()
	{
		$this->getResource('view')->doctype("XHTML1_TRANSITIONAL");
	}
	
	
	protected function _initFilelibPlugins()
    {
                $filelib = $this->bootstrap('filelib')->getResource('filelib');

          		// $fp = new Emerald_Filelib_Plugin_Image_ChangeFormat(array('TargetExtension' => 'jpg', 'ImageMagickOptions' => array('CompressionQuality' => 10, 'ImageFormat' => 'jpeg')));
                // $filelib->addPlugin($fp);

                // $ra = new Emerald_Filelib_Plugin_RandomizeName(array('Prefix' => 'xoo'));
                // $filelib->addPlugin($ra);

                $thumb = new Emerald_Filelib_Plugin_Image_Version(
                	array(
                		'ImageMagickOptions' => array(
							'ImageFormat' => 'png',
                		),
                		'Extension' => 'png',
                		'Identifier' => 'thumb',
                		'ScaleOptions' => array('method' => 'scaleImage', 640, 480, true)
                	)
                );
                $filelib->addPlugin($thumb);

                $mini = new Emerald_Filelib_Plugin_Image_Version(
                	array(
                		'ImageMagickOptions' => array(
							'ImageFormat' => 'png',
                		),
                		'Extension' => 'png',
                		'Identifier' => 'mini',
                		'ScaleOptions' => array('method' => 'thumbnailImage', 200, 200),
                	)
                );
                $filelib->addPlugin($mini);
                              
                $flashify = new Emerald_Filelib_Plugin_Video_Flashify(array('Extension' => 'flv', 'Identifier' => 'flash'));
				$filelib->addPlugin($flashify);
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