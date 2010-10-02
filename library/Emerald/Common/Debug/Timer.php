<?php
/**
 * Timer component for benchmarking
 * 
 * @author pekkis
 * @package Emerald_Common_Debug
 *
 */
class Emerald_Common_Debug_Timer implements IteratorAggregate
{

    /**
     * @var array Timers
     */
    static private $_timers = array();

    /**
     * @var float Start microtime
     */
    private $_start = false;

    /**
     * @var array Timed events
     */
    private $_events = array();

    /**
     * Returns a timer
     * 
     * @param string $name Timer name
     * @return Emerald_Common_Debug_Timer
     */
    static public function getTimer($name) {
        if(!isset(self::$_timers[$name])) {
            self::$_timers[$name] = new self();
        }
        return self::$_timers[$name];
    }


    /**
     * All constructs go via getTimer()
     */
    private function __construct()
    { }


    /**
     * Time an event
     * 
     * @param string $event Event name
     */
    public function time($event)
    {
        if(!$this->_start) {
            $this->_start = microtime(true);
        }
        $this->_events[] = array('event' => $event, 'time' => microtime(true));
    }



    /**
     * Returns all timed events
     * 
     * @return array
     */
    public function getEvents()
    {
        return $this->_events;
    }


    /**
     * Returns start microtime
     * 
     * @return float
     */
    public function getStart()
    {
        return $this->_start;
    }


    public function getIterator()
    {
        return new ArrayIterator($this->getEvents());
    }


    /**
     * Returns an HTML representation of the timer
     * 
     * @return string
     */
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