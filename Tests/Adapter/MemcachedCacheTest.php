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

use Sonatra\Component\Cache\Adapter\MemcachedCache;

/**
 * Memcached Cache Tests Suite.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MemcachedCacheTest extends AbstractCacheTest
{
    /**
     * {@inheritdoc}
     */
    public function getCache($prefix = null)
    {
        return new MemcachedCache($prefix, array(array(
            'host'   => '127.0.0.1',
            'port'   => 11211,
            'weight' => 0
        )));
    }

    /**
     * {@inheritdoc}
     */
    public function getMockCache($prefix = null)
    {
        return $this->getCache($prefix);
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        if (PHP_VERSION_ID <= 50303) {
            $this->testSkipped = true;
            $this->markTestSkipped('The PHP version must be greater than 5.3.3 (because DateInterval is buggy)');
        }

        if (!class_exists('Memcached', true)) {
            $this->testSkipped = true;
            $this->markTestSkipped('Memcached is not installed');
        }

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 0));
        $result = @socket_connect($socket, '127.0.0.1', 11211);
        socket_close($socket);

        if (!$result) {
            $this->testSkipped = true;
            $this->markTestSkipped('Memcached is not running');
        }

        $memcached = new \Memcached();
        $memcached->addServer('127.0.0.1', 11211);

        $memcached->flush();
    }

    /**
     * Clean up all.
     */
    public function tearDown()
    {
        if ($this->testSkipped) {
            return;
        }

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 0));
        $result = @socket_connect($socket, '127.0.0.1', 11211);
        socket_close($socket);

        if (!$result) {
            return;
        }

        $memcached = new \Memcached();
        $memcached->addServer('127.0.0.1', 11211);

        $memcached->flush();
    }
}
