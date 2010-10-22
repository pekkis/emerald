<?php

set_include_path(realpath(dirname(__FILE__) . '/../../library'));

require_once "Zend/Loader/Autoloader.php";
Zend_Loader_Autoloader::getInstance()->getDefaultAutoloader();

Zend_Loader_Autoloader::getInstance()->registerNamespace('Emerald');


$compiler = new Emerald\Base\Compiler\Compiler();



$riter = new RecursiveDirectoryIterator($argv[1]);


$iter = new RecursiveIteratorIterator($riter);


foreach($iter as $item) {
    
    if($item->isFile()) {
        if($compiler->isCompilable($item->getPathname())) {
            echo "puuuuuppa";
        }
        
    }
    
    
}




Zend_Debug::dump($argv);

die();



die('wuuhuu');



