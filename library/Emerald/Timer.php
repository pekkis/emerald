<?php
class Emerald_Timer implements IteratorAggregate
{
	
	static private $_timers = array();
	
	private $_times = array();
	
	private $_start = false;
	
	private $_events = array();
		
	static public function getTimer($name) {
		if(!isset(self::$_timers[$name])) {
			self::$_timers[$name] = new self();
		}
		return self::$_timers[$name];
	}
	
	
	private function __construct()
	{
		
	}
		
	
	public function time($event)
	{
		if(!$this->_start) {
			$this->_start = microtime(true); 
		}
		$this->_events[] = array('event' => $event, 'time' => microtime(true));
	}


	
	public function getEvents()
	{
		return $this->_events;
	}
	
	
	public function getStart()
	{
		return $this->_start;
	}
	
	
	public function getIterator()
	{
		return new ArrayIterator($this->getEvents());
	}
	
	
	
	public function __toString()
	{
		$output = '<table border="1">';
		$output .= '<tr><th>event</th><th>time</th></tr>';
		$start = $this->getStart();
		foreach($this as $key => $event) {
			$output .= "<tr>";								
			$output .=  "<td>{$key}. {$event['event']}</td><td>"  . ($event['time'] - $start);
			
			$output .= '</td>';
			
			$output .=  "</tr>";
		}		
		
		$output .= "</table>";
		
		return $output;
	}
	
	
	
}