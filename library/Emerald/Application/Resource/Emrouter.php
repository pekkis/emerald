<?php
/**
 * CMS specific router initialization
 * 
 * @author pekkis
 * @package Emerald_Application
 *
 */
class Emerald_Application_Resource_Emrouter extends Zend_Application_Resource_Router
{

    /**
     * @var EmCore_Model_Shard
     */
    private $_shardModel;

    /**
     * Returns shard model
     * 
     * @return EmCore_Model_Shard
     */
    public function getShardModel()
    {
        if(!$this->_shardModel) {
            return new EmCore_Model_Shard();
        }
    }

    /**
     * Returns shard item for a page
     * 
     * @param Zend_Navigation_Page $page
     * @return EmCore_Model_ShardItem
     */
    public function getShard(Zend_Navigation_Page $page)
    {
        $shardItem = $this->getShardModel()->find($page->shard_id);
        return $shardItem;

    }

    /**
     * @return Zend_Controller_Router_Abstract
     */
    public function init()
    {
        $router = parent::init();
        $cache = $this->getBootstrap()->bootstrap('cache')->getResource('cache')->getCache('default');

        $this->getBootstrap()->bootstrap('translate');

        // Try to fetch page routes from cache, if not go to loop de loop, fetch and cache 'em.
        $pageRoutes = $cache->load('Emerald_PageRoutes');
        if($pageRoutes === false) {
            	
            $this->getBootstrap()->bootstrap('modules')->bootstrap('emdb');
            	
            $pageRoutes = array();
            $naviModel = new EmCore_Model_Navigation();
            	
            $shardModel = new EmCore_Model_Shard();
            	
            $navi = $naviModel->getNavigation();
            	
            $navi = new RecursiveIteratorIterator($navi, RecursiveIteratorIterator::SELF_FIRST);
            foreach($navi as $page) {
                if($page->id && $page->shard_id) {
                    $shard = $this->getShard($page);
                    $routes = $shard->getRoutes($page);
                    foreach($routes as $name => $route) {
                        $pageRoutes[$name] = $route;
                    }
                }
            }
            	
            if($pageRoutes) {
                $router->addRoutes($pageRoutes);
            }
            	
            $cache->save($pageRoutes, 'Emerald_PageRoutes');
            	
            /*
             foreach($navi as $page) {
             if($page->id && $page->shard_id && ($page->parent_id != $page->id)) {
             $naviModel->navigationFromShard($page);
             }
             $naviModel->saveNavigation();
             }
             */
            	
        } else {
            if($pageRoutes) {
                $router->addRoutes($pageRoutes);
            }
        }
        
        return $router;
    }

}