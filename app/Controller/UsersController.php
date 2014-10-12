<?php
// app/Controller/UsersController.php
App::uses('AppController', 'Controller');

class UsersController extends AppController {

	public $uses = array('User','Language', 'Course');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('add', 'logout');
	}

	public function login() {
		if ($this->request->is('post')) {
			// Enabling login by username / email
			$user = $this->User->findByUsername($this->request->data['User']['username']);
			if (empty($user)) {
				$user = $this->User->findByEmail($this->request->data['User']['username']);

				if (empty($user)) {
					$this->Session->setFlash(__('Verkeerde gebruikersnaam, e-mail adres of wachtwoord'));
					return $this->redirect(array('controller' => 'pages', 'action' => 'display'));
				}

				$this->request->data['User']['username'] = $user['User']['username'];
			}

			if ($this->Auth->login()) {
				return $this->redirect($this->Auth->redirect());
			}
			$this->Session->setFlash(
				__('Verkeerde gebruikersnaam, e-mail adres of wachtwoord'),
				'default',
				array('class' => 'alert alert-danger')
			);
			return $this->redirect(array('controller' => 'pages', 'action' => 'display'));
		}
	}

	public function logout() {
		return $this->redirect($this->Auth->logout());
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

}
?>