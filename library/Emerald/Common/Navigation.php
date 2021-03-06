<?php
/**
 * Extends Zend_Navigation with callback finders
 * 
 * @author pekkis
 * @package Emerald_Common_Navigation
 *
 */
class Emerald_Common_Navigation extends Zend_Navigation
{
    /**
     * Returns a child page by a callback
     *
     * @param  callback $callback           name of property to match against
     * @return Zend_Navigation_Page|null  matching page or null
     */
    public function findOneByCallback($callback)
    {
        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $page) {
            if ($callback($page) == true) {
                return $page;
            }
        }

        return null;
    }

    /**
     * Returns all child pages by callback, or an empty array
     * if no pages are found
     *
     * @param  callback $callback  callback
     * @return array             array containing only Zend_Navigation_Page
     *                           instances
     */
    public function findAllByCallback($callback)
    {
        $found = array();

        $iterator = new RecursiveIteratorIterator($this, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $page) {
            if ($callback($page) == true) {
                $found[] = $page;
            }
        }

        return $found;
    }

    /**
     * Returns page(s) by callback
     *
     * @param  callback $callback  name of callback
     * @param  bool $all       [optional] whether an array of all matching
     *                           pages should be returned, or only the first.
     *                           If true, an array will be returned, even if not
     *                           matching pages are found. If false, null will
     *                           be returned if no matching page is found.
     *                           Default is false.
     * @return Zend_Navigation_Page|null  matching page or null
     */
    public function findByCallback($callback, $all = false)
    {
        if ($all) {
            return $this->findAllByCallback($property, $value);
        } else {
            return $this->findOneByCallback($property, $value);
        }
    }



}

