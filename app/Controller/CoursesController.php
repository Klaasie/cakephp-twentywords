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
	public $uses = array('Language', 'Course', 'Input', 'Statistic', 'CategoriesNed', 'CategoriesSpa', 'Sentences_ned', 'Sentences_spa');


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
		// Logic
	}
}
?>