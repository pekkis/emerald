<?php

namespace Emerald\Base\Messaging;

/**
 * Message class with predefined message statuses
 *
 * @author pekkis
 *
 */
class Message
{
    const SUCCESS = 1;
    const INFO = 2;
    const FAILURE = 4;

    /**
     * Type(s)
     * @var integer
     */
    public $type;

    /**
     * Message
     * @var string
     */
    public $message;

    public function __construct($type, $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    
    
}