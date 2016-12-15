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

use Sonatra\Component\Cache\Adapter\ApcuAdapter;

/**
 * Apcu Cache Adapter Test.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ApcuAdapterTest extends AbstractAdapterTest
{
    public function setUp()
    {
        if (!function_exists('apcu_fetch') || !ini_get('apc.enabled') || ('cli' === PHP_SAPI && !ini_get('apc.enable_cli'))) {
            $this->markTestSkipped('APCu extension is required.');
        }

        $this->adapter = new ApcuAdapter(str_replace('\\', '.', __CLASS__).static::PREFIX_GLOBAL, 0);
        $this->adapter->clear();
    }
}
