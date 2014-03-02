<?php
App::uses('VirtualMoneyAppModel', 'VirtualMoney.Model');
App::uses('VirtualMoney', 'VirtualMoney.Model');

/**
 * VirtualMoneyAppModel Test Case
 *
 */
class VirtualMoneyAppModelTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.virtual_money.virtual_money',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->VirtualMoney = ClassRegistry::init('VirtualMoney.VirtualMoney');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->VirtualMoney);

		parent::tearDown();
	}

/**
 * testFindAllByBelongsTo method
 *
 * @return void
 */
	public function testFindAllByBelongsTo() {
        $this->assertEquals($this->VirtualMoney->findAllByBelongsTo('User', 10000), array());
        $this->assertEquals($this->VirtualMoney->findAllByBelongsTo('User', 3), array(
           array(
               'VirtualMoney' => array(
                    'id' => '5312fecf-669c-4c15-8610-3e8831d4c5d0',
                    'model' => 'User',
                    'foreign_key' => '3',
                    'price' => '6000',
                    'description' => 'null',
                    'extra' => 'null',
                    'deleted' => null,
                    'created' => '2014-03-02 18:50:07'
                )
           )
        ));
        $this->assertEquals($this->VirtualMoney->findAllByBelongsTo('User', 1, array('conditions' => array('VirtualMoney.price' => '1000'))), array(
           array(
               'VirtualMoney' => array(
                    'id' => '5312fecf-5df8-47af-bd52-3e8831d4c5d0',
                    'model' => 'User',
                    'foreign_key' => '1',
                    'price' => 1000,
                    'description' => 'null',
                    'extra' => 'null',
                    'deleted' => null,
                    'created' => '2014-03-02 18:50:07'
                )
           )
        ));
        $this->assertEquals($this->VirtualMoney->findAllByBelongsTo('User', 1, array('fields' => array('id'), 'conditions' => array('VirtualMoney.price' => '1000'))), array(
           array(
               'VirtualMoney' => array(
                    'id' => '5312fecf-5df8-47af-bd52-3e8831d4c5d0',
                )
           )
        ));
	}

/**
 * testDeleteAllByBelongsTo method
 *
 * @return void
 */
	public function testDeleteAllByBelongsTo() {
        $this->assertTrue($this->VirtualMoney->deleteAllByBelongsTo('User', 10000), 0);
        $this->assertTrue($this->VirtualMoney->deleteAllByBelongsTo('User', 3), 0);
        $this->assertTrue($this->VirtualMoney->deleteAllByBelongsTo('User', 2), 0);
        
        $this->assertEquals($this->VirtualMoney->find('count', array('conditions' => array('model' => 'User', 'foreign_key' => 10000))), 0);
        $this->assertEquals($this->VirtualMoney->find('count', array('conditions' => array('model' => 'User', 'foreign_key' => 3))), 0);
        $this->assertEquals($this->VirtualMoney->find('count', array('conditions' => array('model' => 'User', 'foreign_key' => 2))), 0);
        
        $this->assertTrue(($this->VirtualMoney->find('count') > 0));
	}

/**
 * testFindLastByBelongsTo method
 *
 * @return void
 */
	public function testFindLastByBelongsTo() {
        $this->assertEquals($this->VirtualMoney->findLastByBelongsTo('User', 10000), array());
        $this->assertEquals($this->VirtualMoney->findLastByBelongsTo('User', 3), array(
               'VirtualMoney' => array(
                    'id' => '5312fecf-669c-4c15-8610-3e8831d4c5d0',
                    'model' => 'User',
                    'foreign_key' => '3',
                    'price' => '6000',
                    'description' => 'null',
                    'extra' => 'null',
                    'deleted' => null,
                    'created' => '2014-03-02 18:50:07'
                )
        ));
        $this->assertEquals($this->VirtualMoney->findLastByBelongsTo('User', 1, array('fields' => array('id'))), array(
               'VirtualMoney' => array(
                    'id' => '5312fecf-2ffc-4b9c-9bea-3e8831d4c5d0',
                )
        ));
        $this->assertEquals($this->VirtualMoney->findLastByBelongsTo('User', 1), array(
               'VirtualMoney' => array(
                    'id' => '5312fecf-2ffc-4b9c-9bea-3e8831d4c5d0',
                    'model' => 'User',
                    'foreign_key' => '1',
                    'price' => 2000,
                    'description' => 'null',
                    'extra' => 'null',
                    'deleted' => null,
                    'created' => '2014-03-02 18:50:08'
                ),
        ));
	}

/**
 * testSum method
 *
 * @return void
 */
	public function testSum() {
        $this->assertEquals($this->VirtualMoney->sum('User', 10000), 0);
        $this->assertEquals($this->VirtualMoney->sum('User', 1, array('id' => null)), 0);
        $this->assertEquals($this->VirtualMoney->sum('User', 1), 6000);
        $this->assertEquals($this->VirtualMoney->sum('User', 3), 6000);
        $this->assertEquals($this->VirtualMoney->sum('User', 1, array('price' => '1000')), 1000);
	}

/**
 * testAppend method
 *
 * @return void
 */
	public function testAppend() {
        $this->assertNull($this->VirtualMoney->append('User', 1, new stdClass()));
        $this->assertNull($this->VirtualMoney->append('User', 1, array(400)));
        $this->assertNull($this->VirtualMoney->append('User', 1, 'string_something'));
        
        $sum = $this->VirtualMoney->sum('User', 1);
        $this->assertTrue((bool)$this->VirtualMoney->append('User', 1, 540.45));
        $sum2 = $this->VirtualMoney->sum('User', 1);
        $this->assertEquals($sum, ($sum2 - 540.45));
        $this->assertTrue((bool)$this->VirtualMoney->append('User', 1, '-540.45'));
        $sum3 = $this->VirtualMoney->sum('User', 1);
        $this->assertEquals($sum, ($sum3 + 540.45));
        
        $this->assertTrue((bool)$this->VirtualMoney->append('User', 1, 540.45, 'For payment'));
        $this->assertEquals($this->VirtualMoney->field('description', array('id' => $this->VirtualMoney->id)), 'For payment');
        
        $this->assertTrue((bool)$this->VirtualMoney->append('User', 1, -540.45, 'For payment', array('payment_id' => '450')));
        $this->assertEquals($this->VirtualMoney->field('description', array('id' => $this->VirtualMoney->id)), 'For payment');
        $this->assertEquals($this->VirtualMoney->field('extra', array('id' => $this->VirtualMoney->id)), serialize(array('payment_id' => '450')));
	}

/**
 * testAppendMany method
 *
 * @return void
 */
	public function testAppendMany() {
        $sum = $this->VirtualMoney->sum('User', 1);
        $data = array('VirtualMoney' => array(
            'model' => 'User',
            'foreign_key' => '1',
            'price' => 540.45,
            'description' => 'For payment',
            'extra' => array('payment_id' => '450')
        ));
        $this->assertTrue((bool)$this->VirtualMoney->appendMany($data));
        $sum2 = $this->VirtualMoney->sum('User', 1);
        $this->assertEquals($sum, ($sum2 - 540.45));
        $this->assertEquals($this->VirtualMoney->field('description', array('id' => $this->VirtualMoney->id)), 'For payment');
        $this->assertEquals($this->VirtualMoney->field('extra', array('id' => $this->VirtualMoney->id)), serialize(array('payment_id' => '450')));
        
        $data = array('VirtualMoney' => array(
            'model' => 'User',
            'foreign_key' => '1',
            'price' => -540.45,
            'description' => 'For payment',
            'extra' => array('payment_id' => '450')
        ));
        $this->assertTrue((bool)$this->VirtualMoney->appendMany($data));
        $sum3 = $this->VirtualMoney->sum('User', 1);
        $this->assertEquals($sum, $sum3);
        
        //validation errors
        $data = array('VirtualMoney' => array(
            'model' => 'User',
            //'foreign_key' => '1',
            'price' => 540.45,
            'description' => 'For payment',
            'extra' => array('payment_id' => '450')
        ));
        $this->assertFalse((bool)$this->VirtualMoney->appendMany($data));
        
        $data = array('VirtualMoney' => array(
            //'model' => 'User',
            'foreign_key' => '1',
            'price' => 540.45,
            'description' => 'For payment',
            'extra' => array('payment_id' => '450')
        ));
        $this->assertFalse((bool)$this->VirtualMoney->appendMany($data));
        
        $data = array('VirtualMoney' => array(
            'model' => 'User',
            'foreign_key' => '1',
            //'price' => 540.45,
            'description' => 'For payment',
            'extra' => array('payment_id' => '450')
        ));
        $this->assertFalse((bool)$this->VirtualMoney->appendMany($data));
        
        //invalid method
        $this->setExpectedException('BadMethodCallException');
        $this->assertFalse($this->VirtualMoney->appendMany($data, array(
            'saveMethod' => 'noMethod'
        )));
        $this->assertFalse($this->VirtualMoney->appendMany($data, array(
            'saveMethod' => 'find'
        )));
	}

/**
 * testFindQueryForBelongsTo method
 *
 * @return void
 */
	public function testFindQueryForBelongsTo() {
        $query = $this->VirtualMoney->findQueryForBelongsTo('User', 4);
        
        $sum = $this->VirtualMoney->sum('User', 4);
        $data = $this->VirtualMoney->find('all', $query);
        $this->assertTrue(isset($data[0][$this->VirtualMoney->alias]));
        $this->assertEquals($sum, (double)$data[0][$this->VirtualMoney->alias]['price']);
	}

/**
 * testFindQueryForDatetime method
 *
 * @return void
 */
	public function testFindQueryForDatetime() {
        $query = $this->VirtualMoney->findQueryForDatetime('2014-01-01', '2014-01-31');
        $this->assertEquals($query, $this->VirtualMoney->findQueryForDatetime('2014-01-01 00:00:00', '2014-01-31 23:59:59'));
        $this->assertEquals($query, $this->VirtualMoney->findQueryForDatetime(array(
            'year' => '2014',
            'month' => '1',
            'day' => '1'
        ), array(
            'year' => '2014',
            'month' => '1',
            'day' => '31'
        )));
        $this->assertEquals($query, $this->VirtualMoney->findQueryForDatetime(array(
            'year' => '2014',
            'month' => '1',
            'day' => '1',
            'hour' => '0',
            'min' => '0',
            'second' => '0'
        ), array(
            'year' => '2014',
            'month' => '1',
            'day' => '31',
            'hour' => '23',
            'min' => '59',
            'second' => '59'
        )));
        
        $query = $this->VirtualMoney->findQueryForDatetime('2014-01-01');
        $this->assertEquals($this->VirtualMoney->find('count'), $this->VirtualMoney->find('count', $query));
        
        $query = $this->VirtualMoney->findQueryForDatetime(null, '2014-01-01');
        $this->assertEquals(0, $this->VirtualMoney->find('count', $query));
        
        $query = $this->VirtualMoney->findQueryForDatetime('2014-03-02', '2014-03-02');
        $this->assertEquals($this->VirtualMoney->find('count', $query), $this->VirtualMoney->find('count', $query));
	}

}
