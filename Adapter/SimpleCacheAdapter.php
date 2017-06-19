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

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\SimpleCacheAdapter as BaseSimpleCacheAdapter;

/**
 * Simple Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class SimpleCacheAdapter extends BaseSimpleCacheAdapter implements AdapterInterface
{
    use AdapterPrefixesTrait;
    use AdapterDeferredTrait;

    /**
     * {@inheritdoc}
     */
    public function clearByPrefixes(array $prefixes)
    {
        $this->clearDeferredByPrefixes($prefixes);

        /* @var CacheInterface $pool */
        $pool = AdapterUtil::getPropertyValue($this, 'pool');

        return $pool->clear();
    }
}
