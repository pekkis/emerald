<?php

namespace Emerald\Filelib;

/**
 * Filelib item iterator extends ArrayIterator to implement toArray method.
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
abstract class AbstractItemIterator extends \ArrayIterator
{

    public function __construct($array)
    {
        if(!is_array($array)) {
            $array = array($array);
        }

        parent::__construct($array);
    }


    /**
     * Returns the collection as array.
     *
     * @return array
     */
    public function toArray()
    {
        $arr = array();
        foreach($this as $item) {
            $arr[] = $item->toArray();
        }

        return $arr;
    }

}
?>