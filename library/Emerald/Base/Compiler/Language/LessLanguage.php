<?php

namespace Emerald\Base\Compiler\Language;

use Symfony\Component\Console\Shell;

class LessLanguage extends AbstractLanguage
{

    protected $_targetFileExtension = 'css';
    
    
    protected function _compile($file)
    {
        $cmd = "lessc " . escapeshellarg($file);
        exec($cmd);
    }
    
}