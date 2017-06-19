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

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\ProxyAdapter as BaseProxyAdapter;

/**
 * Proxy Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ProxyAdapter extends BaseProxyAdapter implements AdapterInterface
{
    use AdapterPrefixesTrait;

    /**
     * {@inheritdoc}
     */
    public function clearByPrefixes(array $prefixes)
    {
        /* @var AdapterInterface|CacheItemPoolInterface $pool */
        $pool = AdapterUtil::getPropertyValue($this, 'pool');

        return $pool instanceof AdapterInterface
            ? $pool->clearByPrefixes($prefixes)
            : $pool->clear();
    }
}
