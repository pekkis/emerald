<?php

namespace Emerald\Base\Cache;

interface Cache
{
    public function load($id);
        
    public function save($id, $data);

    public function remove($id);
    
    public function contains($id);
        
    public function setAutoSerialize($autoSerialize);
    
    public function getAutoSerialize();
    
}