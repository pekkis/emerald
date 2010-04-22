<?php
class Emerald_Application_Resource_Emacl extends Zend_Application_Resource_ResourceAbstract
{

	public function init()
	{
		
		$this->getBootstrap()->bootstrap('customer')->bootstrap('db')->bootstrap('filelib');
		
		$customer = $this->getBootstrap()->getResource('customer');
		
		$cache = Zend_Registry::get('Emerald_CacheManager')->getCache('default');
		if(!$acl = $cache->load('acl')) {
			$acl = new Emerald_Acl(); 
			Emerald_Acl::initialize($acl, $customer);
			$cache->save($acl, 'acl'); 
		}
		
		$filelib = $this->getBootstrap()->getResource('filelib');
		// $acl = $this->getBootstrap()->getResource('acl');
		
		// $filelib = $this->getBootstrap()->bootstrap('filelib')->getResource('filelib');
		// $acl = $this->getBootstrap()->bootstrap('acl')->getResource('acl');
		$user = $this->getBootstrap()->bootstrap('emuser')->getResource('emuser');
		                
		// $fp = new Emerald_Filelib_Plugin_Image_ChangeFormat(array('TargetExtension' => 'jpg', 'ImageMagickOptions' => array('CompressionQuality' => 10, 'ImageFormat' => 'jpeg')));
		// $filelib->addPlugin($fp);
				
		// $ra = new Emerald_Filelib_Plugin_RandomizeName(array('Prefix' => 'xoo'));
		// $filelib->addPlugin($ra);
		                              
		
		
		$aclHandler = new Emerald_Filelib_Acl_Zend();
		$aclHandler->setAcl($acl);
		$aclHandler->setAnonymousRole("Emerald_Group_" . EmCore_Model_Group::GROUP_ANONYMOUS);
				
		$aclHandler->setRole($user);
		
		$filelib->setAcl($aclHandler);
		
		$filelib->setFileItemClass("EmCore_Model_FileItem");
		$filelib->setFolderItemClass("EmCore_Model_FolderItem");		
		
		
		Zend_Registry::set('Emerald_Acl', $acl);
                                
        return $acl;
		
		
	}
	
}