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
	public $uses = array('User','Language', 'Course', 'Input', 'Statistic', 'CategoriesNed', 'CategoriesSpa', 'SentencesNed', 'SentencesSpa', 'Status', 'Entities');


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

		// If user has not done any courses.
		if(count($statistics) == 0){
			// creating statistics
			$this->Statistic->create();
			$data['user_id'] = $this->Session->read('User.id');
			$data['category_id'] = 1;
			$data['subcategory_id'] = 1;
			$data['status'] = "process";
			$this->Statistic->save($data);

			// Get the latest statistics.
			$statistics = $this->Statistic->find('all', array('conditions' => array('user_id' => $this->Session->read('User.id'))));
		}

		$status = $this->getStatus();

		// Date of status
		if($status != NULL){
			$statusDate = new DateTime($status['Status']['modified']);
		}

		$this->set('categoryName', $categoryName);
		$this->set('appLanguage', $appLanguage);
		$this->set('learnLanguage', $learnLanguage);
		$this->set('categories', $categories);

		$this->set('statistics', $statistics);
		$this->set('status', $status);
		$this->set('input', $input);
	}

	public function test(){
		// Get languages
		$curLang = $this->Session->read('User.language');
		$learnLang = $this->Session->read('User.learn');

		// Set html entities
		$entities = $this->Entities->find('all', array('conditions' => array('languages_shortcode' => $learnLang)));

		$this->set('curLang', $curLang);
		$this->set('learnLang', $learnLang);
		$this->set('entities', $entities);
		$this->set('jsIncludes', array('courses/test'));
	}

	public function start(){
		$this->autoRender = false;

		// Vars
		$data = array();
		$result = array();

		// First check if he is already finished for today.
		$status = $this->getStatus();

		// Date of status
		if($status != NULL){
			$statusDate = new DateTime($status['Status']['modified']);
		}

		if($status == NULL) { // if first time.
			// Create status record.
			$this->Status->create();
			$data['user_id'] = $this->Session->read('User.id');
			$data['sentence_id'] = 1;
			$data['status'] = "progress";
			$this->Status->save($data);

			// Get first question
			return $this->getQuestion(1);

		}else if($status['Status']['status'] == "progress"){
			// Getting all the input available.
			$currentInput = $this->Input->find('all',
				array('conditions' => 
					array(
						'user_id' => $this->Session->read('User.id'),
						'modified >' => $status['Status']['created']
					),
					'order' => array('id' =>'ASC'),
				)
			);

			if($currentInput != NULL){
				$lastRecord = end($currentInput);
				$id = $lastRecord['Input']['sentence_id'] + 1;
			}else{
				$id = $status['Status']['sentence_id'];
			}

			if(count($currentInput) < 7){
				// Still in first bit "today".
				return $this->getQuestion($id);
			}else if(count($currentInput) < 14){
				// In the second bit "yesterday".

				// Logic here to get the 7 previous ones.
				$yesterdaysInput = $this->Input->find('first',
					array('conditions' => 
						array(
							'id <' => $status['Status']['sentence_id'],
							'user_id' => $this->Session->read('User.id'),
							'shown' => 1,
						),
						'order' => array('id' =>'ASC'),
					)
				);

				$id = $yesterdaysInput['Input']['sentence_id'];

				return $this->getQuestion($id);
			}else{
				// In last bit "days before yesterday".

				$remainingIds = $id = $status['Status']['sentence_id'] - 7;
				$belowFifty = $this->Input->query('SELECT * FROM inputs WHERE (`good`/(`good`+`false`))*100 < 50 AND `id` < ' . $remainingIds . ' AND `modified` < "' . $status['Status']['created'] . '" ORDER BY `shown` ASC LIMIT 1;');

				if($belowFifty == NULL){
					$randomSentence = $this->Input->find('first', 
						array( 
							'conditions' => array(
								'id <' => $remainingIds,
								'modified <' => $status['Status']['created']
							), 
							'order' => 'rand()'
						)
					);

					return $this->getQuestion($randomSentence['Input']['sentence_id']);
				}else{
					return $this->getQuestion($belowFifty[0]['inputs']['sentence_id']);
				}

			}
		}else if($status['Status']['status'] == "completed" && $statusDate->format('Y-m-d') != date('Y-m-d')){ // Finshed a day before.
			// Getting all the input available.
			$currentInput = $this->Input->find('all',
				array('conditions' => 
					array(
						'user_id' => $this->Session->read('User.id'),
						'modified >' => $status['Status']['created']
					),
					'order' => array('id' =>'ASC'),
				)
			);

			$lastRecord = end($currentInput);
			$id = $lastRecord['Input']['sentence_id'] + 1;

			// Starting a new daily course.
			$this->Status->create();
			$data['user_id'] = $this->Session->read('User.id');
			$data['sentence_id'] = $id;
			$data['status'] = "progress";
			$this->Status->save($data);

			return $this->getQuestion($id);
		}else if($status['Status']['status'] == "completed" && $statusDate->format('Y-m-d') == date('Y-m-d')){ // Finished for today.
			$result['result'] = 'completed';
			return base64_encode(json_encode($result));
		}else{
			$result['result'] = 'error';
			$result['message'] = 'Something went wrong during start()';
			return base64_encode(json_encode($result));
		}

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
			$data['Input']['shown'] = $inputExists['Input']['shown'] + 1;
			$data['Input']['good'] += $inputExists['Input']['good'];
			$data['Input']['false'] += $inputExists['Input']['false'];
			if($inputExists['Input']['good'] == 0 && $data['Input']['good'] == 1){
				$data['Input']['successfull'] = date('Y-m-d H:i:s');
			}

			$this->Input->save($data);
		} else {
			// This one has not been answered before.
			$data['Input']['shown'] = 1;
			if($data['Input']['good'] >= 1){
				$data['Input']['successfull'] = date('Y-m-d H:i:s');
			}

			$this->Input->create();
			$this->Input->save($data);
		}

	}

	public function nextQuestion(){
		$this->autoRender = false;

		// Vars
		$result = array();

		$status = $this->getStatus();

		$lastInput = $this->Input->find('all',
			array('conditions' => 
				array(
					'user_id' => $this->Session->read('User.id'),
					'modified >' => $status['Status']['created']
				),
				'order' => array('id' =>'ASC'),
			)
		);

		$lastRecord = end($lastInput);
		$id = $lastRecord['Input']['sentence_id'] + 1;

		// couple of exceptions.
		if($lastRecord['Input']['sentence_id'] == 7 && count($lastInput) == 7){ // This is the very first time.
			// Update input row
			$this->Status->id = $status['Status']['id'];
			$this->Status->save(array('status' => 'completed'));

			$result['result'] = 'completed';
			return base64_encode(json_encode($result));
		} else if($lastRecord['Input']['sentence_id'] == 14 && count($lastInput) == 14){
			// Update input row
			$this->Status->id = $status['Status']['id'];
			$this->Status->save(array('status' => 'completed'));

			$result['result'] = 'completed';
			return base64_encode(json_encode($result));
		}

		if(count($lastInput) < 7){
			// Still in first bit "today".

			return $this->getQuestion($id);

		}else if(count($lastInput) < 14){
			// In the second bit "yesterday".

			// Logic here to get the 7 previous ones.
			$yesterdaysInput = $this->Input->find('first',
				array('conditions' => 
					array(
						'id <' => $status['Status']['sentence_id'],
						'user_id' => $this->Session->read('User.id'),
						'shown' => 1,
					),
					'order' => array('id' =>'ASC'),
				)
			);

			$id = $yesterdaysInput['Input']['sentence_id'];

			return $this->getQuestion($id);

		}else if(count($lastInput) < 20){
			// In last bit "days before yesterday".

			$remainingIds = $id = $status['Status']['sentence_id'] - 7;
			$belowFifty = $this->Input->query('SELECT * FROM inputs WHERE (`good`/(`good`+`false`))*100 < 50 AND `id` < ' . $remainingIds . ' AND `modified` < "' . $status['Status']['created'] . '" ORDER BY `shown` ASC LIMIT 1;');

			if($belowFifty == NULL){
				$randomSentence = $this->Input->find('first', 
					array( 
						'conditions' => array(
							'id <' => $remainingIds,
							'modified <' => $status['Status']['created']
						), 
						'order' => 'rand()'
					)
				);

				return $this->getQuestion($randomSentence['Input']['sentence_id']);
			}else{
				return $this->getQuestion($belowFifty[0]['inputs']['sentence_id']);
			}

		}elseif(count($lastInput) == 20){
			// Update status row
			$this->Status->id = $status['Status']['id'];
			$this->Status->save(array('status' => 'completed'));

			$result['result'] = 'completed';
			return base64_encode(json_encode($result));

		}else{
			// Assume for now something went wrong.
			$result['result'] = 'error';
			$result['message'] = 'Something went wrong during nextQuestion()';
			return base64_encode(json_encode($result));
		}

	}

	private function getStatus(){
		return $this->Status->find('first', 
				array('conditions' => 
					array('user_id' => $this->Session->read('User.id')),
					'order' => array('id' => 'DESC')
				)
			);
	}

	private function getQuestion($id){
		$langCur = 'Sentences'.ucfirst($this->Session->read('User.language'));
		$langLearn = 'Sentences'.ucfirst($this->Session->read('User.learn'));

		$questionsLang = $this->$langCur->find('first', array('conditions' => array('id' => $id)));
		$questionsLearn = $this->$langLearn->find('first', array('conditions' => array('id' => $id)));

		$questions['current'] = $questionsLang[$langCur];
		$questions['learn'] = $questionsLearn[$langLearn];

		return base64_encode(json_encode($questions));
	}

}
?>