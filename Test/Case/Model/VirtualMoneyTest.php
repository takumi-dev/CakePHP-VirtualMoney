<?php
App::uses('VirtualMoney', 'VirtualMoney.Model');

/**
 * VirtualMoney Test Case
 *
 */
class VirtualMoneyTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.virtual_money.virtual_money',
        'plugin.virtual_money.cashback_request'
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
 * testRequestCashback method
 *
 * @return void
 */
    public function _beforeCashback(CakeEvent $event)
    {
        $this->assertEquals($event->data['money']['VirtualMoney'], array('model' => 'User', 'foreign_key' => 2, 'price' => -100));
        $this->assertEquals($event->data['cashback']['CashbackRequest'], array('model' => 'User', 'foreign_key' => 2, 'price' => 100));
        
        $event->data['money']['VirtualMoney']['price'] = -1000;
        $event->data['cashback']['CashbackRequest']['price'] = 1000;
    }
    
    public function _beforeCashbackStop(CakeEvent $event)
    {
        $event->stopPropagation();
        return false;
    }
    
	public function testRequestCashback() {
        $this->assertFalse($this->VirtualMoney->requestCashback('User', 1, 100 * 10000));
        $this->assertNull($this->VirtualMoney->requestCashback('User', 1, -100));
        $this->assertNull($this->VirtualMoney->requestCashback('User', 1, new stdClass()));
        $this->assertNull($this->VirtualMoney->requestCashback('User', 1, array(100)));
        $this->assertNull($this->VirtualMoney->requestCashback('User', 1, 'foo'));
        
        //request
        $sum = $this->VirtualMoney->sum('User', 1);
        $this->assertTrue($this->VirtualMoney->requestCashback('User', 1, 100));
        
        $sum_after = $this->VirtualMoney->sum('User', 1);
        $this->assertEquals($sum_after, ($sum - 100));
        
        //request
        $sum = $this->VirtualMoney->sum('User', 1);
        $this->assertTrue($this->VirtualMoney->requestCashback('User', 1, 93.5025));
        
        $sum_after = $this->VirtualMoney->sum('User', 1);
        $this->assertEquals($sum_after, ($sum - 93.5025));
	}
    
    public function testRequestCashbackEvent() {
        //callback test
        $this->VirtualMoney->getEventManager()->attach(array($this, '_beforeCashbackStop'), 'VirtualMoney.beforeCashback');
        $this->assertFalse($this->VirtualMoney->requestCashback('User', 2, 100));
        $this->VirtualMoney->getEventManager()->detach(array($this, '_beforeCashbackStop'), 'VirtualMoney.beforeCashback');
        
        //request
        $sum = $this->VirtualMoney->sum('User', 2);
        $this->VirtualMoney->getEventManager()->attach(array($this, '_beforeCashback'), 'VirtualMoney.beforeCashback');
        $this->assertTrue($this->VirtualMoney->requestCashback('User', 2, 100));
        $sum_after = $this->VirtualMoney->sum('User', 2);
        $this->assertEquals($sum_after, ($sum - 1000));
    }

}
