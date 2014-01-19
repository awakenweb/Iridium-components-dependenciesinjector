Iridium-components-dependenciesinjector
=======================================

Simple Dependendies Injector for Iridium Framework. Works as a standalone library but best used with the full stack framework.

This component allows you to declare objetcs, closures, arrays, or any variables as services, that you can access in your application without having to instanciate them directly.

You can also declare objects as singletons.
You don't have to make your classes implement the singleton design pattern to make this work, just declare the service as singleton and the dependencies injector will take care of returning always the same instance of the service for you.
If you instanciate the class by yourself, you will however obtain a new instance, as your class is not a real singleton.


The class is unit tested using [Atoum](https://github.com/atoum/atoum).

Installation
------------
### Prerequisites

***Iridium requires at least PHP 5.4+ to work.***

Some of Iridium components may work on PHP5.3 but no support will be provided for this version.

### Using Composer
First, install [Composer](http://getcomposer.org/ "Composer").
Create a composer.json file at the root of your project. This file must at least contain :
```json
{
    "require": {
        "awakenweb/iridium-components-dependenciesinjector": "dev-master"
        }
}
```
and then run
```bash
~$ composer install
```
---
Usage
-----

### Classic services and closures
The classic services will return __different instances__ of the same class for each call to the DI::get() method.

First thing you have to do is to declare a service. In this example, our service is a simple `stdClass`, but it can be whatever you want. You can use the Closure to initialize you objects.

```php
<?php

include(path/to/vendor/autoload.php);
use Iridium\Components\DependenciesInjector\DependenciesInjector\DI;

DI::declareService('serviceName', function(){
    $obj = new \stdClass();
    $obj->hello = 'hello world';
    return $obj;
    });

DI::declareClosure('myClosure', function() {return 'hello universe';});
```

To use your declared service, you can either use the `DI::get()` method or some syntaxic sugar by calling the service name as a static method.

```php
$service = DI::get('serviceName');
echo $service->hello; // echoes hello world

$closure = DI::get('myClosure');
echo $closure(); // echoes hello universe

// this is strictly equivalent
$service = DI::serviceName();
echo $service->hello;
```

### Singleton services

To declare singletons, you simply have to use the `declareSingleton` method as follows:
```php
<?php

include(path/to/vendor/autoload.php);
use Iridium\Components\DependenciesInjector\DependenciesInjector\DI;

DI::declareSingleton('mySingleton', function(){
    return new arrayObject(array();
    });
```

You can now

```php
$service = DI::get('mySingleton');
$service['foo'] = 'bar';

$singletonService = DI::get('mySingleton');
echo $singletonService['foo']; // echoes bar
```