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

use Fxp\Component\Cache\Adapter\ArrayAdapter;
use Fxp\Component\Cache\Adapter\ProxyAdapter;

/**
 * Proxy Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 *
 * @internal
 */
final class ProxyAdapterTest extends AbstractAdapterTest
{
    protected function setUp(): void
    {
        $this->adapter = new ProxyAdapter(new ArrayAdapter(), '', 0);
        $this->adapter->clear();
    }
}
