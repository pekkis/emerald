<?php
/**
 * RecursiveFilterIterator with a callback
 * 
 * @author pekkis
 * @package Emerald_Common_Spl
 *
 */
class Emerald_Common_Spl_CallbackRecursiveFilterIterator extends RecursiveFilterIterator
{

    private $_callback;

    public function __construct($iterator, $callback)
    {
        parent::__construct($iterator);

        if(!is_callable($callback)) {
            throw new BadMethodCallException("Callback not callable");
        }

        $this->_callback = $callback;
    }


    public function accept()
    {
        $callback = $this->_callback;
        return $callback($this->current());
    }



}
