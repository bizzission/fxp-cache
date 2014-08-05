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
    const PREFIX1 = 'global_prefix1_';
    const PREFIX2 = 'global_prefix2_';

    /**
     * @var bool
     */
    protected $testSkipped = false;

    /**
     * Gets the cache.
     *
     * @param string $prefix
     *
     * @return CacheInterface
     */
    abstract public function getCache($prefix = null);

    /**
     * Gets the mock cache.
     *
     * @param string $prefix
     *
     * @return CacheInterface
     */
    abstract public function getMockCache($prefix = null);

    public function testSetOperation()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $element1 = $cache1->set('foo', 'bar', CacheElement::SECOND);
        $element2 = $cache2->set('foo', 'bar', CacheElement::SECOND);

        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $element1);
        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $element2);
    }

    public function testHasOperation()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $cache1->set('foo', 'bar', CacheElement::SECOND);
        $cache2->set('foo', 'bar', CacheElement::SECOND);

        $this->assertTrue($cache1->has('foo'));
        $this->assertTrue($cache2->has('foo'));
    }

    public function testGetOperation()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $cache1->set('foo', 'bar', CacheElement::SECOND);
        $cache2->set('foo', 'bar', CacheElement::SECOND);

        $element1 = $cache1->get('foo');
        $element2 = $cache2->get('foo');

        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $element1);
        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $element2);
    }

    public function testGetOverrideOperation()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $cache1->set('foo', 'bar', CacheElement::SECOND);
        $cache2->set('foo', 'bar', CacheElement::SECOND);

        $element1 = $cache1->set('foo', 'bar2', CacheElement::SECOND);
        $element2 = $cache2->set('foo', 'bar2', CacheElement::SECOND);

        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $element1);
        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $element2);
    }

    public function testFlushWithSuccess()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $cache1->set('foo', 'bar', CacheElement::SECOND);
        $cache2->set('foo', 'bar', CacheElement::SECOND);

        $this->assertTrue($cache1->flush('foo'));
        $this->assertTrue($cache2->flush('foo'));
        $this->assertFalse($cache1->has('foo'));
        $this->assertFalse($cache2->has('foo'));
    }

    public function testFlushWithoutSuccess()
    {
        $cache1 = $this->getMockCache(self::PREFIX1);
        $cache2 = $this->getMockCache();

        $this->assertFalse($cache1->flush('foo'));
        $this->assertFalse($cache2->flush('foo'));
    }

    public function testFlushAllWithoutPrefix()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $cache1->set('foo', 'bar', CacheElement::SECOND);
        $cache1->set('bar', 'foo', CacheElement::SECOND);
        $cache2->set('foo', 'bar', CacheElement::SECOND);
        $cache2->set('bar', 'foo', CacheElement::SECOND);

        $this->assertTrue($cache1->flushAll());
        $this->assertTrue($cache2->flushAll());

        $this->assertFalse($cache1->has('foo'));
        $this->assertFalse($cache2->has('foo'));

        $this->assertFalse($cache1->has('bar'));
        $this->assertFalse($cache2->has('bar'));
    }

    public function testFlushAllWithPrefix()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $cache1->set('custom_prefix_foo', 'bar', CacheElement::SECOND);
        $cache2->set('custom_prefix_foo', 'bar', CacheElement::SECOND);
        $cache1->set('custom_prefix_bar', 'foo', CacheElement::SECOND);
        $cache2->set('custom_prefix_bar', 'foo', CacheElement::SECOND);
        $cache1->set('number', 42, CacheElement::SECOND);
        $cache2->set('number', 42, CacheElement::SECOND);

        $this->assertTrue($cache1->flushAll('custom_prefix_'));
        $this->assertTrue($cache2->flushAll('custom_prefix_'));
        $this->assertFalse($cache1->has('custom_prefix_foo'));
        $this->assertFalse($cache2->has('custom_prefix_foo'));
        $this->assertFalse($cache1->has('custom_prefix_bar'));
        $this->assertFalse($cache2->has('custom_prefix_bar'));
        $this->assertTrue($cache1->has('number'));
        $this->assertTrue($cache2->has('number'));
    }

    public function testNonExistantCache()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $element1 = $cache1->get('invalid');
        $element2 = $cache2->get('invalid');

        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $element1);
        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $element2);
        $this->assertTrue($element1->isExpired());
        $this->assertTrue($element2->isExpired());
        $this->assertNull($element1->getData());
        $this->assertNull($element2->getData());
    }

    public function testExpiredCache()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $cache1->set('expired', "foobar", CacheElement::SECOND);
        $cache2->set('expired', "foobar", CacheElement::SECOND);

        sleep(CacheElement::SECOND + 1);

        $element1 = $cache1->get('expired');
        $element2 = $cache1->get('expired');

        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $element1);
        $this->assertInstanceOf('Sonatra\Component\Cache\CacheElement', $element2);
        $this->assertTrue($element1->isExpired());
        $this->assertTrue($element2->isExpired());
        $this->assertNull($element1->getData());
        $this->assertNull($element2->getData());
    }

    public function testCounterSetOperation()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $counter1 = $cache1->setCounter(new Counter('number', 1));
        $counter2 = $cache2->setCounter(new Counter('number', 1));

        $this->assertInstanceOf('Sonatra\Component\Cache\Counter', $counter1);
        $this->assertInstanceOf('Sonatra\Component\Cache\Counter', $counter2);
        $this->assertEquals(1, $counter1->getValue());
        $this->assertEquals(1, $counter2->getValue());
    }

    public function testCounterGetOperation()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $counter1 = $cache1->setCounter(new Counter('number', 1));
        $counter2 = $cache2->setCounter(new Counter('number', 1));

        $counter1 = $cache1->getCounter($counter1->getName());
        $counter2 = $cache2->getCounter($counter2->getName());

        $this->assertEquals(1, $counter1->getValue());
        $this->assertEquals(1, $counter2->getValue());
    }

    public function testCounterIncrementOperation()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $counter1 = $cache1->setCounter(new Counter('number', 1));
        $counter2 = $cache2->setCounter(new Counter('number', 1));

        $counter1 = $cache1->increment($counter1);
        $counter2 = $cache2->increment($counter2);
        $this->assertEquals(2, $counter1->getValue());
        $this->assertEquals(2, $counter2->getValue());

        // check value stocked in cache
        $counter1 = $cache1->getCounter($counter1->getName());
        $counter2 = $cache2->getCounter($counter2->getName());
        $this->assertEquals(2, $counter1->getValue());
        $this->assertEquals(2, $counter2->getValue());

        // increment next
        $counter1 = $cache1->increment($counter1, 3);
        $counter2 = $cache2->increment($counter2, 3);
        $this->assertEquals(5, $counter1->getValue());
        $this->assertEquals(5, $counter2->getValue());

        // check value stocked in cache
        $counter1 = $cache1->getCounter($counter1->getName());
        $counter2 = $cache2->getCounter($counter2->getName());
        $this->assertEquals(5, $counter1->getValue());
        $this->assertEquals(5, $counter2->getValue());
    }

    public function testCounterDecrementOperation()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $counter1 = $cache1->setCounter(new Counter('number', 5));
        $counter2 = $cache2->setCounter(new Counter('number', 5));

        $counter1 = $cache1->decrement($counter1);
        $counter2 = $cache2->decrement($counter2);
        $this->assertEquals(4, $counter1->getValue());
        $this->assertEquals(4, $counter2->getValue());

        // check value stocked in cache
        $counter1 = $cache1->getCounter($counter1->getName());
        $counter2 = $cache2->getCounter($counter2->getName());
        $this->assertEquals(4, $counter1->getValue());
        $this->assertEquals(4, $counter2->getValue());

        // decrement next
        $counter1 = $cache1->decrement($counter1, 3);
        $counter2 = $cache2->decrement($counter2, 3);
        $this->assertEquals(1, $counter1->getValue());
        $this->assertEquals(1, $counter2->getValue());

        // check value stocked in cache
        $counter1 = $cache1->getCounter($counter1->getName());
        $counter2 = $cache2->getCounter($counter2->getName());
        $this->assertEquals(1, $counter1->getValue());
        $this->assertEquals(1, $counter2->getValue());
    }

    public function testTransformStringToCounter()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $counter1 = $cache1->increment('number');
        $counter2 = $cache2->increment('number');

        $this->assertEquals(1, $counter1->getValue());
        $this->assertEquals(1, $counter2->getValue());
    }

    public function testNonExistantCounter()
    {
        $cache1 = $this->getCache(self::PREFIX1);
        $cache2 = $this->getCache(self::PREFIX2);

        $counter1 = $cache1->getCounter('nonexist');
        $counter2 = $cache2->getCounter('nonexist');

        $this->assertInstanceOf('Sonatra\Component\Cache\Counter', $counter1);
        $this->assertInstanceOf('Sonatra\Component\Cache\Counter', $counter2);
        $this->assertEquals(0, $counter1->getValue());
        $this->assertEquals(0, $counter2->getValue());
    }
}
