<?php
class Core_Model_DbTable_NewsItem extends Zend_Db_Table_Abstract 
{
	protected $_name = 'emerald_news_item';
	protected $_primary = array('id');
	
	protected $_referenceMap    = array(
        'NewsChannel' => array(
            'columns'           => array('news_channel_id'),
            'refTableClass'     => 'Core_Model_DbTable_NewsChannel',
            'refColumns'        => array('id')
		)
    );
	
	
	
	
}
