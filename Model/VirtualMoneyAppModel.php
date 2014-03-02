<?php
App::uses('AppModel', 'Model');
class VirtualMoneyAppModel extends AppModel {
    /**
    * Constructor
    *
    * @param bool|string $id ID
    * @param string $table Table
    * @param string $ds Datasource
    */
	public function __construct($id = false, $table = null, $ds = null) {
		$this->_setupValidation();
		parent::__construct($id, $table, $ds);
	}
    
    /**
    * Setup validation rules - for localization.
    *
    * @return void
    */
	protected function _setupValidation() {
		
	}
    
    /**
	 * afterSave - dispath event "VirtualMoney.afterSave" with data, id, created
	 *
	 * @param bool $created
	 * @return void
	 */
	function afterSave($created)
	{
        parent::afterSave($created);
        
        $event = new CakeEvent($this->alias.'.afterSave', $this, array(
            'data' => $this->data,
            'id' => $this->id,
            'created' => $created
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
        
        if( $this->hasField('extra') )
        {
            if( isset($this->data[$this->alias]['extra']) && !is_scalar($this->data[$this->alias]['extra']) )
            {
                $this->data[$this->alias]['extra'] = serialize($this->data[$this->alias]['extra']);
            }
        }
        
        return true;
    }
    
    /**
	 * findAllByBelongsTo - find all logs with belongsTo $model, $foreign_key
	 *
	 * @param string $model
     * @param string $foreign_key
     * @param array  $extraQuery
	 * @return array $data or bool false
	 */
    public function findAllByBelongsTo($model, $foreign_key, $extraQuery = array())
    {
        $conditions = array(
            'model' => $model,
            'foreign_key' => $foreign_key,
        );
        $query = Hash::merge(compact('conditions'), $extraQuery);
        return $this->find('all', $query);
    }
    
    /**
	 * deleteAllByBelongsTo - delete all logs with belongsTo $model, $foreign_key
	 *
	 * @param string $model
     * @param string $foreign_key
     * @param array  $extraConditions
	 * @return int count of deleted or bool false
	 */
    public function deleteAllByBelongsTo($model, $foreign_key, $extraConditions = array())
    {
        $conditions = array(
            'model' => $model,
            'foreign_key' => $foreign_key,
        );
        if( isset($extraConditions['conditions']) )
        {
            $extraConditions = $extraConditions['conditions'];
        }
        
        return $this->deleteAll($conditions, true, true);
    }
    
    /**
	 * findLastByBelongsTo - find last log with belongsTo $model, $foreign_key
	 *
	 * @param string $model
     * @param string $foreign_key
     * @param array  $extraQuery
	 * @return array $data or bool false
	 */
    public function findLastByBelongsTo($model, $foreign_key, $extraQuery = array())
    {
        $conditions = array(
            'model' => $model,
            'foreign_key' => $foreign_key,
        );
        $order = array($this->alias.'.'.$this->primaryKey => 'DESC');
        $query = Hash::merge(compact('order', 'conditions'), $extraQuery);
        
        return $this->find('first', $query);
    }
    
    /**
	 * sum - get sum of price with belongsTo $model, $foreign_key
	 *
	 * @param string $model
     * @param string $foreign_key
     * @param array  $extraQuery
	 * @return double sum of price with belongsTo $model, $foreign_key
	 */
    public function sum($model, $foreign_key, $extraQuery = array())
    {
        $conditions = array(
            'model' => $model,
            'foreign_key' => $foreign_key,
        );
        $contain = false;
        $fields  = array('id', 'price');
        $query = Hash::merge(compact('conditions', 'contain', 'fields'), $extraQuery);
        
        $data = $this->find('all', $query);
        if( !empty($data) )
        {
            $data = Hash::extract($data, '{n}.'.$this->alias.'.price');
            return (double)array_sum($data);
        }
        
        return 0.0;
    }
    
    /**
	 * append - append money increase or decrease by each value
	 *
	 * @param string $model
     * @param string $foreign_key
     * @param float  $price
     * @param string $description = null
     * @param mixed  $extra = null
	 * @return null with invalid argument, mixed array if success, bool false if failed
	 */
    public function append($model, $foreign_key, $price, $description = null, $extra = null){
		if( !is_numeric($price) ){
			return null;
		}
        
		$data = array($this->alias => compact('model', 'foreign_key', 'price', 'description', 'extra'));
		return $this->save($data);
	}
    
    /**
	 * appendMany - append money by general cakephp data
	 *
     * @param mixed $data
     * @param array $options = array()
     *                  - saveMethod
     *                  - fieldList
     *                  - validate
	 * @return mixed array if success, bool false if failed
	 */
    public function appendMany($data, $options = array())
    {
        $fieldList = array(
            $this->alias => array('model', 'foreign_key', 'price', 'description', 'extra')
        );
        
        if( isset($options['saveMethod']) && $options['saveMethod'] !== 'saveAll' ){
            return call_user_func_array(array($this, $options['saveMethod']), array($data, Hash::merge(compact('fieldList'), $options)));
        }else{
            return $this->saveAll($data, Hash::merge(compact('fieldList'), $options));
        }
    }
    
    /**
	 * findQueryForBelongsTo - find query belongsTo $model, $foreign_key
	 *
	 * @param string $model
     * @param string $foreign_key
	 * @return array $query
	 */
    public function findQueryForBelongsTo($model, $foreign_key)
    {
        $conditions = array(
            'model' => $model,
            'foreign_key' => $foreign_key,
        );
        return compact('conditions');
    }
    
    /**
	 * findQueryForDatetime - find query for created
	 *
	 * @param string|array $start = null
     * @param string|array $end = null
	 * @return array $query
	 */
    public function findQueryForDatetime($start = null, $end = null)
	{
        if( !isset($start) && !isset($end) )
        {
            return array();
        }
        
        foreach(array('start', 'end') as $var)
        {
            if( isset(${$var}) )
            {
                //for request array
                if( is_array(${$var}) )
                {
                    if( isset(${$var}['hour']) )
                    {
                        ${$var} = String::insert(':year-:month-:day :hour\::min\:second', ${$var});
                    }else{
                        ${$var} = implode('-', ${$var});
                    }
                }
                if( is_numeric(${$var}) ){
                    ${$var} = date('Y-m-d H:i:s', ${$var});
                }elseif( strpos(${$var}, ':') === false ){
                    if( $var === 'start' )
                    {
                        ${$var} = date('Y-m-d 00:00:00', strtotime(${$var}));
                    }
                    else
                    {
                        ${$var} = date('Y-m-d 23:59:59', strtotime(${$var}));
                    }
                }else{
                    ${$var} = date('Y-m-d H:i:s', strtotime(${$var}));
                }
            }
        }
        
		$conditions = array(
			$this->alias.'.created >=' => $start,
			$this->alias.'.created <=' => $end,
		);
        
		return compact('conditions');
	}
}

