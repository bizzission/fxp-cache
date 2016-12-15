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

use Symfony\Component\Cache\Adapter\ApcuAdapter as BaseApcuAdapter;

/**
 * Apcu Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ApcuAdapter extends BaseApcuAdapter implements AdapterInterface
{
    use AdapterTrait;

    /**
     * {@inheritdoc}
     */
    protected function doClearByPrefix($namespace, $prefix)
    {
        $ok = true;

        foreach ($this->getAllItems() as $item) {
            $ok = !$this->doClearItem($item, $namespace.$prefix) && $ok ? false : $ok;
        }

        return $ok;
    }

    /**
     * Delete the key that starting by the prefix.
     *
     * @param array  $item   The cache item
     * @param string $prefix The full prefix
     *
     * @return bool
     */
    protected function doClearItem(array $item, $prefix)
    {
        $id = $item['info'];
        $key = substr($id, strrpos($id, ':') + 1);
        $res = true;

        if ($prefix === '' || 0 === strpos($id, $prefix)) {
            $res = $this->deleteItem($key);
        }

        return $res;
    }

    /**
     * Get all items.
     *
     * @return array[]
     */
    protected function getAllItems()
    {
        $info = apcu_cache_info();

        return $info['cache_list'];
    }
}
