<?php
class EmeraldAdmin_HelpController extends Emerald_Controller_AdminAction 
{

	
	public function contextAction()
	{
		$filters = Array();
		$validators = array(
			'c' => Array
			(
				'allowEmpty' => false,
				'presence' => 'required'
			),
			'a' => Array
			(
				'allowEmpty' => false,
				'presence' => 'required'
			)
		);
		try
		{
			$filtered = new Zend_Filter_Input(array(), $validators, $this->_getAllParams());
			$filtered->process();
		}
		catch(Exception $e)
		{
			throw new Emerald_Exception('Not Found', 404);
		}
		$siteUrl = Emerald_Server::getInstance()->getConfig()->help->siteRoot;
		$path = "{$siteUrl}/fi_FI/context_help/{$filtered->c}"; // dynamic locale later
		if($filtered->a != "index") $path .= "/{$filtered->a}";
		$this->_redirect($path);
	}
	
	
	

	
	public function indexAction()
	{
		
		
	}
	
}
?>