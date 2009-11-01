<?php
class Admin_IndexController extends Emerald_Controller_AdminAction 
{
	public function indexAction()
	{
		if(Emerald_Server::getInstance()->getConfig()->dashboard->newsFeed) {
			
			try {
				$feed = Zend_Feed::import(Emerald_Server::getInstance()->getConfig()->dashboard->newsFeed);	
			} catch(Exception $e) {
				$feed = null;
			}
			
			$this->view->feed = $feed;
									
		}
		
		
		/* Tested some imap widget idea...
		
		$mail = new Zend_Mail_Storage_Imap(array('host'     => 'mail.only4fun.org',
                                         'user'     => 'puhemies@diktaattoriporssi.com',
                                         'password' => ''));
		
		
		Zend_Debug::dump($mail);
		
		
		Zend_Debug::dump($mail->count());

		$msg = $mail->getMessage($mail->count());
				
		print $msg->subject;
		*/
				
	}
}