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
		//'DebugKit.Toolbar'
	);

	public function beforeFilter() {
		$this->Session->delete('Config.language'); // For debugging.

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
