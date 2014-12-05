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
class ToolsController extends AppController {
/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Tools';

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

	/**
	 * logout()
	 *
	 * Special logout method for cookie testing.
	 */
	public function manualLogout() {
		// Logout
		return $this->redirect($this->Auth->logout());
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