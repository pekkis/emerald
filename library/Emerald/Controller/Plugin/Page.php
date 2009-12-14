<?php
class Emerald_Controller_Plugin_Page extends Zend_Controller_Plugin_Abstract 
{

	
	
	
	
	public function dispatchLoopShutdown()
	{
		
		$filterChain = new Zend_Filter();
		$filterChain->addFilter(new Emerald_Filter_Beautifurl());
		// $filterChain->addFilter(new Emerald_Filter_FileBeautifurl());
		
		$body = $this->getResponse()->getBody();
		$body = $filterChain->filter($body);
				
		$this->getResponse()->setBody($body);
		
		
		
		
	}
	
	
	
}
?>