<?php
class EmCore_Model_Navigation
{

    static private $_navigation;

    private $_pageModel;

    private $_shardModel;

    /**
     * Returns page model
     *
     * @return EmCore_Model_Page
     */
    public function getPageModel()
    {
        if(!$this->_pageModel) {
            $this->_pageModel = new EmCore_Model_Page();
        }
        return $this->_pageModel;

    }

    /**
     * Returns shard model
     *
     * @return EmCore_Model_Shard
     */
    public function getShardModel()
    {
        if(!$this->_shardModel) {
            $this->_shardModel = new EmCore_Model_Shard();
        }
        return $this->_shardModel;

    }


    public function pageUpdate(EmCore_Model_PageItem $page)
    {
        $navi = $this->clearNavigation()->getNavigation();

        $navi = $navi->findBy("id", $page->id);
        $route = array();
        $beautifurl = array();

        $parent = $navi;

        array_unshift($route, '[' . $parent->id . ']');
        array_unshift($beautifurl, $parent->label);

        while($parent = $parent->getParent()) {

            if(!$parent instanceof Zend_Navigation_Page) {
                break;
            }
            	

            if($parent->id) {
                array_unshift($route, '[' . $parent->id . ']');
                array_unshift($beautifurl, $parent->label);
            }
            	
        }

        $route = implode(";", $route);

        $beautifurler = $page->getLocaleItem()->getOption('beautifurler');

        $beautifurler = Emerald_Common_Beautifurl::factory($beautifurler);
        
        $beautifurl = $page->locale . '/' . $beautifurler->beautify($beautifurl);

        $navi->url = EMERALD_URL_BASE . "/" .  $beautifurl;

        $page->path = $route;
        $page->beautifurl = $beautifurl;

        $this->getPageModel()->getTable()->update(
        array('path' => $route, 'beautifurl' => $beautifurl),
        $this->getPageModel()->getTable()->getAdapter()->quoteInto("id = ?", $page->id)
        );

        $beautifurls = $this->getPageModel()->getCachedBeautifurls();
        foreach($beautifurls as $key => $id) {
            if($id == $page->id) {
                unset($beautifurls[$key]);
                $this->getPageModel()->storeCachedBeautifurls();
                break;
            }
        }

        $this->getPageModel()->storeCached($page->id, $page);
        $this->getPageModel()->storeCached('page_global_' . $page->global_id . '_locale_' . $page->locale, $page->id);
        $this->getPageModel()->clearCached('page_global_' . $page->global_id);

        $this->getPageModel()->storeCached($page->id, $page);

        self::$_navigation = null;

        if($pages = $navi->getPages()) {
            foreach($pages as $child) {
                $childPage = $this->getPageModel()->find($child->id);
                $this->pageUpdate($childPage);
            }
        }
        $navi = $this->clearNavigation()->getNavigation();


    }



    public function clearNavigation()
    {
        $cache = Zend_Registry::get('Emerald_CacheManager')->getCache('default');
        $cache->remove('navigation');

        self::$_navigation = null;
        return $this;
    }



    public function saveNavigation()
    {
        $cache = Zend_Registry::get('Emerald_CacheManager')->getCache('default');
        $cache->save(self::$_navigation, 'navigation');
    }



    /**
     * Returns the whole site navi
     *
     * @return Emerald_Common_Navigation
     */
    public function getNavigation()
    {
        if(!self::$_navigation) {

            $cache = Zend_Registry::get('Emerald_CacheManager')->getCache('default');
            	
            if(!$navi = $cache->load('navigation')) {

                $navi = new Emerald_Common_Navigation();


                $localeModel = new EmCore_Model_Locale();

                $pageTbl = new EmCore_Model_DbTable_Page();

                $locales = $localeModel->findAll();

                foreach($locales as $locale) {
                    	
                    $page = new Zend_Navigation_Page_Uri(
                    array(
							'uri' => EMERALD_URL_BASE . '/' . $locale->locale,
							'label' => $locale->locale,
							'locale' => $locale->locale,
							'locale_root' => $locale->locale,
							'cache_seconds' => 0,
                    )
                    );
                    	
                    	
                    if($startPage = $locale->getOption('page_start')) {
                        $startPage = $pageTbl->find($startPage)->current();
                        if($startPage) {
                            $page->uri = EMERALD_URL_BASE . '/' . $startPage->beautifurl;
                            $page->cache_seconds = $startPage->cache_seconds;
                        }
                    }
                    	
                    	
                    $page->setResource("Emerald_Locale_{$locale->locale}");
                    $page->setPrivilege('read');
                    $page->setVisible(true);
                    	
                    $this->_recurseLocale($page, $locale->locale);
                    	
                    $navi->addPage($page);
                    	
                    	
                }

                $cache->save($navi, 'navigation');
            }
            	
            	
            self::$_navigation = $navi;
            	
        }


        /*
         Zend_Debug::dump(self::$_navigation->toArray());

         die();
         */
        return self::$_navigation;


    }


    protected function _recurseLocale(Zend_Navigation_Page $localePage, $locale)
    {
        $pageModel = new EmCore_Model_Page();
        $taggableModel = new EmCore_Model_Taggable();

        $pages = $pageModel->findAll(array("parent_id IS NULL", "locale = ?" => $locale), "order_id");

        foreach($pages as $page) {

            	
            // recurse
            $pageRes = new Zend_Navigation_Page_Uri(
            array(
					'uri' => ($page->customurl) ? $page->customurl : EMERALD_URL_BASE . '/' . $page->beautifurl,
                    'beautifurl' => $page->beautifurl,
					'label' => $page->title,
					'locale' => $page->locale,
					'id' => $page->id,
					'global_id' => $page->global_id,
					'parent_id' => null,
					'layout' => $page->layout,
					'shard_id' => $page->shard_id,
					'cache_seconds' => $page->cache_seconds,
            )
            );

            if($page->redirect_id) {
                $redirectPage = $pageModel->find($page->redirect_id);
                $pageRes->redirect_uri = EMERALD_URL_BASE . '/' . $redirectPage->beautifurl;
            }
            	
            if(($taggable = $taggableModel->findFor($page)) && $taggable->count()) {

                // Zend_Debug::dump($taggable->tags);

                $pageRes->tags = $taggable->tags;
            }

            if($page->class_css) {
                $pageRes->class = $page->class_css;
            }

            	
            $pageRes->setResource("Emerald_Page_{$page->id}");
            $pageRes->setPrivilege('read');
            $pageRes->setVisible($page->visibility);
            	
            // $this->_pagesFromShard($pageRes);
            	
            $this->_recursePage($pageRes, $pageRes->id);
            $localePage->addPage($pageRes);
        }
    }


    protected function _recursePage($parentPage, $pageId)
    {
        $pageModel = new EmCore_Model_Page();
        $taggableModel = new EmCore_Model_Taggable();

        $pages = $pageModel->findAll(array("parent_id = ?" => $pageId), "order_id");

        foreach($pages as $page) {

            // recurse
            $pageRes = new Zend_Navigation_Page_Uri(
            array(
					'uri' => ($page->customurl) ? $page->customurl : EMERALD_URL_BASE . '/' . $page->beautifurl,
                    'beautifurl' => $page->beautifurl,
                    'label' => $page->title,
					'locale' => $page->locale,
					'id' => $page->id,
					'global_id' => $page->global_id,
					'parent_id' => null,
					'layout' => $page->layout,
					'shard_id' => $page->shard_id,
					'cache_seconds' => $page->cache_seconds,
            )
            );

            if($page->redirect_id) {
                $redirectPage = $pageModel->find($page->redirect_id);
                $pageRes->redirect_uri = EMERALD_URL_BASE . '/' . $redirectPage->beautifurl;
            }

            if(($taggable = $taggableModel->findFor($page)) && $taggable->count()) {
                $pageRes->tags = $taggable->tags;
            }

            if($page->class_css) {
                $pageRes->class = $page->class_css;
            }
            	
            	
            $pageRes->setResource("Emerald_Page_{$page->id}");
            $pageRes->setPrivilege('read');
            $pageRes->setVisible($page->visibility);

            // $this->_pagesFromShard($pageRes);
            	
            $this->_recursePage($pageRes, $pageRes->id);
            $parentPage->addPage($pageRes);
        }
    }



    public function navigationFromShard(Zend_Navigation_Page $parentPage)
    {

        $shard = $this->getShardModel()->find($parentPage->shard_id);
        $pages = $shard->getNavigation($parentPage);
        if($pages) {
            $parentPage->addPages($pages);
        }

    }

}


