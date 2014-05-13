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

use Sonatra\Component\Cache\Adapter\ApcCache;

/**
 * PHP Cache Tests Suite.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ApcCacheTest extends AbstractCacheTest
{
    /**
     * {@inheritdoc}
     */
    public function getCache()
    {
        return new ApcCache('prefix_');
    }

    /**
     * {@inheritdoc}
     */
    public function getMockCache()
    {
        return $this->getCache();
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        if (!function_exists('apc_store')) {
            $this->markTestSkipped('APC is not installed');
        }

        if (ini_get('apc.enable_cli') == 0) {
            $this->markTestSkipped('APC is not enabled in cli, please add apc.enable_cli=On into the php.ini file');
        }

        apc_clear_cache();
    }

    /**
     * Clean up all.
     */
    public function tearDown()
    {
        apc_clear_cache();
    }
}
