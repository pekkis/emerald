<?php
/**
 * Index controller tries to find page, any page, to forward to. When it does not find,
 * it throws an exception. Maybe they could automagically be handled?!?
 *
 */
class Core_IndexController extends Emerald_Controller_Action
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
			
			$localeModel = new Core_Model_Locale();
			$page = $localeModel->startFrom($this->getCustomer(), $input->locale);

			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save');
			
			echo $message;
			die();			
			
			
			// Lets forward instead of redirecting. Url looks easier(tm).
			$this->_forward('view', 'page', null, array('id' => $page->id));
					
		} catch(Exception $e) {
			
			throw $e;
		}
				
		
	}
	
}
?>