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

use Sonatra\Component\Cache\Adapter\CacheInterface;
use Sonatra\Component\Cache\CacheElement;
use Sonatra\Component\Cache\Counter;

/**
 * Cache Adapter Interface.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class AbstractCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Gets the cache.
     *
     * @return CacheInterface
     */
    abstract public function getCache();

    /**
     * Gets the mock cache.
     *
     * @return CacheInterface
     */
    abstract public function getMockCache();

    public function testBasicOperations()
    {
        $cache = $this->getCache();

        // set
        $cacheElement = $cache->set('foo', 'bar', CacheElement::SECOND);
        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $cacheElement);

        // has
        $this->assertTrue($cache->has('foo'));

        // get
        $cacheElement = $cache->get('foo');
        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $cacheElement);

        // flush
        $this->assertTrue($cache->flush('foo'));
        $this->assertFalse($cache->has('foo'));

        $cacheElement = $cache->get('foo');
        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $cacheElement);
        $this->assertTrue($cacheElement->isExpired());
        $this->assertNull($cacheElement->getData());
    }

    public function testFlushWithoutSuccess()
    {
        $cache = $this->getMockCache();

        $this->assertFalse($cache->flush('foo'));
    }

    public function testFlushAll()
    {
        // flush all
        $cache = $this->getCache();
        $cache->set('foo', 'bar', CacheElement::SECOND);
        $cache->set('bar', 'foo', CacheElement::SECOND);

        $this->assertTrue($cache->flushAll());
        $this->assertFalse($cache->has('foo'));
        $this->assertFalse($cache->has('bar'));

        // flush all with prefix
        $cache->set('prefix_foo', 'bar', CacheElement::SECOND);
        $cache->set('prefix_bar', 'foo', CacheElement::SECOND);
        $cache->set('number', 42, CacheElement::SECOND);

        $this->assertTrue($cache->flushAll('prefix_'));
        $this->assertFalse($cache->has('prefix_foo'));
        $this->assertFalse($cache->has('prefix_bar'));
        $this->assertTrue($cache->has('number'));
    }

    public function testNonExistantCache()
    {
        $cache = $this->getCache();
        $cacheElement = $cache->get('invalid');

        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $cacheElement);
        $this->assertTrue($cacheElement->isExpired());
    }

    public function testExpiredCache()
    {
        $cache = $this->getCache();
        $cache->set('expired', "foobar", CacheElement::SECOND);

        sleep(CacheElement::SECOND + 1);

        $cacheElement = $cache->get('expired');

        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $cacheElement);
        $this->assertTrue($cacheElement->isExpired());
        $this->assertNotNull($cacheElement->getData());
    }

    public function testCounter()
    {
        $cache = $this->getCache();
        $counter = $cache->setCounter(new Counter('number', 1));

        $this->assertInstanceOf('Sonatra\Component\Cache\Counter', $counter);
        $this->assertEquals(1, $counter->getValue());

        $counter = $cache->getCounter($counter->getName());
        $this->assertEquals(1, $counter->getValue());

        // increment
        $counter = $cache->increment($counter);
        $this->assertEquals(2, $counter->getValue());

        $counter = $cache->increment($counter, 3);
        $this->assertEquals(5, $counter->getValue());

        // check value stocked in cache
        $counter = $cache->getCounter($counter->getName());
        $this->assertEquals(5, $counter->getValue());

        // decrement
        $counter = $cache->decrement($counter);
        $this->assertEquals(4, $counter->getValue());

        $counter = $cache->decrement($counter, 3);
        $this->assertEquals(1, $counter->getValue());

        // check value stocked in cache
        $counter = $cache->getCounter($counter->getName());
        $this->assertEquals(1, $counter->getValue());

        // test transform string to counter
        $counter = $cache->decrement($counter->getName());
        $this->assertEquals(0, $counter->getValue());
    }

    public function testNonExistantCounter()
    {
        $cache = $this->getCache();
        $counter = $cache->getCounter('nonexist');

        $this->assertInstanceOf('Sonatra\Component\Cache\Counter', $counter);
        $this->assertEquals(0, $counter->getValue());
    }
}
