<?php
/**
 *  VirtualMoney Model
 * 
 *  See common methods in VirtualMoneyAppModel
 * 
 */
App::uses('CakeEvent', 'Event');
App::uses('VirtualMoneyAppModel', 'VirtualMoney.Model');
class CashbackRequest extends VirtualMoneyAppModel {
	var $displayField = 'price';
	var $order = array('id' => 'DESC');
    
	var $actsAs = array(
		'Containable',
	);
    var $validate = array();
    
    /**
    * Setup validation rules
    *
    * @return void
    */
	protected function _setupValidation() {
		$this->validate = array(
            'model' => array(
                'required' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'allowEmpty' => false,
                    'on' => 'create',
                    'message' => __d('virtual_money', 'This field is required.')
                ),
            ),
            'foreign_key' => array(
                'required' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'allowEmpty' => false,
                    'on' => 'create',
                    'message' => __d('virtual_money', 'This field is required.')
                ),
            ),
            'price' => array(
                'required' => array(
                    'rule' => 'notEmpty',
                    'required' => true,
                    'allowEmpty' => false,
                    'on' => 'create',
                    'message' => __d('virtual_money', 'This field is required.')
                ),
                'numeric' => array(
                    'rule' => 'numeric',
                    'message' => __d('virtual_money', 'This field must be numeric.')
                ),
            )
       );
	}
	
    /**
	 * is_processed - return whether cashback is done, or not
	 *
	 * @param string $id
	 * @return bool
	 */
	public function is_processed($id)
	{
		$conditions = array(
			$this->alias.'.id' => $id,
		);
		
		return (bool)$this->field('processed', $conditions);
	}
    
    /**
	 * mark_as_processed - mark as processed and return result.
	 *
	 * @param string $id
	 * @return bool
	 */
	public function mark_as_processed($id, $datetime = null)
	{
        if( !$this->exists($id) )
        {
            return false;
        }
        
        if( !isset($datetime) )
        {
            $datetime = date('Y-m-d H:i:s');
        }
		
        $this->id = $id;
		return (bool)$this->saveField('processed', $datetime);
	}
	
	/**
	 * mark_as_processed_with_data - mark as processed with data and return result.
	 *
	 * @param string $id
	 * @return bool
	 */
	public function mark_as_processed_with_data($id, $data)
	{
        if( !$this->exists($id) )
        {
            return false;
        }
        
		$data = Hash::merge(array(
            $this->alias => array('processed' => date('Y-m-d H:i:s'))
        ), $data);
		
        $this->id = $id;
		return (bool)$this->save($data, array('fieldList' => array($this->alias => array('processed', 'description', 'extra'))));
	}
}