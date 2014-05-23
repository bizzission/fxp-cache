Usage
=====

## Initialisations

### PHP Cache initialisation

```php
use Sonatra\Component\Cache\Adapter\PhpCache;

$cache = PhpCache('var/cache', 'my_custom_prefix');
//...
```

### APC Cache initialisation

```php
use Sonatra\Component\Cache\Adapter\ApcCache;

$cache = ApcCache('my_custom_prefix');
//...
```

### Memcached Cache initialisation

```php
use Sonatra\Component\Cache\Adapter\MemcachedCache;

$server = array('host' => '127.0.0.1', 'port' => 11211, 'weight' => 0);//host required
$cache = MemcachedCache('my_custom_prefix', $server);

// or

$servers = array(
    array('host' => '127.0.0.1', 'port' => 11211, 'weight' => 2),
    array('host' => '127.0.0.2', 'port' => 11211, 'weight' => 1),
    array('host' => '127.0.0.3', 'port' => 11211, 'weight' => 0),
);
$cache = MemcachedCache('my_custom_prefix', $servers);
//...
```

### Redis Cache initialisation

```php
use Sonatra\Component\Cache\Adapter\RedisCache;

$server = array('host' => '127.0.0.1', 'port' => 6379, 'database' => 42);
$cache = RedisCache('my_custom_prefix', $server);
//...
```

## Examples of Use

### Cache usage

##### Cache: Set

```php
use Sonatra\Component\Cache\Adapter\PhpCache;
use Sonatra\Component\Cache\CacheElement;

$cache = PhpCache(sys_get_temp_dir() . '/project_42', 'my_custom_prefix');

/* @var CacheElement $cacheElement */
$cacheElement = $cache->set('foo', 'bar', CacheElement::MONTH);
```

##### Cache: Has

```php
use Sonatra\Component\Cache\Adapter\PhpCache;
use Sonatra\Component\Cache\CacheElement;

$cache = PhpCache(sys_get_temp_dir() . '/project_42', 'my_custom_prefix');

$cache->has('foo');// will return false

$cache->set('foo', 'bar', CacheElement::MONTH);

$cache->has('foo');// will return true
```

##### Cache: Get

```php
use Sonatra\Component\Cache\Adapter\PhpCache;
use Sonatra\Component\Cache\CacheElement;

$cache = PhpCache(sys_get_temp_dir() . '/project_42', 'my_custom_prefix');

/* @var CacheElement $cacheElement */
$element = $cache->get('foo');

$element->isExpired(); // will return true
$element->getData(); // will return null

$cache->set('foo', 'bar', CacheElement::MONTH);

$element->isExpired(); // will return false
$element->getData(); // will return 'bar'
```

##### Cache: Flush

```php
use Sonatra\Component\Cache\Adapter\PhpCache;
use Sonatra\Component\Cache\CacheElement;

$cache = PhpCache(sys_get_temp_dir() . '/project_42', 'my_custom_prefix');

$cache->set('foo', 'bar', CacheElement::MONTH);
$cache->has('foo');// will return true

$cache->flush('foo'); // will return true
$cache->has('foo');// will return false
```

##### Cache: Flush All

```php
use Sonatra\Component\Cache\Adapter\PhpCache;
use Sonatra\Component\Cache\CacheElement;

$cache = PhpCache(sys_get_temp_dir() . '/project_42', 'my_custom_prefix');

$cache->set('foo', 'bar', CacheElement::MONTH);
$cache->set('bar', 'foo', CacheElement::MONTH);

$cache->has('foo');// will return true
$cache->has('bar');// will return true

$cache->flushAll(); // will return true
$cache->has('foo');// will return false
$cache->has('bar');// will return false
```

##### Cache: Flush All prefixed keys

```php
use Sonatra\Component\Cache\Adapter\PhpCache;
use Sonatra\Component\Cache\CacheElement;

$cache = PhpCache(sys_get_temp_dir() . '/project_42', 'my_custom_prefix');

$cache->set('foo', 'bar', CacheElement::MONTH);
$cache->set('bar', 'foo', CacheElement::MONTH);
$cache->set('prefix_foo', 'bar', CacheElement::MONTH);
$cache->set('prefix_bar', 'foo', CacheElement::MONTH);


$cache->has('foo');// will return true
$cache->has('bar');// will return true
$cache->has('prefix_foo');// will return true
$cache->has('prefix_bar');// will return true

$cache->flushAll('prefix_'); // will return true
$cache->has('foo');// will return true
$cache->has('bar');// will return true
$cache->has('prefix_foo');// will return false
$cache->has('prefix_bar');// will return false
```

### Counter usage

##### Counter: Set

```php
use Sonatra\Component\Cache\Adapter\PhpCache;
use Sonatra\Component\Cache\Counter;

$cache = PhpCache(sys_get_temp_dir() . '/project_42', 'my_custom_prefix');

/* @var Counter $counter */
$counter = new Counter('foo_counter', 42);
counter = $cache->setCounter($counter);// will return Counter saved in cache
```

##### Counter: Get

```php
use Sonatra\Component\Cache\Adapter\PhpCache;
use Sonatra\Component\Cache\Counter;

$cache = PhpCache(sys_get_temp_dir() . '/project_42', 'my_custom_prefix');

/* @var Counter $counter */
$counter = $cache->getCounter('foo_counter');
$counter->getValue(); // will return 0 because counter does not exist in cache

$counter = new Counter('foo_counter', 42);
$counter = $cache->setCounter($counter);

$counter = $cache->getCounter('foo_counter');
$counter->getValue(); // will return 42
```

##### Counter: Increment

```php
use Sonatra\Component\Cache\Adapter\PhpCache;
use Sonatra\Component\Cache\Counter;

$cache = PhpCache(sys_get_temp_dir() . '/project_42', 'my_custom_prefix');

/* @var Counter $counter */
$counter = $cache->increment('foo_counter');
$counter->getValue(); // will return 1 because counter des not exist and it's incremented by 1

$counter = $cache->increment($counter, 4);
$counter->getValue(); // will return 5

$counter = $cache->increment('foo_counter', 5);
$counter->getValue(); // will return 10
```

##### Counter: Decrement

```php
use Sonatra\Component\Cache\Adapter\PhpCache;
use Sonatra\Component\Cache\Counter;

$cache = PhpCache(sys_get_temp_dir() . '/project_42', 'my_custom_prefix');

/* @var Counter $counter */
$counter = new Counter('foo_counter', 42);

$counter = $cache->decrement($counter);
$counter->getValue(); // will return 41

$counter = $cache->decrement('foo_counter', 40);
$counter->getValue(); // will return 1
```
