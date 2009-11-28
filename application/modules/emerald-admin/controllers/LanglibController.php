<?php
class EmeraldAdmin_LanglibController extends Emerald_Controller_AdminAction 
{

	
	public function dumpAction()
	{
		$db = Emerald_Server::getInstance()->getDb();
		
		
		$cmd = "mysqldump -uroot -pg04753m135 --routines --triggers Emerald_Server langlib langlib_translation";
				
		$cmd = escapeshellcmd($cmd);
				
		
		$ret = shell_exec  ( $cmd );
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/plain; charset=UTF-8');
		$this->getResponse()->appendBody($ret);
		
		$this->getResponse()->setHeader('Content-disposition',
				"attachment; filename=langlib.sql");
		
	}
	
	
	

	
	public function indexAction()
	{
		
		$db = Emerald_Server::getInstance()->getDb();
		
		$sql = "
		SELECT langlib.id, langlib.path, l1.translation AS fi, l2.translation AS en
		FROM langlib
		LEFT JOIN langlib_translation AS l1
		ON(langlib.id = l1.langlib_id AND l1.language_id = 'fi')
		LEFT JOIN langlib_translation AS l2
		ON(langlib.id = l2.langlib_id AND l2.language_id = 'en')
		ORDER BY langlib.path ASC
		";
		
		$entries = $db->fetchAll($sql);
		
		$this->view->entries = $entries;
		
		
		
	}
	
	public function editAction()
	{
		$db = Emerald_Server::getInstance()->getDb();
		
		$filters = array();
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),
		);
				
		try {
									
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

		
			$sql = "
			SELECT langlib.id, langlib.path, l1.translation AS fi, l2.translation AS en
			FROM langlib
			LEFT JOIN langlib_translation AS l1
			ON(langlib.id = l1.langlib_id AND l1.language_id = 'fi')
			LEFT JOIN langlib_translation AS l2
			ON(langlib.id = l2.langlib_id AND l2.language_id = 'en')
			WHERE langlib.id = :id
			ORDER BY langlib.path ASC
			";
			
			$entry = $db->fetchRow($sql, array('id' => $input->id));
			
			$this->view->entry = $entry;
			
		} catch(Exception $e) {
			
			throw $e;
			
		}
	
		
		
		
		
		
	}
	
	
	
	public function createAction()
	{
		
		$filters = array();
		$validators = array(
			'path' => array(new Zend_Validate_Regex('/^[a-z0-9_\/]+$/'), 'presence' => 'required'),
			'en' => array(new Zend_Validate_StringLength(1, 5000), 'presence' => 'optional', 'allowEmpty' => true),
			'fi' => array(new Zend_Validate_StringLength(1, 5000), 'presence' => 'optional', 'allowEmpty' => true),			
		);
				
		try {
									
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			
			$db = Emerald_Server::getInstance()->getDb();
			
			$db->beginTransaction();
			
			try {
				
				$sql = "INSERT INTO langlib (path) VALUES(:path)";
				$res = $db->query($sql, array('path' => $input->path));
								
				$id = $db->lastInsertId();
								
				if($input->en) {
					$sql = "INSERT INTO langlib_translation (langlib_id, language_id, translation) VALUES(:langlib_id, :language_id, :translation)";					
					$db->query($sql, array('langlib_id' => $id, 'language_id' => 'en', 'translation' => $input->en));					
				}
				
				if($input->fi) {
					$sql = "INSERT INTO langlib_translation (langlib_id, language_id, translation) VALUES(:langlib_id, :language_id, :translation)";					
					$db->query($sql, array('langlib_id' => $id, 'language_id' => 'fi', 'translation' => $input->fi));					
				}
					
								
				
				$db->commit();

				$this->getResponse()->setRedirect('/admin/langlib', 302);
								
			} catch(Exception $e) {
				$db->rollback();
				
				throw $e;
				
			}
			
			
			
			
			
			
			
			
						
			
		} catch(Exception $e) {
			
			Zend_Debug::dump($input->getMessages());
			
			die('not like this... not... like... THIS!');
		}
		
		
		
		
	}
	
	public function saveAction()
	{
		
		$filters = array();
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),
			'en' => array(new Zend_Validate_StringLength(1, 5000), 'presence' => 'optional', 'allowEmpty' => true),
			'fi' => array(new Zend_Validate_StringLength(1, 5000), 'presence' => 'optional', 'allowEmpty' => true),			
		);
				
		try {
									
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			
			$db = Emerald_Server::getInstance()->getDb();
			
			$db->beginTransaction();
			
			try {
								
				$id = $input->id;
								
				if($input->en) {
					$sql = "REPLACE INTO langlib_translation (langlib_id, language_id, translation) VALUES(:langlib_id, :language_id, :translation)";					
					$db->query($sql, array('langlib_id' => $id, 'language_id' => 'en', 'translation' => $input->en));					
				}
				
				if($input->fi) {
					$sql = "REPLACE INTO langlib_translation (langlib_id, language_id, translation) VALUES(:langlib_id, :language_id, :translation)";					
					$db->query($sql, array('langlib_id' => $id, 'language_id' => 'fi', 'translation' => $input->fi));					
				}
												
				$db->commit();

				$this->getResponse()->setRedirect('/admin/langlib', 302);
								
			} catch(Exception $e) {
				$db->rollback();
				
				throw $e;
				
			}
			
			
			
			
			
			
			
			
						
			
		} catch(Exception $e) {
			
			Zend_Debug::dump($input->getMessages());
			
			die('not like this... not... like... THIS!');
		}

	}
	public function jsAction()
	{
		
		$filters = array();
		$validators = array(
			'id' => array(array('InArray',Array("en","fi")), 'presence' => 'optional', 'default' => 'en'),
		);
				
		try {
									
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			$locale = Zend_Registry::get('Zend_Locale');
			$translate = Zend_Registry::get('Zend_Translate');
			
			$langlib = $translate->getMessages($input->id);
			$js = Zend_JSON::encode($langlib);
			
			$message = "Emerald.Localization.init('{$input->id}', {$js});";
			
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
			$this->getResponse()->appendBody($message); 
			
		}catch(Exception $e){
			
			
			throw new Emerald_Exception('Not Found', 404);
		}	
	}
	
	
	
}
?>