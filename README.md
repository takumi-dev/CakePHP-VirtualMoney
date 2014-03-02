CakePHP-VirtualMoney
====================

CakePHP-VirtualMoney is a simple plugin of CakePHP >= 2.0,
which provide features of management of money to your website.

## Features ##
- a VirtualMoney model handles logs when user add money to your site, or use money on your site.
- a CashbackRequest model handles requests for drawing out money from your website. ( just request logs, without real payment )

## TODO ##
- add default admin panel.
- create Test case.

## Requirements ##
CakePHP 2.x
PHP >= 5.2.6

## LICENCE ##
The MIT License (MIT)

See details
http://opensource.org/licenses/MIT

## Installation ##
Download this: https://github.com/takumi-dev/CakePHP-VirtualMoney/tree/master
Unzip that download.
Copy the resulting folder to app/Plugin
Rename the folder you just copied to VirtualMoney

1. run

    Console/cake schema create --plugin VirtualMoney

or

    run SQL in Config/Schema/schema.sql

(Remember to add your table prefix)


2. just add following code on your bootstrap.php

```php
CakePlugin::load('VirtualMoney');
```
or
```php
CakePlugin::loadAll();
```

## Usage ##
Extend model of this plugin, or directly use it.

To extend model like this:

```php
App::uses('VirtualMoney', 'VirtualMoney.Model');
class AppVirtualMoney extends VirtualMoney {

}
```

or directly use it like this:
```php
App::uses('VirtualMoney', 'VirtualMoney.Model');
class YourModel extends AppModel {
    ...
    function yourMethod(){
        $moneyModel = ClassRegistry::init('VirtualMoney.VirtualMoney');
    }
}
```

### To increase/decrease money
```php
    ...
    function yourMethod(){
        $moneyModel = ClassRegistry::init('VirtualMoney.VirtualMoney');

        //append money to $foreign_key of $model
        $moneyModel->append($model, $foreign_key, $price);

        //append money to $foreign_key of $model with description
        $moneyModel->append($model, $foreign_key, $price, 'For payment');

        //use money for $foreign_key of $model
        $moneyModel->append($model, $foreign_key, -1000.0);

        //append/use money to $foreign_key of $model with extra data ( something like payment api results )
        $moneyModel->append($model, $foreign_key, -1000.0, 'For payment', array('payment_id' => 3, 'item_id' => 4)); //array will automatically serialize

        //append money to $foreign_key of $model by data array
        $moneyModel->appendMany($data, array('saveMethod' => 'saveAll', 'fieldList' => array(), 'validate' => true));
        //$data should be like this:
        //array('VirtualMoney' => array(
        //    'model' => 'User', 'foreign_key' => 1, 'price' => -1500.0, 'description' => 'For payment'
        //    'extra' => array('payment_id' => 3, 'item_id' => 4)
        //));
    }
    ...
}
```

### To create cashback request
```php
    ...
    function yourMethod(){
        $moneyModel = ClassRegistry::init('VirtualMoney.VirtualMoney');

        //custom cashback procedure using events
        //see http://book.cakephp.org/2.0/ja/core-libraries/events.html
        $moneyModel->getEventManager()->attach(function(CakeEvent $event){
            $dataForVirtualMoney = $event->data['money'];
            //like: array('VirtualMoney' => array('model' => 'User', 'foreign_key' => 1, 'price' => -1000.0))
            $dataForCashbackRequest = $event->data['cashback'];
            //like: array('VirtualMoney' => array('model' => 'User', 'foreign_key' => 1, 'price' => 1000.0))

            //something overwrite....

            $event->data['money'] = $dataForVirtualMoney;
            $event->data['cashback'] = $dataForCashbackRequest;

            //you can overwrite saveOptions to appendMany
            $event->options = array('money' => array('saveMethod' => 'yourCustomSaveMethod'), 'cashback' => array());
        }, 'VirtualMoney.beforeCashback');

        //request cashback of $foreign_key of $model
        $moneyModel->requestCashback($model, $foreign_key, $price);
    }
    ...
}
```

### Find / Pagination
```php
class YourController extends AppController {
    ...
    function index(){
        $this->loadModel('VirtualMoney.VirtualMoney');
        
        $query = $this->VirtualMoney->findQueryForDatetime('2014-01-01', '2014-01-31');
        //= array('conditions' => array('VirtualMoney.created >=' => '2014-01-01 00:00:00', 'VirtualMoney.created <=' => '2014-01-31 23:59:59'));

        $query = $this->VirtualMoney->findQueryForBelongsTo('User', 1);
        //= array('conditions' => array('VirtualMoney.model' => 'User', 'VirtualMoney.foreign_key' => 1));

        //find('all') by belongsTo
        $data = $this->VirtualMoney->findAllByBelongsTo('User', 1);

        //find('all') by belongsTo with extra query.
        $data = $this->VirtualMoney->findAllByBelongsTo('User', 1, $query);
        $data = $this->VirtualMoney->findAllByBelongsTo('User', 1, $this->VirtualMoney->findQueryForDatetime('2014-01-01', '2014-01-31'));

        //get sum of price for User: 1 with/without extra query.
        $sum = $this->VirtualMoney->sum('User', 1);
        $sum = $this->VirtualMoney->sum('User', 1, $query);
        $sum = $this->VirtualMoney->sum('User', 1, $this->VirtualMoney->findQueryForDatetime('2014-01-01', '2014-01-31'));

        //find('first') for last log. for calculation of expired account.
        $last = $this->VirtualMoney->findLastByBelongsTo($model, $foreign_key);
        $last = $this->VirtualMoney->findLastByBelongsTo($model, $foreign_key, $query);

        //CashbackRequest has same api with VirtualMoney
    }
    ...
}
```

### Delete
```php
class User extends AppModel{
    function afterDelete()
    {
        $moneyModel = ClassRegistry::init('VirtualMoney.VirtualMoney');
        
        //delete All related money log.
        $moneyModel->deleteAllByBelongsTo($this->alias, $this->id);
        //call deleteAll($conditions, true, true);
    }

    function yourMethod()
    {
        $moneyModel = ClassRegistry::init('VirtualMoney.VirtualMoney');

        //just delete expired logs.
        $moneyModel->deleteAllByBelongsTo($this->alias, $this->id, array('created <=' => '2000-01-01'));
    }
}
```

### Event example
```php
class YourController extends AppController {
    ...
    function _sendNotification(CakeEvent $event){
        //some email method
    }
    function index(){
        $this->loadModel('VirtualMoney.VirtualMoney');
        
        //send email after add CashbackRequest data
        $query = $this->VirtualMoney->getEventManager()->attach(array($this, '_sendNotification'), 'CashbackRequest.afterSave');
        
        $this->VirtualMoney->requestCashback($model, $foreign_key, $price);
        //will send email if success request.
    }
    ...
}
```


## Event APIs ##
### VirtualModel
* VirtualModel.afterSave   - on afterSave:   array('id' => $id, 'data' => $data, 'created' => $created)
* VirtualModel.afterDelete - on afterDelete: array('id' => $id, 'data' => $data)
* VirtualModel.beforeCashback - before append cashback request: array('money' => $dataForVirtualModel, 'cashback' => $dataForCashbackRequest)

====================
### CashbackRequest
* CashbackRequest.afterSave   - on afterSave:   array('id' => $id, 'data' => $data, 'created' => $created)
* CashbackRequest.afterDelete - on afterDelete: array('id' => $id, 'data' => $data)

