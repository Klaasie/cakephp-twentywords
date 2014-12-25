<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $useDbConfig = 'live';

	public $uses = array('User');

	public $components = array(
		'Session',
		'Auth' => array(
			'loginRedirect' => '/dashboard/',
			'logoutRedirect' => array(
				'controller' => 'pages',
				'action' => 'display',
				'home'
			)
		),
		'Cookie',
		//'DebugKit.Toolbar'
	);

	public function beforeFilter() {
		$this->Session->delete('Config.language'); // For debugging.

		if(!AuthComponent::user('id')){
			if($rememberme = $this->Cookie->read('twentywords')){
				// Login for cookie reminder.
				$rememberMe = $this->Cookie->read('twentywords');

				$currentIp = Security::hash($_SERVER['REMOTE_ADDR'], 'sha1', true); // Want to use this for future checking

				$user = $this->User->find('first', array('conditions' => array('hash' => $rememberMe['hash'])));

				if(!empty($user)) {		// If user is found
					// At this point I think we can safely assume the user is in fact him/herself.
					if($this->Auth->login($user['User'])){
						
						// Updating cookie..
						$hash = md5(uniqid($user['User']['email'], true));				// Unique hash for double security.

						$this->Cookie->write('twentywords', array('ip' => $rememberMe['ip'], 'hash' => $hash));

						$this->User->id = $user['User']['id'];
						$this->User->save(array('hash' => $hash));

						// redirecting user to dashboard.
						return $this->redirect($this->Auth->redirect());
					}
				}
			}
		}

		$user = $this->User->findById($this->Auth->User('id'));
		if(isset($user['User'])){
			$user = $user['User'];
			$this->Session->write('User', $user);
		}

		Configure::write('Config.language', 'eng'); // Default

		if($this->Session->read('User.language')){
			$this->Session->write('Config.language', $this->Session->read('User.language'));
		}else{
			$this->_checkBrowserLanguage(); // Getting browser language
		}

		// Settings
		$this->Auth->authError= __('Je bent niet bevoegd om die pagina te bezoeken.');

	}

	/**
	 * Read the browser language and sets the website language to it if available. 
	 * 
	 */
	protected function _checkBrowserLanguage(){
		if(!$this->Session->check('Config.language')){
			
			//checking the 1st favorite language of the user's browser 
			$browserLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

			//available languages
			switch ($browserLanguage){
				case "en":
					$this->Session->write('Config.language', 'eng');
					break;
				case "nl":
					$this->Session->write('Config.language', 'ned');
					break;
				default:
					$this->Session->write('Config.language', 'eng');
			}
		}
	} 
}
