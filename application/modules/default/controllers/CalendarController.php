<?php
class CalendarController extends Emerald_Controller_Action
{
	
	public function indexAction()
	{
		
		$filters = array(
			'page' => array(new Emerald_Filter_PageIdToPage())
		);
		$validators = array(
			'page' => array(new Emerald_Validate_InstanceOf('Emerald_Page'), 'presence' => 'optional', 'allowEmpty' => true),
			'calMon' => array('Int'),
			'calYear' => array('Int')
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
		
			$page = $input->page;
			$calTbl = Emerald_Model::get('Calendar');
			$evtTbl = Emerald_Model::get('CalendarEvent');
			$where = array(
				'page_id = ?' => $page->id
			);
					
			if(!$cal = $calTbl->fetchRow($where)) 
			{
				$cal = $this->_buildCalendar($page); // creates a new calendar on first pageload
			}
			// if cal has a start date we'll use it
			if($cal->default_date)
			{
				$this->view->date = new Zend_Date($cal->default_date);
			}
			else // set the calendar to show current month
			{
				$this->view->date = new Zend_Date();
			}
			$this->view->calendar = $cal;
			
			
			$this->view->date->set(1, Zend_Date::DAY);
			if($input->calMon)
			{
				$this->view->date->set((int)$input->calMon, Zend_Date::MONTH_SHORT);
			}
			if($input->calYear)
			{
				$this->view->date->set((int)$input->calYear, Zend_Date::YEAR);
			}
			$this->view->events = Array(); // hash of the events indexed by id
			$this->view->ev_dates = Array(); // hash of the event dates indexed by id
			$events = $evtTbl->fetchActiveByMonth($this->view->date->toValue(Zend_Date::MONTH_SHORT),$this->view->date->toValue(Zend_Date::YEAR));
			foreach($events as $evt) 
			{
				$this->view->events[$evt->id] = $evt;
				$start = new Zend_Date($evt->start_date,"YYYY-MM-dd");
				$end = new Zend_Date($evt->end_date,"YYYY-MM-dd");
				$this->view->ev_dates[$evt->id] = Array($start,$end);
				#var_dump($evt->title, $evt->start_date, $evt->end_date);
			}
			
			$writable = $this->_emerald->getAcl()->isAllowed($this->_emerald->getUser(), $input->page, 'write');
			$this->view->writable = $writable;
			if($writable) {
				Emerald_Js::addAdminScripts($this->view);			
			}
			
			$this->view->headLink()->appendStylesheet('/lib/css/shard/calendar/basic.css');
			$this->view->headScript()->appendFile('/lib/js/prototype/prototype.js');
			$this->view->headScript()->appendFile('/lib/js/common.js');
						
		} catch(Exception $e) {
			throw $e;
		}
			
			
	}
	
	private function _buildCalendar($page)
	{
		
		$calTbl = Emerald_Model::get('Calendar');
		$where = array(
			'page_id = ?' => $page->id
		);
		if(!$cal = $calTbl->fetchRow($where)) {
			$cal = $calTbl->createRow(array(), true);
			$cal->page_id = $page->id;
		}	
		
		$cal->status = 1;
		$cal->save();
		return $cal;
	}
	
	
}