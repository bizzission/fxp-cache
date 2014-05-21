<?php

/**
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Cache\Tests;

use Sonatra\Component\Cache\Counter;
use Sonatra\Component\Cache\Exception\InvalidArgumentException;

/**
 * PHP Cache Counter Tests Suite.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class CounterTest extends \PHPUnit_Framework_TestCase
{
    public function testCounter()
    {
        $counter = new Counter('foo', 42);

        $this->assertEquals('foo', $counter->getName());
        $this->assertEquals(42, $counter->getValue());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCounterNameException()
    {
        new Counter(42);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCounterValueException()
    {
        new Counter('foo', 'bar');
    }
}
