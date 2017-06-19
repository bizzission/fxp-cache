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
use Sonatra\Component\Cache\Adapter\TagAwareAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter as SymfonyArrayAdapter;

/**
 * Tag Aware Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TagAwareAdapterTest extends AbstractAdapterTest
{
    protected function setUp()
    {
        $this->adapter = new TagAwareAdapter(new ArrayAdapter(), new SymfonyArrayAdapter());
        $this->adapter->clear();
    }
}
