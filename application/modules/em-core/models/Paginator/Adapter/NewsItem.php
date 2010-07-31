<?php
class EmCore_Model_Paginator_Adapter_NewsItem implements Zend_Paginator_Adapter_Interface
{
    protected $_channel;

    protected $_innerAdapter;

    protected $_invalids;

    public function __construct(EmCore_Model_NewsChannelItem $channel, $invalids = false)
    {
        $this->_channel = $channel;
        $this->_invalids = $invalids;
    }



    public function getInnerAdapter()
    {
        if(!$this->_innerAdapter) {
            $tbl = new EmCore_Model_DbTable_NewsItem();
            $sel = $tbl->select()->where('news_channel_id = ?', $this->_channel->id);
            	
            if(!$this->_invalids) {
                $now = new DateTime();
                $sel->where("status = ?", 1);
                $sel->where("valid_start <= ?", $now->format('Y-m-d H:i:s'));
                $sel->where("valid_end >= ?", $now->format('Y-m-d H:i:s'));
            }
            	
            $sel->order('valid_start DESC');
            	
            $this->_innerAdapter = new Zend_Paginator_Adapter_DbTableSelect($sel);
        }

        return $this->_innerAdapter;
    }


    public function count()
    {
        return $this->getInnerAdapter()->count();
    }



    public function getItems($offset, $itemCountPerPage)
    {
        $rawItems = $this->getInnerAdapter()->getItems($offset, $itemCountPerPage);

        $items = new ArrayIterator();
        foreach($rawItems as $item) {
            $items[] = new EmCore_Model_NewsItemItem($item->toArray());
        }
        return $items;
    }



}