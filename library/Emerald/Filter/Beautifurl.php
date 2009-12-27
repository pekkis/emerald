<?php
/**
 * beautifurl filter replaces page controllers view action calls with corresponding beautifurls(tm).
 *
 * @todo: optimize, cache, conquer world.
 */
class Emerald_Filter_Beautifurl implements Zend_Filter_Interface 
{
	
	public function filter($value)
	{
		
		// We be getting de replaces
		$regex = '/\/page\/view\/id\/(\d+)/';
		preg_match_all($regex, $value, $matches);

		if(!$matches[0]) {
			return $value;
		}

		
		$naviModel = new Core_Model_Navigation();

		$navigation = $naviModel->getNavigation();
		
		// We be doin' de replacin'
		foreach($matches[0] as $key => $toReplace) {
			$page = $navigation->findBy('id', $matches[1][$key]);
			if($page) {
				$value = str_ireplace($toReplace, $page->uri, $value);	
			}
			
		}

		return $value;
		
	}
	
	
}
?>