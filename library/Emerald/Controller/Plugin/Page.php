<?php
class Emerald_Controller_Plugin_Page extends Zend_Controller_Plugin_Abstract 
{

	
	
	
	public function dispatchLoopShutdown()
	{
		
		$filterChain = new Zend_Filter();
		$filterChain->addFilter(new Emerald_Filter_Iisiurl());
		$filterChain->addFilter(new Emerald_Filter_FileIisiurl());
		
		$body = $this->getResponse()->getBody();
		$body = $filterChain->filter($body);
				
		$this->getResponse()->setBody($body);
		
		
		
		
	}
	
	
	
}
?>