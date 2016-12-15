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
use Symfony\Component\Cache\Adapter\PhpArrayAdapter as BasePhpArrayAdapter;

/**
 * PHP Array Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PhpArrayAdapter extends BasePhpArrayAdapter implements AdapterInterface
{
    /**
     * {@inheritdoc}
     */
    public function clearByPrefix($prefix)
    {
        $this->initializeForPrefix();

        /* @var BaseAdapterInterface|AdapterInterface $fallbackPool */
        $fallbackPool = AdapterUtil::getPropertyValue($this, 'fallbackPool');
        $cleared = '' !== $prefix && $fallbackPool instanceof AdapterInterface
            ? $this->clearItems($fallbackPool, $prefix)
            : $this->clear();

        return $cleared;
    }

    /**
     * Load the cache file.
     */
    private function initializeForPrefix()
    {
        $values = AdapterUtil::getPropertyValue($this, 'values');

        if (null === $values) {
            $file = AdapterUtil::getPropertyValue($this, 'file');
            $values = @(include $file) ?: array();

            AdapterUtil::setPropertyValue($this, 'values', $values);
        }
    }

    /**
     * Clear the items.
     *
     * @param AdapterInterface $fallbackPool The fallback pool
     * @param string           $prefix       The prefix
     *
     * @return bool
     */
    private function clearItems(AdapterInterface $fallbackPool, $prefix)
    {
        $cleared = $fallbackPool->clearByPrefix($prefix);
        $values = AdapterUtil::getPropertyValue($this, 'values');
        $save = false;

        foreach ($values as $key => $value) {
            if (0 === strpos($key, $prefix)) {
                unset($values[$key]);
                $save = true;
            }
        }

        if ($save) {
            $this->warmUp($values);
        }

        return $cleared;
    }
}
