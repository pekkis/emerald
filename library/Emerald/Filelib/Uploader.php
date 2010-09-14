<?php
class Emerald_Filelib_Uploader
{

	private $_filelib;
	
    /**
     * Sets filelib
     *
     * @param Emerald_Filelib $filelib
     */
    public function setFilelib(Emerald_Filelib $filelib)
    {
        $this->_filelib = $filelib;
    }

    /**
     * Returns filelib
     *
     * @return Emerald_Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }
	
	
	private $_accepted = array();
	
	private $_denied = array();

	
	
	
	public function accept($what)
	{
		if(!is_array($what)) {
			$what = array($what);
		}
        
		foreach($what as $w) {
            $accept = "[" . $w . "]";
            $this->_accepted[] = $accept;
            
            if(in_array($accept, $this->_denied)) {
                unset($this->_denied[$accept]);
            }
            
        }
        
        return $this;
		
	}
	
	
	
	public function deny($what)
	{
        if(!is_array($what)) {
            $what = array($what);
        }
        
        foreach($what as $w) {
        	$deny = "[" . $w . "]";
        	$this->_denied[] = $deny;
        	
        	if(in_array($deny, $this->_accepted)) {
        		unset($this->_accepted[$deny]);
        	}
        	
        }
        
        return $this;
		
	}
	
	
	
	public function getAccepted()
	{
		return $this->_accepted;
	}
	
	
	public function getDenied()
	{
		return $this->_denied;
	}
	
	
	
	public function isAccepted(Emerald_Filelib_FileUpload $upload)
	{
		$mimeType = $upload->getMimeType();
		
		if(!$this->getAccepted() && !$this->getDenied()) {
			return true;
		}
				
		foreach($this->getDenied() as $denied) {
			if(preg_match($denied, $mimeType)) {
				return false;
			}
		}
		
		if(!$this->getAccepted()) {
			return true;
		}
		
        foreach($this->getAccepted() as $accepted) {
            if(preg_match($accepted, $mimeType)) {
                return true;
            }
        }
		
        return false;
		
	}
	
	
}

