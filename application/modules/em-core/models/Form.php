<?php
class EmCore_Model_Form extends Emerald_Cms_Model_Cacheable
{

    protected static $_table = 'EmCore_Model_DbTable_Form';


    /**
     * Finds item with primary key
     *
     * @param $id
     * @return EmCore_Model_FormItem
     */
    public function find($id)
    {
        if(!$ret = $this->findCached($id)) {
            $tbl = $this->getTable();
            $row = $tbl->find($id)->current();
            $ret = ($row) ? new EmCore_Model_FormItem($row->toArray()) : false;

            if($ret) {
                $this->storeCached($id, $ret);
            }
            	
        }

        return $ret;

    }




    /**
     * Finds all items
     *
     * @return ArrayIterator
     */
    public function findAll()
    {
        $rows = $this->getTable()->fetchAll(array(), 'name ASC');
        $iter = new ArrayIterator();
        foreach($rows as $row) {
            $iter->append(new EmCore_Model_FormItem($row));
        }
        return $iter;
    }


    public function getFields(EmCore_Model_FormItem $item)
    {
        if(!$fields = $this->findCached('fields_' . $item->id)) {
            	
            $tbl = new EmCore_Model_DbTable_FormField();
            $rows = $tbl->fetchAll(array('form_id = ?' => $item->id), "order_id ASC");
            $fields = array();
            foreach($rows as $row) {
                $fields[] = new EmCore_Model_FormFieldItem($row);
            }
            	
            $this->storeCached('fields_' . $item->id, $fields);
            	
        }

        return new ArrayIterator($fields);
    }


    public function findFieldById(EmCore_Model_FormItem $item, $id)
    {
        foreach($this->getFields($item) as $field) {
            if($field->id == $id) {
                return $field;
            }
        }
        return false;
    }



    public function getOrderIdForNewField(EmCore_Model_FormItem $item)
    {
        $tbl = new EmCore_Model_DbTable_FormField();
        $max = $tbl->getAdapter()->fetchOne("SELECT MAX(order_id) FROM emerald_form_field WHERE form_id = ?", array($item->id));
        return $max + 1;
    }




    public function save(EmCore_Model_FormItem $item)
    {
        if(!is_numeric($item->id)) {
            $item->id = null;
        }

        $tbl = $this->getTable();

        $row = $tbl->find($item->id)->current();
        if(!$row) {
            $row = $tbl->createRow();
        }

        $row->setFromArray($item->toArray());
        $row->save();

        $item->setFromArray($row->toArray());

        $this->storeCached($item->id, $item);

    }


    public function saveField(EmCore_Model_FormFieldItem $item)
    {
        if(!is_numeric($item->id)) {
            $item->id = null;
        }

        $tbl = new EmCore_Model_DbTable_FormField();
        $row = $tbl->find($item->id)->current();
        if(!$row) {
            $row = $tbl->createRow();
        }

        $row->setFromArray($item->toArray());
        $row->save();
        $item->setFromArray($row->toArray());

        $this->clearCached('fields_' . $item->form_id);

    }


    public function deleteField(EmCore_Model_FormFieldItem $item)
    {
        $tbl = new EmCore_Model_DbTable_FormField();
        $row = $tbl->find($item->id)->current();
        if(!$row) {
            throw new Emerald_Cms_Model_Exception('Could not delete');
        }
        $row->delete();

        $this->clearCached('fields_' . $item->form_id);
    }


    public function delete(EmCore_Model_FormItem $item)
    {
        $tbl = $this->getTable();
        $row = $tbl->find($item->id)->current();
        if(!$row) {
            throw new Emerald_Cms_Model_Exception('Could not delete');
        }
        $row->delete();

        $this->clearCached('fields_' . $item->form_id);
        $this->clearCached($item->id);

    }



}
