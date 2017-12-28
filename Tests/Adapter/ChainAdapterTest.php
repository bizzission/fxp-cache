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
use Fxp\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter as SymfonyArrayAdapter;

/**
 * Chain Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class ChainAdapterTest extends AbstractAdapterTest
{
    protected function setUp()
    {
        $this->adapter = new ChainAdapter([
            new SymfonyArrayAdapter(),
            new ArrayAdapter(),
        ]);
        $this->adapter->clear();
    }
}
