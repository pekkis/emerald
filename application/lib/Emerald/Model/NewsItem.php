<?php
class Emerald_Model_NewsItem extends Emerald_Db_Table_Abstract 
{
	protected $_name = 'news_item';
	protected $_primary = array('id');
	protected $_rowClass = 'Emerald_Db_Table_Row_NewsItem';
	
	protected $_referenceMap    = array(
        'NewsItems' => array(
            'columns'           => array('news_channel_id'),
            'refTableClass'     => 'Emerald_Model_NewsChannel',
            'refColumns'        => array('id')
		)
    );
	
	
	
	
}
