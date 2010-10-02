<?php
class EmCore_InstallController extends Emerald_Controller_Action
{

    public function preDispatch()
    {
        $installed = $this->getCustomer()->getOption('installed');
        if($installed) {
            throw new Emerald_Common_Exception("Already installed", 500);
        }
    }


    public function indexAction()
    {

        $form = new EmCore_Form_Install();
        $this->view->form = $form;
    }


    public function postAction()
    {

        $form = new EmCore_Form_Install();

        try {
            if(!$form->isValid($this->getRequest()->getPost())) {
                throw new Emerald_Common_Exception('Invalid form');
            } else {

                $install = new EmCore_Model_Install();
                $install->install($this->getCustomer(), $form);

                $this->getHelper('redirector')->gotoUrlAndExit('/');

            }
        } catch(Exception $e) {
            $this->view->form = $form;
            $this->getHelper('viewRenderer')->setScriptAction('index');
        }


    }


}