<?php
// app/Model/User.php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {

	public function beforeSave($options = array()) {
		if(!empty($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password'], 'sha1', true);
		} else {
			unset($this->data[$this->alias]['password']);
		}
		//return true;
		//$this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password'], 'sha1', true);
		return true;
	}

	public $validate = array(
		'username' => array(
			'rule'    => 'isUnique',
			'required' => array(
				'rule' => array('notEmpty')
			)
		),
		'email' => array(
			'rule'    => 'isUnique',
			'required' => array(
				'rule' => array('notEmpty')
			)
		),
		'password' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'on' => 'create',  // we only need this validation on create
			)
		)
	);
}
?>