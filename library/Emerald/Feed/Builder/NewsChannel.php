<?php
class Emerald_Feed_Builder_NewsChannel implements Zend_Feed_Builder_Interface 
{
	
	private $_channel;
	
	
	public function __construct(Emerald_Db_Table_Row_NewsChannel $channel)
	{
		$this->_channel = $channel;
	}
	
	
	public function getHeader()
	{
		$app = Emerald_Application::getInstance();
		$request = Zend_Controller_Front::getInstance()->getRequest();
		
		$feedUrl = 'http://' . $request->getServer('HTTP_HOST') . $request->getRequestUri(); 
								
		$header = new Zend_Feed_Builder_Header($this->_channel->title, $feedUrl);
		$header->setDescription($this->_channel->description);
		$header->setGenerator(Emerald_Server::getInstance()->getIdentifier('full'));
		
		$date = new DateTime($this->_channel->modified);
						
		$header->pubDate = $header->lastUpdate = $header->lastModified = $date->format('U');
		
		
		return $header;
	}
	
	
	public function getEntries()
	{
		$entries = array();
		
		$page = $this->_channel->getPage();
		
		$app = Emerald_Application::getInstance();
		$request = Zend_Controller_Front::getInstance()->getRequest();
		
		foreach($this->_channel->getItems() as $item)
		{
			$link = 'http://' . $request->getServer('HTTP_HOST') . '/' . $page->iisiurl . '?a=view&amp;id=' . $item->id;
			
			
			$entry = new Zend_Feed_Builder_Entry($item->title, $link, $item->description);
			
			$date = new DateTime($item->valid_start);
									
			$entry->pubDate = $entry->lastUpdate = $date->format('U');
			
			
			$entries[] = $entry;
		}
		
		
		return $entries;
	}

	
	
	
	
}