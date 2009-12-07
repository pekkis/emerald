<?php
class Core_Model_DbTable_Page extends Zend_Db_Table_Abstract
{
    protected $_name = 'page';
	protected $_rowClass = 'Emerald_Page';
	
	public function findBranch($id, $locale)
	{
		$id = (int)$id;
		
		$where[] = $this->getAdapter()->quoteInto("COALESCE(parent_id, 0) = ?", $id);
		$where[] = $this->getAdapter()->quoteInto("locale = ?", $locale);
		$rows = $this->fetchAll($where, "order_id ASC");
		return $rows;
	}
	public function getNextOrderId($parentId, $locale)
	{
		$sql = "SELECT MAX(order_id)+1 FROM {$this->_name} WHERE COALESCE(parent_id, 0) = ? AND locale = ?";
		$newOrder = $this->_db->fetchOne($sql, array((int)$parentId, $locale));
		return $newOrder ? $newOrder : 0;
	}
	
}
?>