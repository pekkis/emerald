<?php
class Emerald_Db_Table_Row_NewsChannel extends Zend_Db_Table_Row_Abstract 
{
	
	/**
	 * Returns channels page.
	 *
	 * @return Emerald_Page
	 */
	public function getPage()
	{
		$pageTbl = Emerald_Model::get('DbTable_Page');
		return $pageTbl->find($this->page_id)->current();
	}
	
	
	
	public function getItemCount($invisible = false)
	{
		$date = new DateTime();
		if(!$invisible) {
			$sql = "SELECT count(*) FROM news_item WHERE news_channel_id = ? AND status = ?
			AND (valid_start <= ? OR valid_start IS NULL) AND
			(valid_end >= ? OR valid_end IS NULL)";
			$where = array($this->id, 1, $date->format('Y-m-d H:i:s'), $date->format('Y-m-d H:i:s'));
		} else {
			$sql = "SELECT count(*) FROM news_item WHERE news_channel_id = ?
			AND (valid_start <= ? OR valid_start IS NULL) AND
			(valid_end >= ? OR valid_end IS NULL)";
			$where = array($this->id, $date->format('Y-m-d H:i:s'), $date->format('Y-m-d H:i:s'));
		}
		
		
		return $this->getTable()->getAdapter()->fetchOne($sql, $where);
		
	}
	
	
	public function getItems($invisible = false, $count = null, $offset = null)
    {
		$newsItemTbl = Emerald_Model::get('NewsItem');
				
		$where = array();
						
		if(!$invisible) {
			
			$date = new DateTime();
									
			$where = array(
				'news_channel_id = ?' => $this->id,
				'status = ?' => 1,
				'(valid_start <= ? OR valid_start IS NULL)' => $date->format('Y-m-d H:i:s'),
				'(valid_end >= ? OR valid_end IS NULL)' => $date->format('Y-m-d H:i:s')
			);
		} else {
			
			$where = array(
				'news_channel_id = ?' => $this->id,
			);
			
		}

   		return $newsItemTbl->fetchAll($where, 'valid_start DESC', $count, $offset);
    }
	 
	
	
}
?>