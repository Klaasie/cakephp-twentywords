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

	/**
	 * function to clear all cache data
	 * by default accessible only for admin
	 *
	 * @access Public
	 * @return void
	 */
	public function clear_cache() {
		$this->autoRender = false;
		Cache::clear();
		clearCache();

		$files = array();
		$files = array_merge($files, glob(CACHE . '*')); // remove cached css
		$files = array_merge($files, glob(CACHE . 'css' . DS . '*')); // remove cached css
		$files = array_merge($files, glob(CACHE . 'js' . DS . '*'));  // remove cached js           
		$files = array_merge($files, glob(CACHE . 'models' . DS . '*'));  // remove cached models           
		$files = array_merge($files, glob(CACHE . 'persistent' . DS . '*'));  // remove cached persistent           

		foreach ($files as $f) {
			if (is_file($f)) {
				unlink($f);
			}
		}

		if(function_exists('apc_clear_cache')):      
		apc_clear_cache();
		apc_clear_cache('user');
		endif;

		$this->set(compact('files'));
		$this->layout = 'ajax';
	}

//	Commenting these to prevent them from accidently executing.
/*	public function saveCat() {
		$this->autoRender = false;

		$this->Categories_spa->create();
		$this->Categories_spa->save($this->data);
	}

	public function saveSentence(){
		$this->autoRender = false;


		$data['front'] = htmlentities($this->data['front']);
		$data['word'] = htmlentities($this->data['word']);
		$data['back'] = htmlentities($this->data['back']);

		$this->Sentences_spa->create();
		$this->Sentences_spa->save($data);
	}
*/
}
?>