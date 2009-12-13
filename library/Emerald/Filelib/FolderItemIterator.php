<?php
class Emerald_Filelib_FolderItemIterator
extends Emerald_Filelib_ItemIterator
implements RecursiveIterator
{


	public function hasChildren()
	{
		return $this->current()->findSubFolders()->count();
	}
	
	
	public function getChildren()
	{
		return $this->current()->findSubFolders();	
	}
	


}
