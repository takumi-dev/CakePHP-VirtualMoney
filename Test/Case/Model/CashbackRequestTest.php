<?php
App::uses('CashbackRequest', 'VirtualMoney.Model');

/**
 * CashbackRequest Test Case
 *
 */
class CashbackRequestTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.virtual_money.cashback_request'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CashbackRequest = ClassRegistry::init('VirtualMoney.CashbackRequest');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CashbackRequest);

		parent::tearDown();
	}

/**
 * testIsProcessed method
 *
 * @return void
 */
	public function testIsProcessed() {
        $this->assertFalse($this->CashbackRequest->is_processed('null'));
        $this->assertFalse($this->CashbackRequest->is_processed('5312fecf-5df8-47af-bd52-3e8831d4c5d'));
        $this->assertTrue($this->CashbackRequest->is_processed('5312fecf-2ffc-4b9c-9bea-3e8831d4c5d0'));
	}

/**
 * testMarkAsProcessed method
 *
 * @return void
 */
	public function testMarkAsProcessed() {
        $this->assertFalse($this->CashbackRequest->mark_as_processed('null'));
        $this->assertTrue($this->CashbackRequest->mark_as_processed('5312fecf-5df8-47af-bd52-3e8831d4c5d0', '2012-01-01 00:00:00'));
        $this->assertEquals($this->CashbackRequest->field('processed', array('id' => '5312fecf-5df8-47af-bd52-3e8831d4c5d0')), '2012-01-01 00:00:00');
        
        $this->assertTrue($this->CashbackRequest->mark_as_processed('5312fecf-2ffc-4b9c-9bea-3e8831d4c5d0'));
	}

/**
 * testMarkAsProcessedWithData method
 *
 * @return void
 */
	public function testMarkAsProcessedWithData() {
        $this->assertFalse($this->CashbackRequest->mark_as_processed_with_data('null', array()));
        $this->assertTrue($this->CashbackRequest->mark_as_processed_with_data('5312fecf-5df8-47af-bd52-3e8831d4c5d0', array(
            'CashbackRequest' => array('price' => 50, 'description' => 'For payment', 'extra' => array('payment_id' => 1))
        )));
        
        $this->assertEquals($this->CashbackRequest->field('price', array('id' => '5312fecf-5df8-47af-bd52-3e8831d4c5d0')), '1000');
        $this->assertEquals($this->CashbackRequest->field('description', array('id' => '5312fecf-5df8-47af-bd52-3e8831d4c5d0')), 'For payment');
        $this->assertEquals($this->CashbackRequest->field('extra', array('id' => '5312fecf-5df8-47af-bd52-3e8831d4c5d0')), serialize(array('payment_id' => 1)));
	}

}
