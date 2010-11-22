<?php
class EmAdmin_CacheController extends Emerald_Cms_Controller_Action
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
            throw new Emerald_Common_Exception('Forbidden', 403);
        }

        try {
            	
            $cacheManager = Zend_Registry::get('Emerald_CacheManager');
            foreach($cacheManager as $cache) {
                $cache->clean();
            }

            $msg = new Emerald\Base\Messaging\Message(Emerald\Base\Messaging\Message::SUCCESS, "All caches were cleared.");
            	
        } catch(Exception $e) {
            $msg = new Emerald\Base\Messaging\Message(Emerald\Base\Messaging\Message::FAILURE, "Cleaning the caches failed.");
        }

        $this->view->message = $msg;


    }


}