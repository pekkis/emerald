<?php
/**
 * Index controller tries to find page, any page, to forward to. When it does not find,
 * it throws an exception. Maybe they could automagically be handled?!?
 *
 */
class EmCore_IndexController extends Emerald_Controller_Action
{
	/**
	 * Many come here. Some get forwarded, others get thrown as exceptions.
	 *
	 */
	public function indexAction()
	{
		$filters = array();
		$validators = array(
			'locale' => array(new Zend_Validate_Regex('([a-z]{2,3}(_[A-Z]{2})?)'), 'required' => 'false', 'allowEmpty' => true),
		);
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();
			
			$installed = $this->getCustomer()->getOption('installed');
			if(!$installed) {
				return $this->getHelper('redirector')->gotoRouteAndExit(array('action' => 'index', 'controller' => 'install', 'module' => 'em-core'), 'default', true);
			}
			
			$localeModel = new EmCore_Model_Locale();
			$page = $localeModel->startFrom($this->getCustomer(), $input->locale);
						
			
			// Lets forward instead of redirecting. Url looks easier(tm).
			$this->_forward('view', 'page', 'em-core', array('id' => $page->id));
					
		} catch(Exception $e) {
			
			throw $e;
		}
				
		
	}
	
}
?>