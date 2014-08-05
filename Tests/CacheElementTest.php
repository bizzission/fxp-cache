<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Cache\Tests;

use Sonatra\Component\Cache\CacheElement;

/**
 * PHP Cache Element Tests Suite.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class CacheElementTest extends \PHPUnit_Framework_TestCase
{
    public function testCacheElement()
    {
        $now = new \DateTime();
        $expirationDateProvided = $now->add(new \DateInterval(sprintf('PT%sS', CacheElement::SECOND)));
        $element = new CacheElement('foo', 'bar', CacheElement::SECOND);

        $this->assertEquals('foo', $element->getKey());
        $this->assertEquals('bar', $element->getData());
        $this->assertEquals(CacheElement::SECOND, $element->getTtl());
        $this->assertFalse($element->isExpired());
        $this->assertEquals($expirationDateProvided, $element->getExpirationDate());
    }

    public function testExpiredCacheElement()
    {
        $now = new \DateTime();
        $element = new CacheElement('foo', 'bar', 0, $now->sub(new \DateInterval('PT1S')));

        $this->assertTrue($element->isExpired());
        $this->assertEquals(new \DateTime(), $element->getExpirationDate());
    }
}
