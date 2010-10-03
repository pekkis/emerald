<?php

/**
 * FilterIterator with a callback
 * 
 * @author pekkis
 * @package Emerald_Base_Spl
 *
 */
class Emerald_Base_Spl_CallbackFilterIterator extends FilterIterator
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

