<?php
class Emerald_Model_Sitemap extends Zend_Db_Table_Abstract
{
    protected $_name = 'sitemap';
    protected $_primary = 'id';

    public function findSubpages($pageId = NULL)
    {
    	if(!$pageId)
    		$where = 'parent_id IS NULL'; 
    	else
    		$where = $this->getAdapter()->quoteInto('parent_id = ?', (int)$pageId);
        return $this->fetchAll($where);
    }
    
}
?>