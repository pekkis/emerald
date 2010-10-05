<?php
/**
 * Handles install stuff
 * @author pekkis
 *
 */
class EmCore_Model_Install
{

    /**
     * Installs a new Emerald customer
     *
     * @param Emerald_Common_Application_Customer $customer Customer
     * @param EmCore_Form_Install $form Install form
     */
    public function install(Emerald_Common_Application_Customer $customer, EmCore_Form_Install $form)
    {
        $userModel = new EmCore_Model_User();
        $groupTbl = new EmCore_Model_DbTable_Ugroup();
        $userTbl = new EmCore_Model_DbTable_User();
        $ugTbl = new EmCore_Model_DbTable_UserGroup();
        $folderTbl = new Emerald_Filelib_Backend_ZendDbBackend_Table_Folder();

        $db = $groupTbl->getAdapter();

        try {

            $db->beginTransaction();
            $groupTbl->insert(array('name' => 'Anonymous'));

            $groupId = $groupTbl->insert(array('name' => 'Root'));
            $userId = $userTbl->insert(array('email' => $form->email->getValue(), 'passwd' => $userModel->hash($form->email->getValue(), $form->password->getValue()), 'status' => 1));

            $ugTbl->insert(array('user_id' => $userId, 'ugroup_id' => $groupId));

            $folderTbl->insert(array('name' => 'root', 'parent_id' => null));

            $customer->setOption('installed', '1');

            $db->commit();


        } catch(Exception $e) {

            $db->rollBack();
            throw $e;
        }



    }


}

