<?php
App::uses('AppModel', 'Model');

class CategoriesNed extends AppModel {
	public $useTable = 'categories_ned';

	public $hasMany = array(
		'Subcategories' => array(
			'className' => 'SubcategoriesNed',
			'foreignKey' => 'category_ned_id',
			// 'conditions' => array('SubcategoriesNed.category_ned_id' => '1'),
		)
	);
}
?>