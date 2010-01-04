<?php
class Core_Model_DbTable_NewsChannel extends Zend_Db_Table_Abstract 
{
	protected $_name = 'news_channel';
	protected $_primary = array('id');
	
	protected $_referenceMap    = array(
        'NewsItems' => array(
            'columns'           => array('id'),
            'refTableClass'     => 'Core_Model_DbTable_NewsItem',
            'refColumns'        => array('news_channel_id')
		)
    );
	
	
	
	
	
}
