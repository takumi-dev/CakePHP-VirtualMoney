<?php
/**
 * VirtualMoneyFixture
 *
 */
App::uses('VirtualMoney', 'VirtualMoney.Model');
class VirtualMoneyFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'VirtualMoney.VirtualMoney');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '5312fecf-5df8-47af-bd52-3e8831d4c5d0',
			'model' => 'User',
			'foreign_key' => '1',
			'price' => 1000,
			'description' => 'null',
			'extra' => 'null',
			'deleted' => null,
			'created' => '2014-03-02 18:50:07'
		),
		array(
			'id' => '5312fecf-2ffc-4b9c-9bea-3e8831d4c5d0',
			'model' => 'User',
			'foreign_key' => '1',
			'price' => 2000,
			'description' => 'null',
			'extra' => 'null',
			'deleted' => null,
			'created' => '2014-03-02 18:50:08'
		),
		array(
			'id' => '5312fecf-b890-46cc-80f4-3e8831d4c5d0',
			'model' => 'User',
			'foreign_key' => '1',
			'price' => 3000,
			'description' => 'null',
			'extra' => 'null',
			'deleted' => null,
			'created' => '2014-03-02 18:50:07'
		),
		array(
			'id' => '5312fecf-72c0-458b-91d3-3e8831d4c5d0',
			'model' => 'User',
			'foreign_key' => '2',
			'price' => 4000,
			'description' => 'null',
			'extra' => 'null',
			'deleted' => null,
			'created' => '2014-03-02 18:50:07'
		),
		array(
			'id' => '5312fecf-c9b8-4c89-8175-3e8831d4c5d0',
			'model' => 'User',
			'foreign_key' => '2',
			'price' => 5000,
			'description' => 'null',
			'extra' => 'null',
			'deleted' => null,
			'created' => '2014-03-02 18:50:07'
		),
		array(
			'id' => '5312fecf-669c-4c15-8610-3e8831d4c5d0',
			'model' => 'User',
			'foreign_key' => '3',
			'price' => 6000,
			'description' => 'null',
			'extra' => 'null',
			'deleted' => null,
			'created' => '2014-03-02 18:50:07'
		),
		array(
			'id' => '5312fecf-d75c-4cd2-8d19-3e8831d4c5d0',
			'model' => 'User',
			'foreign_key' => '4',
			'price' => 7000,
			'description' => 'null',
			'extra' => 'null',
			'deleted' => null,
			'created' => '2014-03-02 18:50:07'
		),
		array(
			'id' => '5312fecf-5ba4-4825-b39f-3e8831d4c5d0',
			'model' => 'User',
			'foreign_key' => '5',
			'price' => 8000,
			'description' => 'null',
			'extra' => 'null',
			'deleted' => null,
			'created' => '2014-03-02 18:50:07'
		),
	);

}
