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

use Symfony\Component\Cache\Adapter\AdapterInterface as BaseAdapterInterface;
use Symfony\Component\Cache\Adapter\ChainAdapter as BaseChainAdapter;

/**
 * Chain Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ChainAdapter extends BaseChainAdapter implements AdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public function clearByPrefix($prefix)
    {
        /* @var BaseAdapterInterface[] $adapters */
        $adapters = AdapterUtil::getPropertyValue($this, 'adapters');
        $cleared = true;

        foreach ($adapters as $adapter) {
            if ($adapter instanceof AdapterInterface) {
                $cleared = $adapter->clearByPrefix($prefix) && $cleared;
            }
        }

        return $cleared;
    }
}
