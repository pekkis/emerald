<?php
class Emerald_Model_Calendar extends Emerald_Db_Table_Abstract 
{
	protected $_name = 'calendar';
	protected $_primary = array('id');
	#protected $_rowClass = 'Emerald_Db_Table_Row_Calendar';
	
	protected $_referenceMap    = array(
        'Events' => array(
            'columns'           => array('id'),
            'refTableClass'     => 'Emerald_Model_CalendarEvent',
            'refColumns'        => array('calendar_id')
		)
    );
    
	
	
	
	
	
}