<?php
class Emerald_Model_CalendarEvent extends Emerald_Db_Table_Abstract 
{
	protected $_name = 'calendar_event';
	protected $_primary = array('id');
	protected $_rowClass = 'Emerald_Db_Table_Row_CalendarEvent';
	
	protected $_referenceMap    = array(
        'Events' => array(
            'columns'           => array('calendar_id'),
            'refTableClass'     => 'Emerald_Model_Calendar',
            'refColumns'        => array('id')
		)
    );

    public function fetchActiveByMonth($month, $year)
    {
    	$month = (int) $month;
    	$year = (int) $year;
    	if($month > 0 && $month < 13)
    	{
    		$date = "{$year}-{$month}-01";
    		// get all events which have started before next month (= in this month or earlier)
    		// and the end date is in this month or later
    		$where[] = "start_date < DATE_ADD('{$date}', INTERVAL 1 MONTH)";
    		$where[] = "end_date > DATE_SUB('{$date}', INTERVAL 1 MONTH)";
    		$res = $this->fetchAll($where);
    	}
    	
    	return $res;
    }
}
