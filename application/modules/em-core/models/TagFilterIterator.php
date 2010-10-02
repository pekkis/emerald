<?php
class EmCore_Model_TagFilterIterator extends FilterIterator
{
    private $_tag;

    public function __construct($iterator, $tag)
    {
        parent::__construct($iterator);

        $this->_tag = $tag;

    }

    public function accept()
    {
        $item = $this->getInnerIterator()->current();

        if(!$item instanceof Emerald_Cms_Model_TaggableItemInterface) {
            throw new Emerald_Exception('TagFilterIterator only accepts instances of Emerald_Cms_Model_TaggableItemInterface');
        }

        if(!$item->getTaggable()) {
            return false;
        }

        $tags = $item->getTaggable()->tagsToArray();
        return in_array($this->_tag, $tags);
    }
}
