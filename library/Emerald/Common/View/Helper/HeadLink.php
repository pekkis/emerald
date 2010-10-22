<?php

use \Emerald\Base\Compiler\Compiler;

class Emerald_Common_View_Helper_HeadLink extends Zend_View_Helper_HeadLink
{

    private $_root = '/wwwroot/oikotie-autot/public';
    
    private $_compile = false;
    
    private $_compiler;
    
    public function getRoot()
    {
        return $this->_root;
    }
    
    
    public function setRoot($root)
    {
        $this->_root = $root;
        return $this;
    }
    
    
    public function setCompile($compile)
    {
        $this->_compile = $compile;
        return $this;
    }
        
    
    public function toString($indent = null)
    {
       
        foreach ($this as $item) {
            
            if ($item->rel == 'stylesheet') {
                
                if(!preg_match("/\.css$/", $item->href)) {

                    $pinfo = pathinfo($item->href);
                    if($this->_compile) {
                        $path = $this->getRoot() . $pinfo['dirname'] . '/' . $pinfo['filename'] . '.' . $pinfo['extension'];
                        $this->getCompiler()->compile($path);
                    }
                    
                    $item->href = $pinfo['dirname'] . '/' . $pinfo['filename'] . '.css';
                    
                }
                
            }
            
            
        }

        return parent::toString($indent);
    
    }    
    
    
    
    
    public function getCompiler()
    {
        if(!$this->_compiler) {
            $this->_compiler = new \Emerald\Base\Compiler\Compiler();
        }
        return $this->_compiler;
    }
    
    
    
    
}

