<?php
/**
 * Emerald multipurpose view helper
 * 
 * @author pekkis
 * @package Emerald_Cms_View
 *
 */
class Emerald_Cms_View_Helper_Emerald extends Zend_View_Helper_Abstract
{

    private $_user;

    private $_acl;


    public function emerald()
    {
        return $this;
    }


    public function addEverything()
    {
        return $this->addMeta()->addScripts()->addStylesheets();
    }


    public function addMeta()
    {
        $this->view->headMeta()
        ->appendHttpEquiv("Content-Type", "text/html; charset=UTF-8")
        ->appendName("Generator", "Emerald");
        return $this;
    }


    public function addTinyMCE()
    {
        $this->view->headScript()->appendFile(EMERALD_URL_BASE_LIB . '/lib/ext/tinymce/jscripts/tiny_mce/jquery.tinymce.js');
    }

    public function addScripts()
    {
        $this->view->jQuery()
        ->setCdnVersion(Emerald_Cms_Version::JQUERY_VERSION)
        ->setUiCdnVersion(Emerald_Cms_Version::JQUERY_UI_VERSION)
        ->addJavascriptFile(EMERALD_URL_BASE_LIB . '/lib/ext/jquery.jgrowl.js')
        ->addJavascriptFile(EMERALD_URL_BASE_LIB . '/lib/ext/underscore-min.js')
        ->addJavascriptFile(EMERALD_URL_BASE_LIB . '/lib/em-core/emerald.js')
        ->enable()
        ->uiEnable();
        	
        $this->view->headScript()
        ->prependFile(EMERALD_URL_BASE . '/em-core/langlib/index/locale/' . Zend_Registry::get('Zend_Locale') . '/format/js');
        	
        return $this;
    }


    public function addStylesheets()
    {
        $this->view->headLink()
        ->appendStylesheet(EMERALD_URL_BASE_LIB . '/lib/ext/jquery.jgrowl.css')
        ->appendStylesheet(EMERALD_URL_BASE_DATA . '/data/customer.css');

        if($this->view->page && $this->userIsAllowed($this->view->page, 'write')) {
            $this->view->headLink()->appendStylesheet(EMERALD_URL_BASE_LIB . '/lib/em-admin/toolbar.css');
        }
        	
        return $this;
    }


    public function googleAnalytics()
    {
        $analyticsId = Zend_Registry::get('Emerald_Customer')->getOption('google_analytics_id');
        if($analyticsId) {
            $xhtml = '<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("' . $analyticsId . '");
pageTracker._initData();
pageTracker._trackPageview();
</script>';

            return $xhtml;

        }

    }



    public function getUser()
    {
        if(!$this->_user) {
            $this->_user = Zend_Registry::get('Emerald_User');
        }
        return $this->_user;
    }


    public function getAcl()
    {
        if(!$this->_acl) {
            $this->_acl = Zend_Registry::get('Emerald_Acl');
        }
        return $this->_acl;
    }



    public function userIsAllowed($resource, $privilege = null)
    {
        if(!$resource) {
            return false;
        }

        $user = $this->getUser();
        $acl = $this->getAcl();

        return $acl->isAllowed($user, $resource, $privilege);

    }


    public function findActivity($category, $name)
    {
        static $model;
        if(!$model) {
            $model = new EmAdmin_Model_Activity();
        }

        return $model->findByCategoryAndName($category, $name);

    }


    public function toolbar()
    {
        if($this->view->page && $this->userIsAllowed($this->view->page, 'write')) {
            return $this->view->render('helpers/toolbar.phtml');
        }

    }




}
