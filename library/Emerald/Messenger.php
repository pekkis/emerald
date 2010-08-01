<?php
/**
 * Messenger wraps messages
 *
 * @package Emerald_Message
 * @author pekkis
 * @todo Is this used? Is this necessary? Or even reasonable?
 *
 */
class Emerald_Messenger extends ArrayObject
{

    /**
     * Appends a message
     * 
     * @param Emerald_Message $message
     */
    public function addMessage(Emerald_Message $message)
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