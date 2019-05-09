<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Cache\Adapter;

use Symfony\Component\Cache\Adapter\AdapterInterface as BaseAdapterInterface;
use Symfony\Component\Cache\Adapter\ChainAdapter as BaseChainAdapter;

/**
 * Chain Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class ChainAdapter extends BaseChainAdapter implements AdapterInterface
{
    use AdapterPrefixesTrait;

    /**
     * {@inheritdoc}
     */
    public function clearByPrefixes(array $prefixes): bool
    {
        /** @var BaseAdapterInterface[] $adapters */
        $adapters = AdapterUtil::getPropertyValue($this, 'adapters');
        $cleared = true;

        foreach ($adapters as $adapter) {
            $cleared = $adapter instanceof AdapterInterface
                ? $adapter->clearByPrefixes($prefixes) && $cleared
                : $adapter->clear() && $cleared;
        }

        return $cleared;
    }
}
