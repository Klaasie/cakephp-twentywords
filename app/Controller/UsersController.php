<?php
// app/Controller/UsersController.php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class UsersController extends AppController {

	/**
	 * Loading components
	 */
	public $components = array('Cookie');


	/**
	 * Loading models
	 */
	public $uses = array('User','Language', 'Course');

	/**
	 * beforeFilter();
	 *
	 * Executes before a user page loads.
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('add', 'logout', 'login', 'resetPassword', 'rememberMeLogin');
	}

	/**
	 * login()
	 *
	 * Processes the login form
	 */
	public function login() {

		// Check if post is found.
		if ($this->request->is('post')) {

			// Enabling login by username / email
			$user = $this->User->findByUsername($this->request->data['User']['username']);
			if (empty($user)) {
				$user = $this->User->findByEmail($this->request->data['User']['username']);

				if (empty($user)) {
					// No user found.
					$this->Session->setFlash(
						__('Verkeerde gebruikersnaam, e-mail adres of wachtwoord'),
						'default',
						array('class' => 'alert alert-danger')
					);
					return $this->redirect('/login/');
				}

				$this->request->data['User']['username'] = $user['User']['username'];
			}

			if ($this->Auth->login()) {
				// Check if they want to be remembered.
				if($this->request->data['User']['rememberme']){
					$ip = Security::hash($_SERVER['REMOTE_ADDR'], 'sha1', true);	// hashing ip for extra security.
					$hash = md5(uniqid($user['User']['email'], true));				// Unique hash for double security.

					$this->Cookie->write('twentywords', array('ip' => $ip, 'hash' => $hash));

					$this->User->id = $user['User']['id'];
					$this->User->save(array('ipaddress' => $ip, 'hash' => $hash));
				}

				// Redirect user to dashboard.
				return $this->redirect($this->Auth->redirect());
			}

			// Wrong combination
			$this->Session->setFlash(
				__('Verkeerde gebruikersnaam, e-mail adres of wachtwoord'),
				'default',
				array('class' => 'alert alert-danger')
			);
			return $this->redirect('/login/');
		}
	}

	/**
	 * rememberMeLogin()
	 *
	 * Executes when a cookie is found.
	 */
	public function rememberMeLogin(){
		// Login for cookie reminder.
		$rememberMe = $this->Cookie->read('twentywords');

		$currentIp = Security::hash($_SERVER['REMOTE_ADDR'], 'sha1', true); // Want to use this for future checking

		$user = $this->User->find('first', array('conditions' => array('hash' => $rememberMe['hash'])));

		if(!empty($user)) {		// If user is found
			// At this point I think we can safely assume the user is in fact him/herself.
			if($this->Auth->login($user)){

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

	/**
	 * logout()
	 *
	 * Destroys user session
	 */
	public function logout() {
		// Remove potential cookie.
		$this->Cookie->delete('twentywords');
		$this->User->id = $this->Session->read('User.id');
		$this->User->save(array('ipaddress' => NULL, 'hash' => NULL));

		// Logout
		return $this->redirect($this->Auth->logout());
	}

	/**
	 * resetPassword()
	 *
	 * Method to sent a new password to the user.
	 */
	public function resetPassword() {

		if($data = $this->data){
			if($user = $this->User->findByEmail($data['User']['email'])){
				$newPassword = $this->generatePassword();
				$data['User']['password'] = $newPassword;

				$this->User->id = $user['User']['id'];
				if($this->User->save($data)){
					$Email = new CakeEmail();
					$Email->emailFormat('html');
					$Email->template('sendPassword', 'twentywords');
					$Email->viewVars(array('title' => 'Wachwoord gereset','user' => $user, 'password' => $newPassword));
					$Email->from(array('noreply@twentywords.nl' => 'Twenty Words'));
					$Email->to($user['User']['email']);
					$Email->subject(__('Nieuw wachtwoord'));
					$Email->send();
					
					$this->Session->setFlash(
						__('Een nieuw wachtwoord is naar je verstuurd!'),
						'default',
						array('class' => 'alert alert-success')
					);
					
					return $this->redirect('/login/');
				}else{
					$this->Session->setFlash(
						__('Er ging iets mis bij het opslaan!'),
						'default',
						array('class' => 'alert alert-danger')
					);
					return $this->redirect('/resetpassword');
				}

			} else {
				$this->Session->setFlash(
					__('Er is geen account met dat e-mail adres gevonden.'),
					'default',
					array('class' => 'alert alert-danger')
				);
				return $this->redirect('/resetpassword');
			}
		}
	}

	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}

	public function add() {
		$this->autoRender = false;

		if ($this->request->is('post')) {
			
			$this->request->data['User']['role'] = "student";
			$this->request->data['User']['origin'] = "Website";
			$this->request->data['User']['username'] = strtolower($this->request->data['User']['username']);

			$this->User->create();
			if ($this->User->save($this->request->data)) {

				$Email = new CakeEmail();
				$Email->emailFormat('html');
				$Email->template('welcome', 'twentywords');
				$Email->viewVars(array('title' => 'Welcome to Twenty Words!','user' => $this->request->data));
				$Email->from(array('noreply@twentywords.nl' => 'Twenty Words'));
				$Email->to($this->request->data['User']['username']);
				$Email->subject(__('Welcome to Twenty Words!'));
				$Email->send();

				$this->Session->setFlash(
					__('Je account is aangemaakt!'),
					'default',
					array('class' => 'alert alert-success')
				);
				return $this->redirect(array('controller' => 'pages', 'action' => 'display'));
			}

			$this->Session->setFlash(
				__('Er ging iets fout!'),
				'default',
				array('class' => 'alert alert-danger')
			);
			$this->redirect(array('controller' => 'pages', 'action' => 'display'));
		}
	}

	/**
	 * profile()
	 *
	 * Loads profile page
	 * @param $username name of user
	 */
	public function profile($username = NULL){
		if($username){
			$foreign = true;
			$user = $this->User->findByUsername($username);
		}else{
			$foreign = false;
			$user = $this->User->findByUsername($this->Session->read('User.username'));
		}

		$involvedLanguages = array($user['User']['language'], $user['User']['learn']);

		$languages = $this->Language->find('all', array('conditions' => array('shortcode' => $involvedLanguages, 'language' => $this->Session->read('Config.language'))));

		$availableLanguages = $this->Language->find('all', array('conditions' => array('language' => $this->Session->read('Config.language'))));

		$this->set('user', $user);
		$this->set('foreign', $foreign);
		$this->set('languages', $languages);
		$this->set('availableLanguages', $availableLanguages);
		$this->set('jsIncludes', array('users/profileEdit'));
	}

	/**
	 * editUser()
	 *
	 * Method executed when new user data is saved.
	 */
	public function editUser(){
		$this->autoRender = false;

		$data = $this->data;
		$user = $this->User->findByUsername($this->Session->read('User.username'));

		if(strlen($data['User']['username']) == 0){
			$data['User']['username'] = $user['User']['username'];
		}
		if(strlen($data['User']['email']) == 0){
			$data['User']['email'] = $user['User']['email'];
		}
		if(strlen($data['User']['language']) == 0){
			$data['User']['language'] = $user['User']['language'];
		}
		if(strlen($data['User']['learn']) == 0){
			$data['User']['learn'] = $user['User']['learn'];
		}

		$this->User->id = $user['User']['id'];
		if($this->User->save($data)){
			$this->Session->setFlash(
				__('Je gegevens zijn gewijzigd!'),
				'default',
				array('class' => 'alert alert-success')
			);
		}else {
			$this->Session->setFlash(
				__('Er ging iets mis!'),
				'default',
				array('class' => 'alert alert-danger')
			);
		}

		$this->redirect('/profile/');
	}

	/**
	 * editPassword
	 *
	 * Method executed when password is edited.
	 */
	public function editPassword(){
		$this->autoRender = false;

		$data = $this->data;
		$user = $this->User->findById($this->Session->read('User.id'));

		if(isset($data['User']['currentpassword']) && isset($data['User']['newpassword'])){
			$currentPassword = Security::hash($data['User']['currentpassword'], 'sha1', true);
			if($currentPassword == $user['User']['password']){
				// Everything seems in order, save the new password.
				$data['User']['password'] = $data['User']['newpassword'];
				$this->User->id = $user['User']['id'];
				if($this->User->save($data)){
					$this->Session->setFlash(
						__('Je nieuwe wachtwoord is opgeslagen!'),
						'default',
						array('class' => 'alert alert-success')
					);
				}else{
					$this->Session->setFlash(
						__('Oeps! er ging iets mis. Probeer asjeblieft opnieuw.'),
						'default',
						array('class' => 'alert alert-danger')
					);
				}
			}else{
				$this->Session->setFlash(
					__('Je huidige wachtwoord klopt niet.'),
					'default',
					array('class' => 'alert alert-danger')
				);
			}
		}else{
			$this->Session->setFlash(
				__('Vul alle velden in astublieft.'),
				'default',
				array('class' => 'alert alert-danger')
			);
		}

		$this->redirect('/profile/');
	}

	/**
	 * delete()
	 *
	 * Deletes user
	 * @todo Make this actually work
	 */
	public function delete($id = null) {
		$this->request->onlyAllow('post');

		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			return $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		return $this->redirect(array('action' => 'index'));
	}

	/**
	 * generatePassword()
	 *
	 * Generates random password to use in the password reminder.
	 */
	function generatePassword(){
		return substr( str_shuffle( 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$' ) , 0 , 10 ); 
	}

}
?>