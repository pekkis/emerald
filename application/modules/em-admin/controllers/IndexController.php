<?php
class EmAdmin_IndexController extends Emerald_Cms_Controller_Action
{
    public function indexAction()
    {


        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), "Emerald_Activity_administration___expose")) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }


    }
}