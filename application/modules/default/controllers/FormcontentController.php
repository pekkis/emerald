<?php
class FormcontentController extends Emerald_Controller_Action 
{

	
	public function indexAction()
	{
		$filters = array(
		);
		$validators = array(
			'page' => array(new Emerald_Validate_InstanceOf('Emerald_Page'), 'presence' => 'optional', 'allowEmpty' => true),
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
				
			$page = $input->page;
			$this->view->page = $page;
		
			$writable = Zend_Registry::get('Emerald_Acl')->isAllowed($this->getCurrentUser(), $input->page, 'write');
			$this->view->writable = $writable;
			if($writable) {
				Emerald_Js::addAdminScripts($this->view);			
			}
				
			$formcontentTbl = Emerald_Model::get('Formcontent');
			$formcontent = $formcontentTbl->find($input->page->id)->current();
		
			$this->view->formcontent = $formcontent;
		
			if($formcontent && $formcontent->form_id) {
			
				$formTbl = Emerald_Model::get('Form');
				$form = $formTbl->find($formcontent->form_id)->current();
									
				$this->view->form = $form;
								
			}
		
			$this->view->headScript()->appendFile('/lib/js/shard/formcontent/form.js');
		
				
		} catch(Exception $e) {
			throw $e;
		}
		
	}
	
	
	
	
	
	public function postAction()
	{
		
		$filters = array();
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),		
			'page_id' => array('Int', 'presence' => 'required'),
			'redirect_page_id' => array('Int', 'presence' => 'required'),
		);

		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();
			
			
			$formTbl = Emerald_Model::get('Form');
			$form = $formTbl->find($input->id)->current();
									
			$errorFields = array();
			foreach($form->getFields() as $field) {
				
				$validator = array();
				
				if($field->mandatory) {
					$validator['presence'] = 'required';
				}
					
				$minLength = ($field->mandatory) ? 1 : 0;
				
				
				switch($field->type) {
					
					
					case 1: case 5:
						$validator[] = new Zend_Validate_StringLength($minLength, 255);  
						break;
						
					case 2:
						$validator[] = new Zend_Validate_StringLength($minLength, 2000);
						break;
					
					case 3: case 4:
						$validator[] = new Zend_Validate_StringLength(1, 255);
						break;
						
					case 6:
						$validArr = ($field->mandatory) ? array(1) : array(0, 1);
						$validator[] = new Zend_Validate_InArray($validArr);
					default:
						continue;
					
					
				}
				
				
				
							
				$validators["form_{$form->id}_field_{$field->id}"] = $validator;
					
			}
			
			
			try {
				
				$filters = array();
				
				$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
				$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars(ENT_COMPAT, 'UTF-8'));
				$input->process();
				
				$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Validation ok');
				
				try {
					
					$formcontentTbl = Emerald_Model::get('Formcontent');
					
					$formcontent = $formcontentTbl->fetchRow(
						array('page_id = ?' => $input->page_id)
					);
					
					$formTbl = Emerald_Model::get('Form');
					
					$form = $formTbl->find($formcontent->form_id)->current();
					
					$transport = new Zend_Mail_Transport_Smtp();

					$mail = new Zend_Mail('UTF8');
					
					$mail->setSubject($formcontent->email_subject);
					$mail->setFrom($formcontent->email_from);
					$mail->addTo($formcontent->email_to);
					
					$rows = array();
					
					foreach($form->getFields() as $field) {
						
						$fieldVal = $input->getEscaped("form_{$form->id}_field_{$field->id}");
						
						if(is_array($fieldVal)) {
							$fieldVal = implode(', ', $fieldVal);
						}
						
						$rows[] = $field->title . ': ' . $fieldVal;					
					}
					
					$mesg = implode("\n", $rows);

					$mail->setBodyText($mesg);
									
					$mail->send($transport);
					
					
					$redirectPage = Emerald_Page::find($input->redirect_page_id);
					
					$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Mail sent ok');
					$msg->redirect_iisiurl = $redirectPage->iisiurl;					
										
					
				} catch(Exception $e) {
					
					$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Mail send failure.');
					$msg->exception = $e->getMessage();
				}
				
				
				
			} catch(Zend_Filter_Exception $e) {
				
				
				$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Validation error');
				$msg->errorFields = array_keys($input->getMessages());
				
				
			}
			
			
			
					
			
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
	    	$this->getResponse()->appendBody($msg);
			
		} catch(Exception $e) {
			throw new Emerald_Exception($e->getMessage(), 500);
		}
		

		
		
		
		
	}
	
	
}