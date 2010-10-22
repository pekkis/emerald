<?php

namespace Emerald\Base\Compiler;

class Compiler
{

    private $_extensions = array(
        'scss' => 'Sass',
        'less' => 'Less',
        'coffee' => 'CoffeeScript',
    );
    
    private $_compilers = array();
    
    
    public function isCompilable($file)
    {
        $pinfo = pathinfo($file);
        
        if(!isset($this->_extensions[$pinfo['extension']])) {
            return false;
        }
                
        return $this->_extensions[$pinfo['extension']];
    }
    
    
    
    public function compile($file)
    {
        if(!$language = $this->isCompilable($file)) {
            throw new InvalidArgumentException("Can not compile file '{$file}'");
        }

        
        $compiler = $this->getCompilerForLanguage($language);
        
        return $compiler->compile($file);
                
    }

    
    
    public function getCompilerForLanguage($language)
    {
        if(!isset($this->_compilers[$language])) {
            $className = '\\Emerald\\Base\\Compiler\\Language\\' . $language . 'Language';
            $this->_compilers[$language] = new $className();
        }

        return $this->_compilers[$language];
        
        
    }
    
    
    
    
    
}