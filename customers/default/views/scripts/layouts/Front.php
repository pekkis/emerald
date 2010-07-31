<?php
class Emerald_Layout_Front extends Emerald_Layout
{

    protected function _run()
    {
        $this->actionToStack('index', 'html-content', 'em-core', array('page_id' => $this->getPage()->id, 'block_id' => 3, 'rs' => 'sidebar_two'));

        $pageModel = new EmCore_Model_Page();
        $page = $pageModel->findGlobal(5, $this->getPage()->locale);
        if($page) {
            	
            $this->shard($page->id, 'News', array('a' => 'tag-cloud', 'rs' => 'sidebar_one'));
            	
            $this->shard($page->id, 'News', array('a' => 'headlines', 'amount' => 3, 'rs' => 'sidebar_one'));
        }
        	
        $this->shard($this->getPage(), $this->getPage()->shard_id, array('block_id' => 1));
    }

}