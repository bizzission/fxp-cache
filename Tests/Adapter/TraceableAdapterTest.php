<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Cache\Tests\Adapter;

use Fxp\Component\Cache\Adapter\NullAdapter;
use Fxp\Component\Cache\Adapter\TraceableAdapter;
use Symfony\Component\Cache\Adapter\NullAdapter as SymfonyNullAdapter;

/**
 * Traceable Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TraceableAdapterTest extends AbstractAdapterTest
{
    protected function setUp()
    {
        $this->adapter = new TraceableAdapter(new NullAdapter());
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
        return array(
            array(new TraceableAdapter(new NullAdapter())),
            array(new TraceableAdapter(new SymfonyNullAdapter())),
        );
    }

    /**
     * @dataProvider getAdapters
     *
     * @param TraceableAdapter $adapter The adapter
     */
    public function testClearByPrefixWithDifferentAdapter(TraceableAdapter $adapter)
    {
        $res = $adapter->clearByPrefix(static::PREFIX_1);
        $this->assertTrue($res);
    }

    /**
     * @dataProvider getAdapters
     *
     * @param TraceableAdapter $adapter The adapter
     */
    public function testClearByPrefixesWithDifferentAdapter(TraceableAdapter $adapter)
    {
        $res = $adapter->clearByPrefixes(array(static::PREFIX_1, static::PREFIX_2));
        $this->assertTrue($res);
    }
}
