<?php

namespace Emerald\Base\Cache\Adapter;

use \Emerald\Base\Cache\AbstractCache;

abstract class AbstractCacheAdapter extends AbstractCache 
{
    private $_cache;
    
    public function setCache($cache)
    {
        $this->_cache = $cache;
    }
    
    public function getCache()
    {
        return $this->_cache;
    }
    
}