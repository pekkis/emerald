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

		if($matches[0]) {
			$naviModel = new EmCore_Model_Navigation();
			$navigation = $naviModel->getNavigation();
			// We be doin' de replacin'
			foreach($matches[0] as $key => $toReplace) {
				$page = $navigation->findBy('id', $matches[1][$key]);
				if($page) {
					$value = str_ireplace(EMERALD_URL_BASE . $toReplace, $page->uri, $value);	
				}
				
			}
		}

		// We be getting de replaces
		$regex = '/\/em-filelib\/file\/render\/id\/(\d+)(\/version\/([a-z_]+))?/';
		preg_match_all($regex, $value, $matches);
		
		if($matches[0]) {
			
			$fl = Zend_Registry::get('Emerald_Filelib');
			
			// We be doin' de replacin'
			foreach($matches[0] as $key => $toReplace) {
				
				$file = $fl->file()->find($matches[1][$key]);
												
				if($file && $file->isAnonymous()) {
					
					$opts = array();
					if(isset($matches[3][$key]) && $matches[3][$key]) {
						$opts['version'] = $matches[3][$key];
					}
					
					$value = str_ireplace(EMERALD_URL_BASE . $toReplace, $file->renderPath($opts), $value);	
				}
				
			}
		}
		

		return $value;
		
		
	}
	
	
}
?>