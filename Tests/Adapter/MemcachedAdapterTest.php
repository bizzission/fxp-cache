<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Cache\Tests\Adapter;

use Sonatra\Component\Cache\Adapter\MemcachedAdapter;

/**
 * Memcached Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MemcachedAdapterTest extends AbstractAdapterTest
{
    public function setUp()
    {
        if (!MemcachedAdapter::isSupported()) {
            $this->markTestSkipped('Extension memcached >=2.2.0 required.');
        }

        $client = new \Memcached();
        $client->addServers(array(array(
            getenv('MEMCACHED_HOST') ?: '127.0.0.1',
            getenv('MEMCACHED_PORT') ?: 11211,
        )));

        $this->adapter = new MemcachedAdapter($client, str_replace('\\', '.', __CLASS__), 0);
        $this->adapter->clear();
    }
}
