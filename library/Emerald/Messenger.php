<?php
/**
 * Messenger class
 *
 * @package Emerald_Messenger
 * @author pekkis
 *
 */
class Emerald_Messenger extends ArrayObject
{


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