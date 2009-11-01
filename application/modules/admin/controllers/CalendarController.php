<?php
class Admin_CalendarController extends Emerald_Controller_AdminAction
{
	public function editAction()
	{
		$filters = array();
		
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),
		);
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			$id = $input->id;
			$calTbl = Emerald_Model::get('Calendar');
			$cal = $calTbl->find($id)->current();
		
			$this->view->calendar = $cal;
			$this->view->layout()->setLayout("admin_popup_outer");
		} catch(Exception $e) {throw $e;
			throw new Emerald_Exception('Internal Server Error', 500);
		}
	}
	
	public function editeventAction()
	{
		$filters = array();
		
		$validators = array(
			'id' => array('Int'),
			'calendar_id' => array('Int', 'presence' => 'required')
		);
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			$id = $input->id;
			$calId = $input->calendar_id;
			
			$evtTbl = Emerald_Model::get('CalendarEvent');
			if($id)
			{
				$event = $evtTbl->find($id)->current();
			}else
			{
				$event = $evtTbl->createRow();
				$event->calendar_id = $calId;
			}
		
			$this->view->event = $event;
			$this->view->layout()->setLayout("admin_popup_outer");
		} catch(Exception $e) { 
			throw new Emerald_Exception('Internal Server Error', 500);
		}
	}
	
	public function saveeventAction()
	{
		
		$filter = new Emerald_Filter_SelectDate();
		$this->_setParam('start_date', $filter->filter($this->_getParam('start_date')));
		$this->_setParam('end_date', $filter->filter($this->_getParam('end_date')));
		
		$filters = array();
		
		$validators = array(
			'id' => array('Int', 'presence' => 'required', "allowEmpty"=>true),
			'calendar_id' => array('Int', 'presence' => 'required', "allowEmpty"=>false),
			'title' => array(new Zend_Validate_StringLength(1, 255), 'presence' => 'required'),
			'description' => array(new Zend_Validate_StringLength(0), 'presence' => 'required'),
			'content' => array(new Zend_Validate_StringLength(1), 'presence' => 'required'),
			'start_date' => array(new Emerald_Validate_Datetime()),
			'end_date' => array(new Emerald_Validate_Datetime()),
		);
				
		
		$db = Zend_Registry::get('Emerald_Db');
		$db->beginTransaction();
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			
			$input->process();
			
			$calendarTbl = Emerald_Model::get('Calendar');
			$eventTbl = Emerald_Model::get('CalendarEvent');
			$calendar = $calendarTbl->find($input->calendar_id)->current();
			if($input->id)
			{
				$event = $eventTbl->find($input->id)->current();
			}
			else
			{
				$event = $eventTbl->createRow(array(), true);
			}
			
			$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Save ok');
			
			$event->calendar_id = $calendar->id;
			$event->start_date = $input->start_date;
			$event->end_date = $input->end_date;	
			$event->title = $input->getUnescaped('title');
			$event->description = $input->getUnescaped('description');
			$event->content = $input->getUnescaped('content');	
			$event->status = 1;
			$event->save();
			
			$db->commit();
		} 
		catch(Exception $e) 
		{
			$db->rollback();
			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed');
			$message->errorFields = array_keys($input->getMessages()); 
		}
				
		$this->_helper->_layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);
		
		
	}
}