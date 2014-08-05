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
 * APC Cache Tests Suite.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ApcCacheTest extends AbstractCacheTest
{
    /**
     * {@inheritdoc}
     */
    public function getCache($prefix = null)
    {
        return new ApcCache($prefix);
    }

    /**
     * {@inheritdoc}
     */
    public function getMockCache($prefix = null)
    {
        return $this->getCache($prefix);
    }

    /**
     * Set up.
     */
    public function setUp()
    {
        if (!function_exists('apc_store')) {
            $this->testSkipped = true;
            $this->markTestSkipped('APC is not installed');
        }

        if (ini_get('apc.enable_cli') == 0) {
            $this->testSkipped = true;
            $this->markTestSkipped('APC is not enabled in cli, please add apc.enable_cli=On into the php.ini file');
        }

        apc_clear_cache();
    }

    /**
     * Clean up all.
     */
    public function tearDown()
    {
        if ($this->testSkipped) {
            return;
        }

        apc_clear_cache();
    }
}
