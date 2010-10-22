<?php

namespace Emerald\Base\Compiler\Language;


abstract class AbstractLanguage implements Language
{
    
    abstract protected function _compile($file);

    protected $_targetFileExtension;
    
    
    public function getTargetFileExtension()
    {
        return $this->_targetFileExtension;
    }
    
    
    
    public function compile($file)
    {

        $pinfo = pathinfo($file);
        
        $target = $pinfo['dirname'] . '/' . $pinfo['filename'] . '.' . $this->getTargetFileExtension(); 
        
        if(filemtime($target) >= filemtime($file)) {
            return true;
        }
        
        return $this->_compile($file);
        
        
    }
    
    
    
    
    
}