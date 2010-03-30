<?php
class EmCore_View_Helper_LanguageSwitcher extends Zend_View_Helper_Abstract
{

	
	public function languageSwitcher(array $options)
	{

		if(!isset($options['page']) || !$options['page']) {
			return;
		}
		
		$page = $options['page']['page'];
		
		$naviModel = new EmCore_Model_Navigation();
		$navi = $naviModel->getNavigation();
		
		$others = $navi->findAllBy('global_id', $page->global_id);
		
		$otherArr = array();
		
		foreach($others as $other) {
			$otherArr[$other->locale] = $other;
		}
		
		
		echo "<ul>";

		foreach($otherArr as $opage) {
			if($opage->locale != $page->locale) {
				echo "<li>";
				echo "<a href=\"{$opage->uri}\">{$opage->locale}</a></li>";
			}
		}
		
		
		echo "</ul>";
		
		
		
		
	}
	
	
	
	
	
}
