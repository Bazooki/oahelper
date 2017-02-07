<?php
App::uses('AppModel', 'Model');
/**
 * XmlLog Model
 *
 */
class XmlLog extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'openid';

	public $belongsTo = array(
		'Follower' => array(
			'className' => 'Follower',
			'foreignKey' => 'follower_id'
		)
	);

}
