<?php
class EmAdmin_PageController extends Emerald_Cms_Controller_Action
{
    public $ajaxable = array(
		'save' => array('json'),
		'delete' => array('json'),
		'save-partial' => array('json')
    );

    public function init()
    {
        $this->getHelper('ajaxContext')->initContext();
    }

    public function deleteAction()
    {
        $pageModel = new EmCore_Model_Page();
        $page = $pageModel->find($this->_getParam('id'));

        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }

        try {
            $pageModel->delete($page);
            $this->view->message = new Emerald\Base\Messaging\Message(Emerald\Base\Messaging\Message::SUCCESS, 'Save ok');
        } catch(Emerald_Common_Exception $e) {
            $this->view->message = new Emerald\Base\Messaging\Message(Emerald\Base\Messaging\Message::FAILURE, 'Save failed');
        }

    }



    public function editAction()
    {
        $pageModel = new EmCore_Model_Page();

        $page = $pageModel->find($this->_getParam('id'));

        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }

        $form = new EmAdmin_Form_Page();
        $form->setLocale($page->locale);
        $form->setDefaults($page->toArray());

        $permForm = $form->getSubForm('page-permissions');
        $permissions = $pageModel->getPermissions($page);
        $permForm->setDefaults($permissions);

        $tagForm = $form->getSubForm('tags');
        // No taggable, make it
        if(!$page->getTaggableId()) {
            $taggableModel = new EmCore_Model_Taggable();
            $taggableModel->registerFor($page);
            $pageModel->save($page);
        }

        $tagForm->setTaggable($page->getTaggable());


        $this->view->form = $form;


    }


    public function createAction()
    {

        $form = new EmAdmin_Form_Page();
        $form->setLocale($this->_getParam('locale'));

        $form->parent_id->setValue($this->_getParam('id'));
        $form->order_id->setValue(1);

        if($this->_getParam('id')) {
            $model = new EmCore_Model_Page();
            $aclElm = $model->find($this->_getParam('id'));
        } else {
            $model = new EmCore_Model_Locale();
            $aclElm = $model->find($this->_getParam('locale'));
        }

        $perms = $model->getPermissions($aclElm);

        $shardModel = new EmCore_Model_Shard();
        $shard = $shardModel->findByIdentifier('Html');
        $form->shard_id->setValue($shard->id);
        $form->layout->setValue('Default');
        $form->visibility->setValue(1);

        $permForm = $form->getSubForm('page-permissions');
        	
        $permForm->setDefaults($perms);

        $this->view->form = $form;

    }


    public function savePartialAction()
    {
        $pageModel = new EmCore_Model_Page();
        $form = new EmAdmin_Form_Page();


        if($this->_getParam('id')) {
            $page = $pageModel->find($this->_getParam('id'));

            if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
                throw new Emerald_Common_Exception('Forbidden', 403);
            }
            	
            $form->setLocale($page->locale);
            	
            $naviModel = new EmCore_Model_Navigation();
            $navi = $naviModel->getNavigation();

            if($form->isValidPartial($this->_getAllParams())) {
                foreach($form->getValues() as $key => $value) {
                    if($value) {
                        $page->$key = $value;
                    }
                }
                // $page->setFromArray($form->getValues());
                $pageModel->save($page);

                $msg = new Emerald\Base\Messaging\Message(Emerald\Base\Messaging\Message::SUCCESS, 'Ok');

            } else {
                $msg = new Emerald\Base\Messaging\Message(Emerald\Base\Messaging\Message::FAILURE, 'Failed');
                $msg->errors = $form->getMessages();
            }

        } else {
            $msg = new Emerald\Base\Messaging\Message(Emerald\Base\Messaging\Message::FAILURE, 'Failed');
        }
        $this->view->message = $msg;
    }





    public function saveAction()
    {

        $pageModel = new EmCore_Model_Page();

        $form = new EmAdmin_Form_Page();

        if($this->_getParam('id')) {
            	
            $action = 'edit';
            	
            $page = $pageModel->find($this->_getParam('id'));
            	
            $aclModel = new EmCore_Model_Page();
            $aclElm = $page;
            	
            $form->setLocale($page->locale);
        } else {
            	
            $action = 'create';
            	
            $parentId = $this->_getParam('parent_id');
            $page = new EmCore_Model_PageItem();
            if(is_numeric($parentId)) {
                $parentPage = $pageModel->find($this->_getParam('parent_id'));
                $form->setLocale($parentPage->locale);

                $aclModel = new EmCore_Model_Page();
                $aclElm = $aclModel->find($parentId);

            } else {
                $form->setLocale($parentId);
                $aclModel = new EmCore_Model_Locale();
                $aclElm = $aclModel->find($parentId);
            }

        }

        if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $aclElm, 'write')) {
            throw new Emerald_Common_Exception('Forbidden', 403);
        }

        $naviModel = new EmCore_Model_Navigation();
        $navi = $naviModel->getNavigation();

        if(!$form->isValid($this->_getAllParams())) {
            	
            $msg = new Emerald\Base\Messaging\Message(Emerald\Base\Messaging\Message::FAILURE, 'Save failed.');
            $msg->errors = $form->getMessages();
            	
        } else {
            $page->setFromArray($form->getValues());
            	
            $perms = $form->getSubForm('page-permissions')->getValues();

            // Tags
            $tags = $form->getSubForm('tags')->getValues();
            $taggableModel = new EmCore_Model_Taggable();
            	
            $taggable = $taggableModel->registerFor($page);
            $taggable->setFromString($tags['tags']['tags']);
            $taggableModel->save($taggable);
            	
            $pageModel->save($page, $perms['pagepermissions']);

            if($form->mirror->getValue()) {
                $sitemapModel = new EmAdmin_Model_Sitemap();
                $sitemapModel->mirror($page, $action);
            }
            	
            $msg = new Emerald\Base\Messaging\Message(Emerald\Base\Messaging\Message::SUCCESS, 'Save ok.');
            $msg->page_id = $page->id;
        }


        $this->view->message = $msg;

    }






}
