CakePHP-VirtualMoney
====================

CakePHP-VirtualMoney is a simple plugin of CakePHP >= 2.0,
which provide features of management of money to your website.

Features
====================
- a MoneyLog model handles logs when user add money to your site, or use money on your site.
- a DrawMoneyRequest model handles requests for drawing out money from your website. ( just request logs, without real payment )
- (TODO) Admin Panels for these features.


Requires
====================
CakePHP >= 2.0
PHP >= 5.3


LICENCE
====================
MIT Licence

See details
http://opensource.org/licenses/MIT


Usage
====================
1. run
Console/cake schema create --plugin VirtualMoney virtual_money
or
run SQL in Config/Schema/virtual_money.sql
(Remember to add your table prefix)

2. just add following code on your bootstrap.php

CakePlugin::load('VirtualMoney');
or
CakePlugin::loadAll();

3. Extend model of this plugin, or directly use it.



