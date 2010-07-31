<?php
interface Emerald_Model_TaggableItemInterface
{
    public function getTaggable();

    public function getTaggableId();

    public function setTaggableId($taggableId);

    public function getType();

}