Sonatra Cache
=============

## Prerequisites

This version of the library requires not dependence.

## Installation

``` bash
$ php composer.phar require sonatra/cache
```

##### APC

If you want to use APC cache, you must install the APC PHP Extension.

##### Memcached

If you want to use Memcached cache, you must install the Memcache PHP Extension.

##### Redis

If you want to use Redis cache, you must install this dependency:

``` bash
$ php composer.phar require 'predis/predis:0.8'
```

## Configuration

##### APC

The APC PHP Extension must be enabled in `php.ini`

##### Memcached

The Memcached PHP Extension must be enabled in `php.ini`

### Next Steps

Now that you have completed the basic installation of the Sonatra Cache,
you are ready to use this library.

[Enjoy!](usage.md)
