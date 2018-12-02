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
use Symfony\Component\Cache\Adapter\PhpArrayAdapter as BasePhpArrayAdapter;

/**
 * PHP Array Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class PhpArrayAdapter extends BasePhpArrayAdapter implements AdapterInterface
{
    use AdapterPrefixesTrait;

    /**
     * {@inheritdoc}
     */
    public function clearByPrefixes(array $prefixes)
    {
        $this->initializeForPrefix();

        /* @var BaseAdapterInterface|AdapterInterface $fallbackPool */
        $fallbackPool = AdapterUtil::getPropertyValue($this, 'pool');
        $cleared = $fallbackPool instanceof AdapterInterface
            ? $this->clearItems($fallbackPool, $prefixes)
            : $this->clear();

        return $cleared;
    }

    /**
     * Load the cache file.
     */
    private function initializeForPrefix()
    {
        $keys = AdapterUtil::getPropertyValue($this, 'keys');
        $values = AdapterUtil::getPropertyValue($this, 'values');

        if (null === $values) {
            $file = AdapterUtil::getPropertyValue($this, 'file');
            $values = @(include $file) ?: [];

            AdapterUtil::setPropertyValue($this, 'keys', $keys);
            AdapterUtil::setPropertyValue($this, 'values', $values);
        }
    }

    /**
     * Clear the items.
     *
     * @param AdapterInterface $fallbackPool The fallback pool
     * @param string[]         $prefixes     The prefixes
     *
     * @return bool
     */
    private function clearItems(AdapterInterface $fallbackPool, array $prefixes)
    {
        $cleared = $fallbackPool->clearByPrefixes($prefixes);
        $keys = AdapterUtil::getPropertyValue($this, 'keys') ?: [];
        $values = AdapterUtil::getPropertyValue($this, 'values') ?: [];
        $warmValues = [];

        foreach ($keys as $key => $valuePrefix) {
            foreach ($prefixes as $prefix) {
                if ('' === $prefix || 0 !== strpos($key, $prefix)) {
                    $warmValues[$key] = $values[$valuePrefix];
                }
            }
        }

        if (\count($values) !== \count($warmValues)) {
            $this->warmUp($warmValues);
        }

        return $cleared;
    }
}
