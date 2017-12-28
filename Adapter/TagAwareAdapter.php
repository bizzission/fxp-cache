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

use Symfony\Component\Cache\Adapter\TagAwareAdapter as BaseTagAwareAdapter;

/**
 * Tag Aware Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TagAwareAdapter extends BaseTagAwareAdapter implements TagAwareAdapterInterface
{
    use AdapterDeferredTrait;
    use AdapterPrefixesTrait;

    /**
     * {@inheritdoc}
     */
    public function clearByPrefixes(array $prefixes)
    {
        $itemsAdapter = AdapterUtil::getPropertyValue($this, 'pool');
        $this->clearDeferredByPrefixes($prefixes);

        return $itemsAdapter instanceof AdapterInterface
            ? $itemsAdapter->clearByPrefixes($prefixes)
            : $itemsAdapter->clear();
    }
}
