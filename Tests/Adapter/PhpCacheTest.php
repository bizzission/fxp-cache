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

use Sonatra\Component\Cache\Adapter\PhpCache;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * PHP Cache Tests Suite.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PhpCacheTest extends AbstractCacheTest
{
    /**
     * {@inheritdoc}
     */
    public function getCache()
    {
        return new PhpCache(self::getDir(), new Filesystem());
    }

    /**
     * {@inheritdoc}
     */
    public function getMockCache()
    {
        $fs = $this->getMock('Symfony\Component\Filesystem\Filesystem');

        $fs->expects($this->once())
            ->method('remove')
            ->will($this->throwException(new IOException('message')));

        /* @var Filesystem $fs */

        return new PhpCache(self::getDir(), $fs);
    }

    /**
     * Clean up all.
     */
    public function tearDown()
    {
        $fs = new Filesystem();
        $fs->remove(static::getDir());
    }

    /**
     * Gets temp cache directory.
     *
     * @return string
     */
    protected static function getDir()
    {
        return sys_get_temp_dir() . '/phpunit_sonatra_cache_tests';
    }
}
