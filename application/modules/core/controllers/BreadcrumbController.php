<?php
class Core_BreadcrumbController extends Emerald_Controller_Action 
{
	

	
	public function indexAction()
	{

		$filters = array(
		);
		$validators = array(
			'page' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
			'separator' => array(new Zend_Validate_StringLength(1, 15), 'presence' => 'required')
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			$input->page->assertReadable($this->getCurrentUser());
			
			$route = $input->page->getRoute();	
			$this->view->route = $route;
			$this->view->separator = $input->getUnescaped('separator');
		} catch(Exception $e) {
			throw $e;
		}
			
	}
	
	
	
	
}