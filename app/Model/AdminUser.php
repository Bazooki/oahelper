<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
/**
 * AdminUser Model
 *
 */
class AdminUser extends AppModel {


/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'username';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'username' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
//				'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'minLength' => array(
				'rule' => array('minLength', '5'),
				'message' => 'Please ensure that your username is at least 5 characters long.',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'unique' => array(
				'rule' => 'isUnique',
				'required' => 'create',
				'message' => 'That username already exists, please choose a different one.',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'alphaNumeric' => array(
				'rule' => 'alphaNumeric',
				'message' => 'Please only use numbers and letters, no symbols.',
				'required' => true,
			),
		),
		'password' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				//'message' => 'Your custom message here',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'minLength' => array(
				'rule' => array('minLength', '8'),
				'message' => 'Please ensure that your password is at least 8 characters long.',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'passMatch' => array(
				'rule' => array('matchPasswords'),
				'message' => 'Your passwords don\'t match.'
			),
		),
		'password_confirm' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please confirm you password.',
				'allowEmpty' => false,
				'required' => true,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations


		),

	);

	public function matchPasswords(){

		if ($this->data['AdminUser']['password'] == $this->data['AdminUser']['password_confirm']){

			return true;

		}

		return false;
	}


	public function beforeSave($options=array()){

		if(isset($this->data[$this->alias]['password'])){
			$this->data[$this->alias]['password'] = (new SimplePasswordHasher)->hash(
				$this->data[$this->alias]['password']
			);

		}


		return true;
	}
}
