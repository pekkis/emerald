<?php
class Emerald_Controller_Plugin_Filter extends Zend_Controller_Plugin_Abstract 
{

	
	
	
	public function dispatchLoopShutdown()
	{
		$response = $this->getResponse();
		
		$body = $response->getBody();
        $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Emerald_Filter_Langlib(Emerald_Application::getInstance()->getLocale()));

        $body = $filterChain->filter($body);
				
		$response->setBody($body);
		
	}
	
	
	
}
