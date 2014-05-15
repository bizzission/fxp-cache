Sonatra Cache Usage
===================

## Prerequisites

[Installation and Configuration](index.md)

## Initialisations

### PHP Cache initialisation

```php
<?php

use Sonatra\Component\Cache\Adapter\PhpCache;

$cache = PhpCache('var/cache');
//...
```

### APC Cache initialisation

```php
<?php

use Sonatra\Component\Cache\Adapter\ApcCache;

$cache = ApcCache('my_custom_prefix');
//...
```

### Redis Cache initialisation

```php
<?php

use Sonatra\Component\Cache\Adapter\RedisCache;

$cache = RedisCache('my_custom_prefix');
//...
```

## Examples of Use

### Cache usage

```php
<?php

use Sonatra\Component\Cache\Adapter\PhpCache;
use Sonatra\Component\Cache\CacheElement;

$cache = PhpCache('var/cache', new Symfony\Component\Filesystem\Filesystem());

$cache->set('foo', 'bar', CacheElement::MONTH);

// set
$cacheElement = $cache->set('foo', 'bar', CacheElement::DAY);

// has
$cache->has('foo');// will return true

// get
$cacheElement = $cache->get('foo');// will return CacheElement instance

$cacheElement->getData();// will return 'bar'
```

### Counter usage

```php
<?php

use Sonatra\Component\Cache\Adapter\PhpCache;
use Sonatra\Component\Cache\Counter;

$cache = PhpCache('var/cache', new Symfony\Component\Filesystem\Filesystem());

/* @var Counter $counter */
// increment
$counter = $cache->increment('foobar');

$counter->getValue(); // will return 1 if the counter is new

$counter = $cache->increment($counter, 4);

$counter->getValue(); // will return 5

// decrement
$counter = $cache->decrement('foobar');

$counter->getValue(); // will return 4

$counter = $cache->decrement($counter, 4);

$counter->getValue(); // will return 0
```
