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

use Sonatra\Component\Cache\Adapter\ArrayAdapter;
use Sonatra\Component\Cache\Adapter\ProxyAdapter;

/**
 * Proxy Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ProxyAdapterTest extends AbstractAdapterTest
{
    protected function setUp()
    {
        $this->adapter = new ProxyAdapter(new ArrayAdapter(), '', 0);
        $this->adapter->clear();
    }
}
