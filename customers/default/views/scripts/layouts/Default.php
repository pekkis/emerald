<?php
class Emerald_Layout_Default extends Emerald_Layout
{

    protected function _run()
    {
        $this->actionToStack('index', 'html-content', 'emerald', array('page_id' => $this->getPage()->id, 'block_id' => 3, 'rs' => 'sidebar_one'));
        $this->actionToStack('index', 'html-content', 'emerald', array('page_id' => $this->getPage()->id, 'block_id' => 4, 'rs' => 'pre_content'));
        $this->actionToStack('index', 'html-content', 'emerald', array('page_id' => $this->getPage()->id, 'block_id' => 5, 'rs' => 'post_content'));
        $this->shard($this->getPage(), $this->getPage()->shard_id, array('block_id' => 1));
    }


}