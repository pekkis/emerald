<?php
/**
 * Messenger wraps messages
 *
 * @package Emerald_Messaging
 * @author pekkis
 * @todo Is this used? Is this necessary or even smart?
 *
 */
class Emerald_Messaging_Messenger extends ArrayObject
{

    /**
     * Appends a message
     * 
     * @param Emerald_Messaging_Message $message
     */
    public function addMessage(Emerald_Messaging_Message $message)
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
?>