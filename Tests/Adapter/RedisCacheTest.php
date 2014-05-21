<?php

/**
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Cache\Tests\Adapter;

use Sonatra\Component\Cache\Adapter\RedisCache;

/**
 * Redis Cache Tests Suite.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class RedisCacheTest extends AbstractCacheTest
{
    /**
     * {@inheritdoc}
     */
    public function getCache($prefix = null)
    {
        return new RedisCache($prefix, array(
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 42
        ));
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
        if (!class_exists('\Predis\Client', true)) {
            $this->markTestSkipped('Predis is not installed');
        }

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 0));

        $result = @socket_connect($socket, '127.0.0.1', 6379);

        if (!$result) {
            $this->markTestSkipped('Redis is not running');
        }

        socket_close($socket);

        $client = $this->getCache()->getClient();
        $client->executeCommand($client->createCommand('flushdb'));
    }

    /**
     * Clean up all.
     */
    public function tearDown()
    {
        $client = $this->getCache()->getClient();
        $client->executeCommand($client->createCommand('flushdb'));
    }
}
