Usage
=====

## Initialisations

### Filesystem Cache initialisation

```php
use Fxp\Component\Cache\Adapter\FilesystemCache;

$cache = FilesystemCache();
//...
```

## Examples of Use

### Cache usage

##### Cache: clear by prefix

```php
use Fxp\Component\Cache\Adapter\FilesystemCache;

$cache = FilesystemCache('my_custom_prefix');

$cache->clearByPrefix('my-prefix-key');
```
