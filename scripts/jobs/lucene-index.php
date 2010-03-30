<?php

// Creates the Index of Evil.

setlocale(LC_ALL, 'fi_FI.UTF8');

function loadDocument($id, &$links)
{
	global $root, $index;

	echo "\nparsing document {$root}{$id}...";

	$query = new Zend_Search_Lucene_Search_Query_Boolean();
	$query->addSubquery(new Zend_Search_Lucene_Search_Query_Term(new Zend_Search_Lucene_Index_Term($id, 'path')));

	if(!NEW_INDEX) {
		if($hits = $index->find($query)) {
			foreach($hits as $hit) {
				echo "\n\tdeleting document with id {$hit->id}";
				$index->delete($hit->id);	
			}
		}
	}
	
	if(@$html = file_get_contents($root . $id)) {
		
		echo ' [LOAD OK]';
		
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8"); 
		$doc = Zend_Search_Lucene_Document_Html::loadHTML($html, true);
		$doc->addField(Zend_Search_Lucene_Field::Keyword('path', $id));
		$index->addDocument($doc);
	
		/*
		$rawLinks = $doc->getLinks();
		
		foreach($rawLinks as $rawLink) {
			
			// Zend_Debug::dump(explode('#', $rawLink, 2));
			
			$rawLink = rtrim(array_shift(explode('#', $rawLink, 2)), '/');
			
			// Zend_Debug::dump($rawLink);
			
			if($rawLink && !preg_match('/^(http|mailto|#)/i', $rawLink) && !preg_match('/\.(jpg|png|gif|css|js|odf)$/i', $rawLink) && !preg_match('/\/format\/(xml|json)$/i', $rawLink)) {
				
				if(!in_array($rawLink, $links)) {
					$links[] = $rawLink;
					loadDocument($rawLink, $links);	
				}
				
			}
		}
		*/
		
	} else {
		echo ' [LOAD FAILED!!!]';
	}
	
	echo "\n";
	
}



echo "\n\n\nLucene Indexing\n\n\n";

$droot = '/wwwroot/emerald/customers/default/data/index';

$root = 'http://emerald.axis-of-evil.org';

$id = '/';

putenv("EMERALD_CUSTOMER=default");

set_include_path(realpath(dirname(__FILE__) . '/../../library'));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH',
              realpath(dirname(__FILE__) . '/../../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV',
              (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                         : 'production'));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

try {

	$application->getBootstrap()
	->bootstrap('server')
	->bootstrap('modules')
	->bootstrap('customer')
	->bootstrap('cache')
	->bootstrap('db')
	->bootstrap('customerdb')
	->bootstrap('router')
	->bootstrap('acl')
	->bootstrap('locale')
	->bootstrap('view')
	->bootstrap('misc');
	
	echo "were here";


} catch(Exception $e) {
	
	echo $e;
	
	die('xooxer');
}


if(!is_dir($droot)) {
	mkdir($droot, 0775);
}





try {
	
	$index = Zend_Search_Lucene::open($droot);
	define('NEW_INDEX', false );
} catch(Exception $e) {
	$index = Zend_Search_Lucene::create($droot);
	define('NEW_INDEX', true);
}

$naviModel = new EmCore_Model_Navigation();
$navi = $naviModel->getNavigation();

$navi = new RecursiveIteratorIterator($navi, RecursiveIteratorIterator::SELF_FIRST);

Zend_Search_Lucene_Document_Html::setExcludeNoFollowLinks(true);

$links = array();

foreach($navi as $n) {
	if($n->id) {
			loadDocument($n->uri, $links);		
	}
	
}



Zend_Debug::dump($links);

?>
