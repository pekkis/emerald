<?php

namespace Emerald\Base\Spl;

use \RecursiveFilterIterator, \BadMethodCallException;

/**
 * RecursiveFilterIterator with a callback
 * 
 * @author pekkis
 *
 */
class CallbackRecursiveFilterIterator extends RecursiveFilterIterator
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

