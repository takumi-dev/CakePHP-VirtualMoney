<?php
/**
 *  VirtualMoney Model
 * 
 *  See common methods in VirtualMoneyAppModel
 * 
 */
App::uses('CakeEvent', 'Event');
App::uses('VirtualMoneyAppModel', 'VirtualMoney.Model');
class VirtualMoney extends VirtualMoneyAppModel {
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
    * _getCashbackModel - return CashbackRequestModel,
    * please overwrite this method if you want to use your model.
    *
    * @return CashbackRequestModel
    */
    protected function _getCashbackModel()
    {
        return ClassRegistry::init('VirtualMoney.CashbackRequest');
    }
    
    /**
	 * beforeDelete - set $this->data
	 *
	 * @param bool $cascade
	 * @return bool
	 */
    function beforeDelete($cascade = false)
    {
        if( !parent::beforeDelete($cascade) )
        {
            return false;
        }
        
        $this->data = $this->find('first', array(
            'conditions' => array($this->primaryKey => $this->id),
            'recursive' => -1
        ));
        return true;
    }
    
    /**
	 * afterDelete - dispath event "VirtualMoney.afterDelete" with data, id
	 *
	 * @return void
	 */
	function afterDelete()
	{
        parent::afterDelete();
        
        $event = new CakeEvent('VirtualMoney.afterDelete', $this, array(
            'data' => $this->data,
            'id' => $this->id,
        ));
        $this->getEventManager()->dispatch($event);
	}
    
    /**
	 * beforeValidate - serialize extra field if it is necessary.
	 *
	 * @param array $options
	 * @return bool
	 */
    public function beforeValidate($options = array())
    {
        if( !parent::beforeValidate($options) )
        {
            return false;
        }
        
        if( isset($data[$this->alias]['extra']) && !is_scalar($data[$this->alias]['extra']) )
        {
            $data[$this->alias]['extra'] = serialize($data[$this->alias]['extra']);
        }
        
        return true;
    }
    
    /**
	 * requestCashback - append cashback request and decrease money.
     * 
     * you can overwrite saveOptions, and data with in event "VirtualMoney.beforeCashback"
     * and you can add own validation on this event.
     * 
     * CakeEvent Object Properties
     * $event->data['money'] - a save data of VirtualMoney model 
     * $event->data['cashback'] - a save data of CashbackRequest model
     * $event->options - this property is not set in default. you need set followings manually.
     * $event->options['money'] - a saveOptions of VirtualMoney model
     * $event->options['cashback'] - a saveOptions of CashbackRequest model
	 *
	 * @param string $model
     * @param string $foreign_key
     * @param float  $price
	 * @return mixed null with invalid argument, array if success, bool false if failed
	 */
    public function requestCashback($model, $foreign_key, $price)
    {
        //invalid argument
        if( !is_numeric($price) || (double)$price === 0.0 || (double)$price < 0 ){
			return null;
		}
        
        //no money in account
        $currentSum = $this->sum($model, $foreign_key);
        if( $currentSum < (double)$price )
        {
            return false;
        }
        
        $forThis = array($this->alias => array('model' => $model, 'foreign_key' => $foreign_key, 'price' => -1.0 * (double)$price));
        $forCashback = array($this->alias => compact('model', 'foreign_key', 'price'));
        
        //dispath event. you can overwrite saveOptions, and data.
        $event = new CakeEvent('VirtualMoney.beforeCashback', $this, array(
            'money' => $forThis,
            'cashback' => $forCashback
        ));
        $this->getEventManager()->dispatch($event);
        if( $event->isStopped() ) {
            return false;
        }
        
        $forThis = $event->data['money'];
        $forCashback = $event->data['cashback'];
        
        $optionsForThis = array();
        if( !empty($event->options['money']) ){
            $optionsForThis = $event->options['money'];
        }
        $optionsForCashback = array();
        if( !empty($event->options['cashback']) ){
            $optionsForCashback = $event->options['cashback'];
        }
        
        //database call
        $db = $this->getDatasource();
        $db->beginTransaction();
        $requestModel = $this->_getCashbackModel();
        
        if( $this->appendMany($forThis, Hash::merge($optionsForThis, array('atomic' => false))) )
        {
            if( $requestModel->appendMany($forCashback, Hash::merge($optionsForCashback, array('atomic' => false))) )
            {
                $db->commit();
                return true;
            }
        }
        
        $db->rollback();
        return false;
    }
}