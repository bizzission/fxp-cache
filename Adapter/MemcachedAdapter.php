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

use Symfony\Component\Cache\Adapter\MemcachedAdapter as BaseMemcachedAdapter;

/**
 * Memcached Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class MemcachedAdapter extends BaseMemcachedAdapter implements AdapterInterface
{
    use AdapterTrait;

    /**
     * {@inheritdoc}
     */
    protected function doClearByPrefix($namespace, $prefix)
    {
        $ok = true;

        foreach ($this->getAllItems() as $key) {
            $ok = !$this->doClearItem($key, $namespace.$prefix) && $ok ? false : $ok;
        }

        return $ok;
    }

    /**
     * Delete the key that starting by the prefix.
     *
     * @param string $id     The cache item id
     * @param string $prefix The full prefix
     *
     * @return bool
     */
    protected function doClearItem($id, $prefix)
    {
        $key = substr($id, strrpos($id, ':') + 1);
        $res = true;

        if ('' === $prefix || 0 === strpos($id, $prefix)) {
            $res = $this->deleteItem($key);
        }

        return $res;
    }

    /**
     * Get all items.
     *
     * @return string[]
     */
    protected function getAllItems()
    {
        $client = AdapterUtil::getPropertyValue($this, 'client');
        $res = $client->getAllKeys();

        return false !== $res ? $res : array();
    }
}
