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

use Sonatra\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Filesystem Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FilesystemAdapterTest extends AbstractAdapterTest
{
    protected function setUp()
    {
        $this->adapter = new FilesystemAdapter('', 0);
        $this->adapter->clear();
    }

    public static function tearDownAfterClass()
    {
        $fs = new Filesystem();
        $fs->remove(sys_get_temp_dir().'/symfony-cache');
    }
}
