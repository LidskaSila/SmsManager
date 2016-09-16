SmsManager PHP Library
======================

Library for PHP that can send SMS via SmsManager.cz gateway. _(not all API methods are implemented for now)_

Requirements
------------

LidskaSila/SmsManager requires PHP 7.0 or higher.

Installation
------------

```sh
$ composer require lidskasila/sms-manager
```

Usage
-----

Pass `Sms` object to `SmsManager` `sendSms()` method. `Sms` object should include all data like message, message type, receipients or sender.