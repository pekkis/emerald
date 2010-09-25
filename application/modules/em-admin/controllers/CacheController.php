<?php
class EmAdmin_CacheController extends Emerald_Controller_Action
{
    public $ajaxable = array(
		'clear' => array('json'),
    );

    public function init()
    {
        $this->getHelper('ajaxContext')->initContext();
    }

    public function clearAction()
    {

        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___clear_caches")) {
            throw new Emerald_Exception('Forbidden', 403);
        }

        try {
            	
            $cacheManager = Zend_Registry::get('Emerald_CacheManager');
            foreach($cacheManager as $cache) {
                $cache->clean();
            }

            $msg = new Emerald_Messaging_Message(Emerald_Messaging_Message::SUCCESS, "All caches were cleared.");
            	
        } catch(Exception $e) {
            $msg = new Emerald_Messaging_Message(Emerald_Messaging_Message::ERROR, "Cleaning the caches failed.");
        }

        $this->view->message = $msg;


    }


}