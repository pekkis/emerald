<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	public function addOptions($options)
	{
	    return $this->setOptions($this->mergeOptions($this->getOptions(), $options));
	}
		
	private $_translate;
	

	protected function _initMisc()
	{
		$this->getResource('view')->doctype("XHTML1_TRANSITIONAL");
		// Zend_Registry::set('Emerald_CacheManager', $this->getResource('cachemanager'));
		
		$this->getResource('frontcontroller')->returnResponse(true);
		
		$client = new Zend_Http_Client();
		
		$client->setMethod(Zend_Http_Client::POST);
		$client->setParameterPost('admin', 'true');
		
	}
	
	
	protected function _initFilelibPlugins()
    {
		$filelib = $this->bootstrap('filelib')->getResource('filelib');
		$acl = $this->bootstrap('acl')->getResource('acl');
		$user = $this->bootstrap('user')->getResource('user');
		                
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
		
		$aclHandler = new Emerald_Filelib_Acl_Zend();
		$aclHandler->setAcl($acl);
		$aclHandler->setAnonymousRole("Emerald_Group_" . Core_Model_Group::GROUP_ANONYMOUS);
				
		$aclHandler->setRole($user);
		
		$filelib->setAcl($aclHandler);
		
		$filelib->setFileItemClass("Core_Model_FileItem");
		$filelib->setFolderItemClass("Core_Model_FolderItem");		
		
    }
	
	
	
	
	
	public function _initAcl()
	{
		$this->bootstrap('customer')->bootstrap('db');
		
		$customer = $this->getResource('customer');
		
		$cache = Zend_Registry::get('Emerald_CacheManager')->getCache('default');
		if(!$acl = $cache->load('acl')) {
			$acl = new Emerald_Acl(); 
			Emerald_Acl::initialize($acl, $customer);
			$cache->save($acl, 'acl'); 
		}
		Zend_Registry::set('Emerald_Acl', $acl);
                                
        return $acl;
		
		
	}
	
	
	
	
	protected function _initUser()
	{
						
		$auth = Zend_Auth::getInstance();
				
		$userModel = new Core_Model_User();
		
		if($auth->hasIdentity()) {
			$user = $auth->getIdentity();
		} else {
			$user = $userModel->findAnonymous();
			if(!$user) {
				throw new Emerald_Exception('Something wrong with ur user');
			}
			$auth->getStorage()->write($user);
		}
				
		Zend_Registry::set('Emerald_User', $user);
		
		return $user;		
		
	}
	
	
	
	
	
}