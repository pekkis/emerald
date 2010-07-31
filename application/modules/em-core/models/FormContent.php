<?php
class EmCore_Model_FormContent extends Emerald_Model_Cacheable
{
    protected static $_table = 'EmCore_Model_DbTable_FormContent';

    /**
     * Finds item with primary key
     *
     * @param $id
     * @return EmCore_Model_FormContentItem
     */
    public function find($id)
    {
        if(!$ret = $this->findCached($id)) {

            $tbl = $this->getTable();
            $row = $tbl->find($id)->current();

            $ret = ($row) ? new EmCore_Model_FormContentItem($row->toArray()) : false;

            if($ret) {
                $this->storeCached($ret->page_id, $ret);
            }
            	
        }
        return $ret;
    }


    /**
     * Finds item with page id
     *
     * @param $pageId
     * @return EmCore_Model_FormContentItem
     */
    public function findByPageId($pageId)
    {

        if(!$ret = $this->find($pageId)) {
            return new EmCore_Model_FormContentItem(
            array(
					'id' => null,
					'page_id' => $pageId,
            )
            );
        }
        return $ret;
    }


    public function save(EmCore_Model_FormContentItem $item)
    {
        if(!is_numeric($item->page_id)) {
            $item->page_id = null;
        }

        $tbl = $this->getTable();

        $row = $tbl->find($item->page_id)->current();
        if(!$row) {
            $row = $tbl->createRow();
        }
        $row->setFromArray($item->toArray());
        $row->save();
        	
        $item->setFromArray($row->toArray());

        $this->clearCached($item->page_id);

    }



}
?>