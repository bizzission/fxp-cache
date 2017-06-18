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

use Psr\Cache\CacheItemInterface;
use Sonatra\Component\Cache\Adapter\TagAwareAdapterInterface;
use Sonatra\Component\Cache\Adapter\TraceableTagAwareAdapter;

/**
 * Traceable Tag Aware Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TraceableTagAwareAdapterTest extends AbstractAdapterTest
{
    protected function setUp()
    {
        $self = $this;

        /* @var TagAwareAdapterInterface|\PHPUnit_Framework_MockObject_MockObject $tagAwareAdapter */
        $tagAwareAdapter = $this->getMockBuilder(TagAwareAdapterInterface::class)->getMock();
        $tagAwareAdapter->expects($this->any())
            ->method('getItem')
            ->willReturnCallback(function ($value) use ($self) {
                $item = $self->getMockBuilder(CacheItemInterface::class)->getMock();
                $item->expects($this->any())
                    ->method('getKey')
                    ->willReturn($value);

                return $item;
            });

        $tagAwareAdapter->expects($this->any())
            ->method('getItems')
            ->willReturnCallback(function ($values) use ($self) {
                $res = array();

                foreach ($values as $value) {
                    $item = $self->getMockBuilder(CacheItemInterface::class)->getMock();
                    $item->expects($this->any())
                        ->method('getKey')
                        ->willReturn($value);

                    $res[] = $item;
                }

                return $res;
            });

        $tagAwareAdapter->expects($this->any())
            ->method('hasItem')
            ->willReturn(true);

        $tagAwareAdapter->expects($this->any())
            ->method('clear')
            ->willReturn(true);

        $tagAwareAdapter->expects($this->any())
            ->method('deleteItem')
            ->willReturn(true);

        $tagAwareAdapter->expects($this->any())
            ->method('deleteItems')
            ->willReturn(true);

        $tagAwareAdapter->expects($this->any())
            ->method('save')
            ->willReturn(true);

        $tagAwareAdapter->expects($this->any())
            ->method('saveDeferred')
            ->willReturn(true);

        $tagAwareAdapter->expects($this->any())
            ->method('commit')
            ->willReturn(true);

        $tagAwareAdapter->expects($this->any())
            ->method('clearByPrefix')
            ->willReturn(true);

        $tagAwareAdapter->expects($this->any())
            ->method('clearByPrefixes')
            ->willReturn(true);

        $this->adapter = new TraceableTagAwareAdapter($tagAwareAdapter);
        $this->adapter->clear();
    }

    public function testClearByPrefix()
    {
        $res = $this->adapter->clearByPrefix(static::PREFIX_1);
        $this->assertTrue($res);
    }

    public function testClearByPrefixWithDeferredItem()
    {
        $res = $this->adapter->clearByPrefix(static::PREFIX_1);
        $this->assertTrue($res);
    }
}
