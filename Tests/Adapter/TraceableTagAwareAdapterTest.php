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
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface as SymfonyTagAwareInterface;

/**
 * Traceable Tag Aware Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TraceableTagAwareAdapterTest extends AbstractAdapterTest
{
    /**
     * @var TagAwareAdapterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $tagAwareAdapter;

    protected function setUp()
    {
        $this->tagAwareAdapter = $this->getTagAwareAdapter();
        $this->adapter = new TraceableTagAwareAdapter($this->tagAwareAdapter);
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

    public function getAdapters()
    {
        /* @var SymfonyTagAwareInterface|\PHPUnit_Framework_MockObject_MockObject $symfonyAdapter */
        $symfonyAdapter = $this->getMockBuilder(SymfonyTagAwareInterface::class)->getMock();
        $this->mockAdapter($symfonyAdapter);

        return array(
            array(new TraceableTagAwareAdapter($this->getTagAwareAdapter())),
            array(new TraceableTagAwareAdapter($symfonyAdapter)),
        );
    }

    /**
     * @dataProvider getAdapters
     *
     * @param TraceableTagAwareAdapter $adapter The adapter
     */
    public function testClearByPrefixWithDifferentAdapter(TraceableTagAwareAdapter $adapter)
    {
        $res = $adapter->clearByPrefix(static::PREFIX_1);
        $this->assertTrue($res);
    }

    /**
     * @dataProvider getAdapters
     *
     * @param TraceableTagAwareAdapter $adapter The adapter
     */
    public function testClearByPrefixesWithDifferentAdapter(TraceableTagAwareAdapter $adapter)
    {
        $res = $adapter->clearByPrefixes(array(static::PREFIX_1, static::PREFIX_2));
        $this->assertTrue($res);
    }

    /**
     * @return TagAwareAdapterInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getTagAwareAdapter()
    {
        $tagAwareAdapter = $this->getMockBuilder(TagAwareAdapterInterface::class)->getMock();
        $this->mockAdapter($tagAwareAdapter);

        $tagAwareAdapter->expects($this->any())
            ->method('clearByPrefix')
            ->willReturn(true);

        $tagAwareAdapter->expects($this->any())
            ->method('clearByPrefixes')
            ->willReturn(true);

        return $tagAwareAdapter;
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject $adapter The mocked adapter
     */
    private function mockAdapter($adapter)
    {
        $self = $this;
        $adapter->expects($this->any())
            ->method('getItem')
            ->willReturnCallback(function ($value) use ($self) {
                $item = $self->getMockBuilder(CacheItemInterface::class)->getMock();
                $item->expects($this->any())
                    ->method('getKey')
                    ->willReturn($value);

                return $item;
            });

        $adapter->expects($this->any())
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

        $adapter->expects($this->any())
            ->method('hasItem')
            ->willReturn(true);

        $adapter->expects($this->any())
            ->method('clear')
            ->willReturn(true);

        $adapter->expects($this->any())
            ->method('deleteItem')
            ->willReturn(true);

        $adapter->expects($this->any())
            ->method('deleteItems')
            ->willReturn(true);

        $adapter->expects($this->any())
            ->method('save')
            ->willReturn(true);

        $adapter->expects($this->any())
            ->method('saveDeferred')
            ->willReturn(true);

        $adapter->expects($this->any())
            ->method('commit')
            ->willReturn(true);
    }
}
