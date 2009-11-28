<?php
class Core_HtmlcontentController extends Emerald_Controller_Action 
{

	public function init()
	{
		$contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch->addActionContext('index', array('json'))
                      ->initContext();
	}
	
	public function indexAction()
	{
		$filters = array(
			'page' => array(new Emerald_Filter_PageIdToPage()),
		);
		$validators = array(
			'page' => array(new Emerald_Validate_InstanceOf('Emerald_Page'), 'presence' => 'optional', 'allowEmpty' => true),
			'block_id' => array('Int', 'presence' => 'required'),
			'onEmpty' => array(new Zend_Validate_StringLength(0, 255), 'allowEmpty' => true, 'presence' => 'optional', 'default' => ''),	
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			$input->page->assertReadable($this->getCurrentUser());
			
			$writable = Zend_Registry::get('Emerald_Acl')->isAllowed($this->getCurrentUser(), $input->page, 'write');
			$this->view->writable = $writable;
						
			$htmlcontentTbl = Emerald_Model::get('Htmlcontent');
			$htmlcontent = $htmlcontentTbl->find($input->page->id, $input->block_id);
											
			$this->view->content = ($htmlcontent = $htmlcontent->current()) ? $htmlcontent->content : $input->onEmpty;
			$this->view->block_id = $input->block_id;
			$this->view->page = $input->page;		
			
		} catch(Exception $e) {
			
			echo $e;
			die();
			
			throw $e;
			
			
		}
		
	}
	
	
	
	
}