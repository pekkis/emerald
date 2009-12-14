<?php
/**
 * Filebeautifurl filter replaces file controllers render action calls with
 * corresponding beautifurls(tm), but only if the folder is world readable (readably by anonymous).
 *
 * @todo: optimize, cache, conquer world.
 */
class Emerald_Filter_Filebeautifurl implements Zend_Filter_Interface 
{
	
	public function filter($value)
	{
		$db = Zend_Registry::get('Emerald_Db');
		
		// We be getting de replaces
		$regex = '/file\/render\/id\/(\d+)/';
		preg_match_all($regex, $value, $matches);

		if(!$matches[0])
			return $value;
				
		$ids = array_unique($matches[1]);
						
		$sql = 'SELECT filelib_file.id, filelib_file.beautifurl FROM filelib_file 
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
			
			$value = str_ireplace("/file/render/id/{$toReplace->id}", "/data/files/{$toReplace->beautifurl}", $value);
		}

		return $value;
		
	}
	
	
}
?>