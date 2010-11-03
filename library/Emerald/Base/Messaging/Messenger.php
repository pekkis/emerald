<?php

namespace Emerald\Base\Messaging;

/**
 * Messenger wraps messages
 *
 * @author pekkis
 * @todo Is this used? Is this necessary or even smart?
 *
 */
class Messenger extends ArrayObject
{

    /**
     * Appends a message
     * 
     * @param Emerald\Base\Messaging\Message $message
     */
    public function addMessage(Emerald\Base\Messaging\Message $message)
    {
        $this->append($message);
    }


    public function toArray()
    {
        $ret = array();
        foreach($this as $message) {
            $ret[] = $message;
        }
        return $ret;
    }



}