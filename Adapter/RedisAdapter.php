<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Cache\Adapter;

use Symfony\Component\Cache\Adapter\RedisAdapter as BaseRedisAdapter;

/**
 * Redis Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class RedisAdapter extends BaseRedisAdapter implements AdapterInterface
{
    use AdapterTrait;

    /**
     * {@inheritdoc}
     */
    protected function doClearByPrefix($namespace, $prefix)
    {
        return $this->doClear($namespace.$prefix);
    }
}