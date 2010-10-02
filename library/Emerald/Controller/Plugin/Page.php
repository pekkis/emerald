<?php
/**
 * Filters internal links to beautifurls when a page is rendered
 *
 * @author pekkis
 * @package Emerald_Controller
 *
 */
class Emerald_Controller_Plugin_Page extends Zend_Controller_Plugin_Abstract
{

    public function dispatchLoopShutdown()
    {
        $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Emerald_Cms_Filter_Beautifurl());
        $body = $this->getResponse()->getBody();
        $body = $filterChain->filter($body);
        $this->getResponse()->setBody($body);

    }

}
