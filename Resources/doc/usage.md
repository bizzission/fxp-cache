Sonatra Cache Usage
===================

## Prerequisites

[Installation and Configuration](index.md)

## Use

### Cache usage

```php
<?php

use Sonatra\Component\Cache\Adapter\PhpCache;
use Sonatra\Component\Cache\CacheElement;

$cache = PhpCache('var/cache', new Symfony\Component\Filesystem\Filesystem());

$cache->set('foo', 'bar', CacheElement::MONTH);

$cacheElement = $cache->get($keys);

$cacheElement->getData();
```

### Counter usage

```php
<?php

use Sonatra\Component\Cache\Adapter\PhpCache;
use Sonatra\Component\Cache\Counter;

$cache = PhpCache('var/cache', new Symfony\Component\Filesystem\Filesystem());

/* @var Counter */
$counter = $cache->increment('foobar');

$counter->getValue(); // will return 1 if the counter is new

$counter = $cache->increment($counter, 4);

$counter->getValue(); // will return 5
```

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
