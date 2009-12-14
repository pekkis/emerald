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
		$db = Zend_Registry::get('Emerald_Db');
		
		// We be getting de replaces
		$regex = '/page\/view\/id\/(\d+)/';
		preg_match_all($regex, $value, $matches);

		if(!$matches[0])
			return $value;
		
		// We be fetching de beautifurls for replaces from de database
		$imploded = implode(', ', $matches[1]);
		$sql = "SELECT id, beautifurl FROM page WHERE id IN ({$imploded})";
		$res = $db->fetchAll($sql);
		$beautifurls = array();
		foreach($res as $row) {
			$beautifurls[$row->id] = $row->beautifurl;			
		}

		// We be doin' de replacin'
		foreach($matches[0] as $key => $toReplace) {
			$value = str_ireplace($toReplace, $beautifurls[$matches[1][$key]], $value);
		}

		return $value;
		
	}
	
	
}
?>