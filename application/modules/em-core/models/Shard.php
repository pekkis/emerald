<?php
class EmCore_Model_Shard extends Emerald_Model_Cacheable
{

    const ACTIVE = 1;
    const INSERTABLE = 2;

    protected $_shards = array();

    protected $_shardNames = array();

    protected static $_table = 'EmCore_Model_DbTable_Shard';

    protected function _populate()
    {
        if(!$this->_shards) {
            	
            if($data = $this->findCached('data')) {
                $this->_shards = $data['shards'];
                $this->_shardNames = $data['shardNames'];
            } else {
                $tbl = $this->getTable();
                $rows = $tbl->fetchAll(array(), 'name ASC');

                foreach($rows as $row) {
                    	
                    $className = $row->namespace . '_Model_ShardItem_' . $row->name;
                    $shard = new $className($row->toArray());
                    	
                    $this->_shards[$shard->id] = $shard;
                    $this->_shardNames[$shard->name] = $shard->id;
                    	
                }

                $this->storeCached('data', array('shards' => $this->_shards, 'shardNames' => $this->_shardNames));

            }

        }

    }


    /**
     * Finds item with primary key
     *
     * @param $id
     * @return EmCore_Model_ShardItem
     */
    public function find($id)
    {
        $this->_populate();
        return (isset($this->_shards[$id])) ? $this->_shards[$id] : false;
    }


    /**
     * Finds item with name
     *
     * @param $id
     * @return EmCore_Model_ShardItem
     */
    public function findByName($name)
    {
        $this->_populate();
        return (isset($this->_shardNames[$name])) ? $this->_shards[$this->_shardNames[$name]] : false;
    }


    /**
     * Finds all items
     *
     * @return ArrayIterator
     */
    public function findAll()
    {
        $this->_populate();
        return new ArrayIterator($this->_shards);
    }





    public function findByIdentifier($identifier)
    {
        return (is_numeric($identifier) ? $this->find($identifier) : $this->findByName($identifier));
    }


}
?>