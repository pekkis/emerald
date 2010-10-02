<?php
class EmCore_Model_CustomContent extends Emerald_Cms_Model_Cacheable
{
    protected static $_table = 'EmCore_Model_DbTable_CustomContent';

    public function find($pageId, $blockId)
    {

        if(!$ret = $this->findCached(array($pageId, $blockId))) {
            	
            $tbl = $this->getTable();
            $row = $tbl->find($pageId, $blockId)->current();
            $ret = ($row) ? new EmCore_Model_CustomContentItem($row->toArray()) : new EmCore_Model_CustomContentItem(array('page_id' => $pageId, 'block_id' => $blockId));
            	
            $this->storeCached(array($ret->page_id, $ret->block_id), $ret);
            	
        }

        return $ret;

    }

    public function save(EmCore_Model_CustomContentItem $customContent)
    {
        $tbl = $this->getTable();

        $row = $tbl->find($customContent->page_id, $customContent->block_id)->current();
        if(!$row) {
            $row = $tbl->createRow();
        }

        $row->setFromArray($customContent->toArray());
        $row->save();

        $customContent->setFromArray($row->toArray());

        $this->storeCached(array($customContent->page_id, $customContent->block_id), $customContent);

    }



}