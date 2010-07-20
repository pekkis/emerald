<?php
class EmCore_Model_TaggableDescriptor
{
	private $_taggable;

	private $_locale;
	
	private $_title;
	
	private $_uri;
	
	private $_description;

	
	public function __construct($options = array())
	{
		Emerald_Options::setConstructorOptions($this, $options);
	}

	
	public function getLocale()
	{
		return $this->_locale;
	}

	
	public function setLocale($locale)
	{
		$this->_locale = $locale;
	}
	
	
	public function getTitle()
	{
		return $this->_title;
	}

	
	public function setTitle($title)
	{
		$this->_title = $title;
	}
	
	
	public function getTaggable()
	{
		return $this->_taggable;
	}

	
	public function setTaggable($taggable)
	{
		$this->_taggable = $taggable;
	}
	

	public function getUrl()
	{
		return $this->_url;
	}
	
	
	public function setUrl($url)
	{
		$this->_url = $url;
	}
	
	
	public function getDescription()
	{
		return $this->_description;
	}
	
	
	public function setDescription($description)
	{
		$this->_description = $description;
	}
	
	
	
} 
