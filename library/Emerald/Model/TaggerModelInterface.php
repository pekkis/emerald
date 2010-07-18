<?php
interface Emerald_Model_TaggerModelInterface
{
	
	public function findTagger(EmCore_Model_TaggableItem $taggable);
	
	public function findDescriptor(EmCore_Model_TaggableItem $taggable);
	
	
}