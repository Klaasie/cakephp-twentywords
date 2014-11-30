<?php
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class CoursesController extends AppController {
/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Courses';

/**
 * Model names
 *
 * @var array
 */
	public $uses = array('User','Language', 'Course', 'Input', 'Statistic', 'CategoriesNed', 'CategoriesSpa', 'SentencesNed', 'SentencesSpa');


	public $components = array('RequestHandler');

/**
 *	Beforefilter
 */
	public function beforeFilter() {
		parent::beforeFilter();
	}

/**
 * Displays the dashboard
 *
 */
	public function dashboard() {
		// Vars
		$appLangShort = $this->Session->read('Config.language');
		$learnLangShort = $this->Session->read('User.learn');
		$appLanguage = $this->Language->find('first', array('conditions' => array('shortcode' => $appLangShort, 'language' => $appLangShort)));
		$learnLanguage = $this->Language->find('first', array('conditions' => array('shortcode' => $learnLangShort, 'language' => $appLangShort)));

		$categoryName = 'Categories'.ucfirst($appLangShort);

		$categories = $this->$categoryName->find('all');

		$statistics = $this->Statistic->find('all', array('conditions' => array('user_id' => $this->Session->read('User.id'))));
		$input = $this->Input->find('all', array('conditions' => array('user_id' => $this->Session->read('User.id'))));

		$this->set('categoryName', $categoryName);
		$this->set('appLanguage', $appLanguage);
		$this->set('learnLanguage', $learnLanguage);
		$this->set('categories', $categories);

		$this->set('statistics', $statistics);
		$this->set('input', $input);
	}

	public function test(){
		$curLang = $this->Session->read('User.language');
		$learnLang = $this->Session->read('User.learn');


		$this->set('curLang', $curLang);
		$this->set('learnLang', $learnLang);
		$this->set('jsIncludes', array('courses/test'));
	}

	public function start(){
		$this->autoRender = false;

		// vars
		$user = $this->User->findById($this->Session->read('User.id'));
		$langCur = 'Sentences'.ucfirst($user['User']['language']);
		$langLearn = 'Sentences'.ucfirst($user['User']['learn']);

		$currentInput = $this->Input->find('first', 
			array('conditions' => 
				array('user_id' => $user['User']['id']),
				'order' => array('id' => 'DESC')
			)
		);

		if(isset($currentInput['Input'])){
			// Resuming
		}else{
			// First time
			$questionsLang = $this->$langCur->find('first', array('conditions' => array('id' => 1)));
			$questionsLearn = $this->$langLearn->find('first', array('conditions' => array('id' => 1)));
		}

		$questions['current'] = $questionsLang[$langCur];
		$questions['learn'] = $questionsLearn[$langLearn];

		return json_encode($questions);
	}

	public function save(){
		$this->autoRender = false;

		$data = array();

		// User data
		$data['Input']['user_id'] = $this->Session->read('User.id');

		$data['Input']['sentence_id'] = $this->data['sentence_id'];
		$data['Input']['good'] = $this->data['good'];
		$data['Input']['false'] = $this->data['false'];

		if($inputExists = $this->Input->find('first', array('conditions' => array('user_id' => $data['Input']['user_id'], 'sentence_id' => $data['Input']['sentence_id'])))){
			// Input already exists
			$this->Input->id = $inputExists['Input']['id'];
			// Update data
			$data['Input']['good'] += $inputExists['Input']['good'];
			$data['Input']['false'] += $inputExists['Input']['false'];
			if($inputExists['Input']['successfull'] == NULL && $data['Input']['good'] >= 1){
				$data['Input']['successfull'] = date();
			}

			$this->Input->save($data);
		} else {
			// This one has not been answered before.
			if($data['Input']['good'] >= 1){
				$data['Input']['successfull'] = date('Y-m-d H:i:s');
			}

			$this->Input->create();
			$this->Input->save($data);
		}

	}

	public function nextQuestion(){
		$this->autoRender = false;

		// vars
		$user = $this->User->findById($this->Session->read('User.id'));
		$langCur = 'Sentences'.ucfirst($user['User']['language']);
		$langLearn = 'Sentences'.ucfirst($user['User']['learn']);

		$currentInput = $this->Input->find('first', 
			array('conditions' => 
				array('user_id' => $user['User']['id']),
				'order' => array('id' => 'DESC')
			)
		);

		$id = $currentInput['Input']['sentence_id'] + 1;

		$questionsLang = $this->$langCur->find('first', array('conditions' => array('id' => $id)));
		$questionsLearn = $this->$langLearn->find('first', array('conditions' => array('id' => $id)));

		$questions['current'] = $questionsLang[$langCur];
		$questions['learn'] = $questionsLearn[$langLearn];

		return json_encode($questions);
	}

	// public function getQuestions(){
	// 	$this->autoRender = false;

	// 	// vars
	// 	$user = $this->User->findById($this->Session->read('User.id'));
	// 	$langCur = 'Sentences'.ucfirst($user['User']['language']);
	// 	$langLearn = 'Sentences'.ucfirst($user['User']['learn']);

	// 	$currentInput = $this->Input->find('first', 
	// 		array('conditions' => 
	// 			array('user_id' => $user['User']['id']),
	// 			'order' => array('id' => 'DESC')
	// 		)
	// 	);

	// 	if(isset($currentInput['Input'])){
	// 		// Resuming
	// 	}else{
	// 		// First time
	// 		$questionsLang = $this->$langCur->find('all', array('conditions' => array('id BETWEEN ? AND ?' => array(1,7))));
	// 		$questionsLearn = $this->$langLearn->find('all', array('conditions' => array('id BETWEEN ? AND ?' => array(1,7))));
	// 	}

	// 	$questions[$langCur] = $questionsLang;
	// 	$questions[$langLearn] = $questionsLearn;

	// 	return json_encode($questions);
	// }
}
?>