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
     * @param string[]         $prefixes     The prefixes
     *
     * @return bool
     */
    private function clearItems(AdapterInterface $fallbackPool, array $prefixes)
    {
        $cleared = $fallbackPool->clearByPrefixes($prefixes);
        $values = AdapterUtil::getPropertyValue($this, 'values');
        $save = false;

        foreach ($values as $key => $value) {
            foreach ($prefixes as $prefix) {
                if ('' !== $prefix && 0 === strpos($key, $prefix)) {
                    unset($values[$key]);
                    $save = true;
                }
            }
        }

        if ($save) {
            $this->warmUp($values);
        }

        return $cleared;
    }
}
