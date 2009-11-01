<?php
/**
 * FileIisiurl filter replaces file controllers render action calls with
 * corresponding IisiUrls(tm), but only if the folder is world readable (readably by anonymous).
 *
 * @todo: optimize, cache, conquer world.
 */
class Emerald_Filter_FileIisiurl implements Zend_Filter_Interface 
{
	
	public function filter($value)
	{
		$db = Emerald_Application::getInstance()->getDb();
		
		// We be getting de replaces
		$regex = '/file\/render\/id\/(\d+)/';
		preg_match_all($regex, $value, $matches);

		if(!$matches[0])
			return $value;
		
		// Zend_Debug::dump($matches);
				
		$ids = array_unique($matches[1]);
						
		$sql = 'SELECT filelib_file.id, filelib_file.iisiurl FROM filelib_file 
				JOIN filelib_folder ON(filelib_file.folder_id = filelib_folder.id)
				JOIN permission_filelib_folder_ugroup ON(filelib_folder.id = permission_filelib_folder_ugroup.folder_id)
				WHERE filelib_file.id IN(' . implode(',', $ids) . ')
				AND permission_filelib_folder_ugroup.ugroup_id = ' . Emerald_Group::GROUP_ANONYMOUS;

		try {
			$allowedReplaces = $db->fetchAll($sql);
						
		} catch(Exception $e) {
			$allowedReplaces = array();			
		}

		// We be doin' de replacin'
		foreach($allowedReplaces as $key => $toReplace) {
			
			$value = str_ireplace("/file/render/id/{$toReplace->id}", "/data/files/{$toReplace->iisiurl}", $value);
		}

		return $value;
		
	}
	
	
}
?>