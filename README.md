CakePHP-VirtualMoney
====================

CakePHP-VirtualMoney is a simple plugin of CakePHP >= 2.0,
which provide features of management of money to your website.

## Features ## 
====================
- a VirtualMoney model handles logs when user add money to your site, or use money on your site.
- a CashbackRequest model handles requests for drawing out money from your website. ( just request logs, without real payment )

## TODO ## 
====================
- add default admin panel

## Requirements ##
====================
CakePHP 2.x
PHP >= 5.2.6

## LICENCE ##
====================
The MIT License (MIT)

See details
http://opensource.org/licenses/MIT

## Usage ##
====================
Download this: https://github.com/takumi-dev/CakePHP-VirtualMoney/tree/master
Unzip that download.
Copy the resulting folder to app/Plugin
Rename the folder you just copied to VirtualMoney

1. run

    Console/cake schema create --plugin VirtualMoney virtual_money

or

    run SQL in Config/Schema/virtual_money.sql

(Remember to add your table prefix)


2. just add following code on your bootstrap.php

```php
CakePlugin::load('VirtualMoney');
```
or
```php
CakePlugin::loadAll();
```

3. Extend model of this plugin, or directly use it.

To extend model like this:

```php
App::uses('VirtualMoney', 'VirtualMoney.Model');
class AppVirtualMoney extends VirtualMoney {

}
```

## API ##
====================
VirtualModel
* protected _setupValidation
* protected _getCashbackModel()

* public sum($model, $foreign_key, $extraQuery = array())
* public append($model, $foreign_key, $price, $description = null, $extra = null)
* public appendMany($data, $options = array())
* public findQueryForDatetime($start = null, $end = null)
* public requestCashback($model, $foreign_key, $price)

* public findLastByBelongsTo($model, $foreign_key, $extraQuery = array())
* public findAllByBelongsTo($model, $foreign_key, $extraQuery = array())
* public deleteAllByBelongsTo($model, $foreign_key, $extraConditions = array())

Events
* VirtualModel.afterSave   - on afterSave:   array('id' => $id, 'data' => $data, 'created' => $created)
* VirtualModel.afterDelete - on afterDelete: array('id' => $id, 'data' => $data)
* VirtualModel.beforeCashback - before append cashback request: array('money' => $dataForVirtualModel, 'cashback' => $dataForCashbackRequest)

====================
CashbackRequest
* protected _setupValidation

* public sum($model, $foreign_key, $extraQuery = array())
* public append($model, $foreign_key, $price, $description = null, $extra = null)
* public appendMany($data, $options = array())
* public findQueryForDatetime($start = null, $end = null)
* public requestCashback($model, $foreign_key, $price)

* public findLastByBelongsTo($model, $foreign_key, $extraQuery = array())
* public findAllByBelongsTo($model, $foreign_key, $extraQuery = array())
* public deleteAllByBelongsTo($model, $foreign_key, $extraConditions = array())

Events
* CashbackRequest.afterSave   - on afterSave:   array('id' => $id, 'data' => $data, 'created' => $created)
* CashbackRequest.afterDelete - on afterDelete: array('id' => $id, 'data' => $data)

