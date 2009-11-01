<?php
class Emerald_Application_Resource_View extends Zend_Application_Resource_View
{
	
	
	public function init()
	{
		$view = parent::init();
		
		// $view = new Emerald_View(array('encoding' => 'UTF-8'));
        $view->getHelper('headMeta')->appendName('Generator', 'Emerald Content Management Server');
        $view->getHelper('headMeta')->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        
        $view->addHelperPath(dirname(__FILE__).'/View/Helper', 'Emerald_View_Helper');
		$view->addHelperPath($this->getBootstrap()->getResource('customer')->getRoot() . '/application/helpers', 'Emerald_View_Helper');
                        
        // $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        // $viewRenderer->setView($view);
		
		return $view;
		
	}
	
	
	
   /**
     * Retrieve view object
     * 
     * @return Zend_View
     */
    public function getView()
    {
            	    	
    	if (null === $this->_view) {
            $this->_view = new Emerald_View($this->getOptions());
        }
        return $this->_view;
    }
}