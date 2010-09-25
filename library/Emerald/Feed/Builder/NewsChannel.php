<?php
/**
 * CMS specific Newsitem feed builder
 * 
 * @author pekkis
 * @package Emerald_Cms
 *
 */
class Emerald_Feed_Builder_NewsChannel implements Zend_Feed_Builder_Interface
{

    private $_channel;


    public function __construct(EmCore_Model_NewsChannelItem $channel)
    {
        $this->_channel = $channel;
        $this->_channel->items_per_page = 9999;
    }


    public function getHeader()
    {

        $request = Zend_Controller_Front::getInstance()->getRequest();

        $feedUrl = 'http://' . $request->getServer('HTTP_HOST') . $request->getRequestUri();

        $header = new Zend_Feed_Builder_Header($this->_channel->title, $feedUrl);
        $header->setDescription($this->_channel->description);
        $header->setGenerator("Emerald " . Emerald_Version::VERSION);

        $date = new DateTime($this->_channel->modified);

        $header->pubDate = $header->lastUpdate = $header->lastModified = $date->format('U');

        return $header;
    }


    public function getEntries()
    {
        $entries = array();

        $pageModel = new EmCore_Model_Page();
        $page = $pageModel->find($this->_channel->page_id);

        $request = Zend_Controller_Front::getInstance()->getRequest();

        foreach($this->_channel->getItems() as $item)
        {
            if(!$item->isValid()) {
                // Xml doesn't include invalids
                continue;
            }
            	
            $link = 'http://' . $request->getServer('HTTP_HOST') . '/' . $page->beautifurl . '?a=view&amp;id=' . $item->id;
            	
            	
            $entry = new Zend_Feed_Builder_Entry($item->title, $link, $item->description);
            	
            $date = new DateTime($item->valid_start);
            	
            $entry->pubDate = $entry->lastUpdate = $date->format('U');
            	
            	
            $entries[] = $entry;
        }


        return $entries;
    }





}