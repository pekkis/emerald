<?php
/**
 * Iisiurl filter replaces page controllers view action calls with corresponding IisiUrls(tm).
 *
 * @todo: optimize, cache, conquer world.
 */
class Emerald_Filter_Iisiurl implements Zend_Filter_Interface 
{
	
	public function filter($value)
	{
		$db = Emerald_Application::getInstance()->getDb();
		
		// We be getting de replaces
		$regex = '/page\/view\/id\/(\d+)/';
		preg_match_all($regex, $value, $matches);

		if(!$matches[0])
			return $value;
		
		// We be fetching de iisiurls for replaces from de database
		$imploded = implode(', ', $matches[1]);
		$sql = "SELECT id, iisiurl FROM page WHERE id IN ({$imploded})";
		$res = $db->fetchAll($sql);
		$iisiUrls = array();
		foreach($res as $row) {
			$iisiUrls[$row->id] = $row->iisiurl;			
		}

		// We be doin' de replacin'
		foreach($matches[0] as $key => $toReplace) {
			$value = str_ireplace($toReplace, $iisiUrls[$matches[1][$key]], $value);
		}

		return $value;
		
	}
	
	
}
?>