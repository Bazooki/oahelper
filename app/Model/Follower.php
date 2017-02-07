<?php
App::uses('AppModel', 'Model');
/**
 * Follower Model
 *
 */
class Follower extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'nickname';


	public $validate = array(
		'openid' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
//				'message' => 'Your custom message here',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'nickname' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
//				'message' => 'Your custom message here',
				'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	public $hasMany = array(
		'XmlLog' => array(
			'className' => 'XmlLog',
			'foreignKey' => 'follower_id',
//			'conditions' => array('Comment.status' => '1'),
//			'order' => 'Comment.created DESC',
//			'limit' => '5',
//			'dependent' => true
		)
	);

}
