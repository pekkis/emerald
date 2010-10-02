<?php
class EmCore_Model_NewsItem extends Emerald_Cms_Model_Cacheable implements Emerald_Cms_Model_TaggerModelInterface
{
    protected static $_table = 'EmCore_Model_DbTable_NewsItem';

    protected function _getRawDependencies()
    {
        return parent::_getRawDependencies() + array('router' => function() { return Zend_Controller_Front::getInstance()->getRouter(); });
    }

    /**
     * Finds item with primary key
     *
     * @param $id
     * @return EmCore_Model_NewsItemItem
     */
    public function find($id)
    {
        if(!$ret = $this->findCached($id)) {
            $tbl = $this->getTable();
            $row = $tbl->find($id)->current();
            $ret =($row) ? new EmCore_Model_NewsItemItem($row->toArray()) : false;
            	
            if($ret) {
                $this->storeCached($id, $ret);
            }
        }

        return $ret;
    }




    public function save(EmCore_Model_NewsItemItem $item)
    {
        if(!is_numeric($item->id)) {
            $item->id = null;
        }

        $tbl = $this->getTable();

        if(!$item->id || !$row = $tbl->find($item->id)->current()) {
            $row = $tbl->createRow();
        }
        $row->setFromArray($item->toArray());
        $row->save();

        $item->setFromArray($row->toArray());

        $this->storeCached($item->id, $item);
        $channelModel = new EmCore_Model_NewsChannel();
        $channelModel->clearCached('items_' . $item->news_channel_id);

    }



    public function delete(EmCore_Model_NewsItemItem $item)
    {
        $tbl = $this->getTable();
        $row = $tbl->find($item->id)->current();
        if(!$row) {
            throw new Emerald_Cms_Model_Exception('Could not delete');
        }
        $row->delete();

        $this->clearCached($item->id);
        $channelModel = new EmCore_Model_NewsChannel();
        $channelModel->clearCached('items_' . $item->news_channel_id);

    }


    /**
     * Returns tagger for a taggable
     * @param EmCore_Model_TaggableItem $taggable
     * @return EmCore_Model_NewsItem
     */
    public function findTagger(EmCore_Model_TaggableItem $taggable)
    {
        if($id = $this->findCached('taggable_id_' . $taggable->id)) {
            return $this->find($id);
        }

        $tbl = $this->getTable();
        $id = $tbl->getAdapter()->fetchOne("SELECT id FROM emerald_news_item WHERE taggable_id = ?", array($taggable->id));
        if(!$id) {
            return false;
        }
        $this->storeCached('taggable_id_' . $taggable->id, $id);
        return $this->find($id);
    }


    public function findDescriptor(EmCore_Model_TaggableItem $taggable)
    {
        if(!$tagger = $this->findTagger($taggable)) {
            return false;
        }

        $channel = $tagger->getChannel();
        $page = $channel->getPage();

        $url = $this->getRouter()->assemble(array(
			'id' => $tagger->id,
			'title' => $tagger->title,
        ), "page_{$page->id}_news_view");

        $ret = new EmCore_Model_TaggableDescriptor(array(
			'title' => $tagger->title,
			'url' => $url,
			'description' => $tagger->description,
        ));

        return $ret;
    }




}
?>