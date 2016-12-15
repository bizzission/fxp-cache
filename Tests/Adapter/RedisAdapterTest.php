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

use Predis\Client;
use Sonatra\Component\Cache\Adapter\RedisAdapter;

/**
 * Redis Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class RedisAdapterTest extends AbstractAdapterTest
{
    protected function setUp()
    {
        if (!class_exists('\Predis\Client', true)) {
            $this->markTestSkipped('Predis is not installed');
        }

        $redisHost = 'localhost';
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 1, 'usec' => 0));
        $result = @socket_connect($socket, $redisHost, 6379);
        socket_close($socket);

        if (!$result) {
            $this->markTestSkipped('Redis is not running');
        }

        $redis = RedisAdapter::createConnection('redis://' . $redisHost . '/1', array(
            'class' => Client::class,
            'timeout' => 3,
        ));

        $this->assertInstanceOf(Client::class, $redis);

        $this->adapter = new RedisAdapter($redis, static::PREFIX_GLOBAL);
        $this->adapter->clear();
    }
}
