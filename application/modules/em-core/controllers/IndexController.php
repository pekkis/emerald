<?php
class EmCore_IndexController extends Emerald_Controller_Action
{

	public function indexAction()
	{
		$filters = array();
		$validators = array(
			'locale' => array(new Zend_Validate_Regex('([a-z]{2,3}(_[A-Z]{2})?)'), 'required' => 'false', 'allowEmpty' => true),
		);
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();

			// @todo: Can this be moved to error controller?
			$installed = $this->getCustomer()->getOption('installed');
			if(!$installed) {
				return $this->getHelper('redirector')->gotoRouteAndExit(array('action' => 'index', 'controller' => 'install', 'module' => 'em-core'), 'default', true);
			}
			
			$localeModel = new EmCore_Model_Locale();
			$page = $localeModel->startFrom($this->getCustomer(), $input->locale);

			$this->getHelper('redirector')->gotoUrlAndExit('/' . $page->beautifurl);
												
		} catch(Exception $e) {
			throw $e;
		}
				
		
	}
	
}
?>