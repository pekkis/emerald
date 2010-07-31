<?php
class EmCore_Model_DbTable_NewsChannel extends Zend_Db_Table_Abstract
{
    protected $_name = 'emerald_news_channel';
    protected $_primary = array('id');

    protected $_referenceMap    = array(
        'NewsItems' => array(
            'columns'           => array('id'),
            'refTableClass'     => 'EmCore_Model_DbTable_NewsItem',
            'refColumns'        => array('news_channel_id')
    )
    );





}
