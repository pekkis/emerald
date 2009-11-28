<?php
class Core_Model_NewsChannel extends Emerald_Db_Table_Abstract 
{
	protected $_name = 'news_channel';
	protected $_primary = array('id');
	protected $_rowClass = 'Emerald_Db_Table_Row_NewsChannel';
	
	protected $_referenceMap    = array(
        'NewsItems' => array(
            'columns'           => array('id'),
            'refTableClass'     => 'Core_Model_NewsItem',
            'refColumns'        => array('news_channel_id')
		)
    );
	
	
	
	
	
}
