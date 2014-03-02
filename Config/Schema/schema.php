<?php

class VirtualMoneySchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $virtual_moneys = array(
		'id' => array('type' => 'string', 'length' => '36', 'null' => false, 'default' => NULL, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
		
		//for belongs To
		'model' => array('type' => 'string', 'length' => '128', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'string', 'length' => '36', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		
		//positive value for add money, negative value for use money
		'price' => array('type' => 'float', 'null' => false, 'comment' => ''),
		
		//log description
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		
		//extra field for preserving payment api transaction id or something.
		'extra' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		
        'deleted' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'created' => array('type' => 'datetime', 'null' => false, 'collate' => NULL, 'comment' => ''),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'belongsTo' => array('column' => array('model', 'foreign_key'), 'unique' => false),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
	
	public $cashback_requests = array(
		'id' => array('type' => 'string', 'length' => '36', 'null' => false, 'default' => NULL, 'key' => 'primary', 'collate' => NULL, 'comment' => ''),
		
		//for belongs To
		'model' => array('type' => 'string', 'length' => '128', 'null' => false, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		'foreign_key' => array('type' => 'string', 'length' => '36', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		
		//positive value for add money, negative value for use money
		'price' => array('type' => 'float', 'null' => false, 'comment' => ''),
		
		//log description
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		
		//extra field for preserving payment api transaction id or something.
		'extra' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'comment' => '', 'charset' => 'utf8'),
		
		'processed' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		
        'deleted' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'collate' => NULL, 'comment' => ''),
		'created' => array('type' => 'datetime', 'null' => false, 'collate' => NULL, 'comment' => ''),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'belongsTo' => array('column' => array('model', 'foreign_key'), 'unique' => false),
            'processed' => array('column' => 'processed', 'unique' => false),
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}