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

use Symfony\Component\Cache\Adapter\ArrayAdapter as BaseArrayAdapter;

/**
 * Array Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ArrayAdapter extends BaseArrayAdapter implements AdapterInterface
{
    use AdapterPrefixesTrait;

    /**
     * {@inheritdoc}
     */
    public function clearByPrefixes(array $prefixes)
    {
        $values = AdapterUtil::getPropertyValue($this, 'values');
        $expiries = AdapterUtil::getPropertyValue($this, 'expiries');
        $keys = array_unique(array_merge(array_keys($values), array_keys($expiries)));
        $ok = true;

        foreach ($prefixes as $prefix) {
            foreach ($keys as $key) {
                if ($prefix === '' || 0 === strpos($key, $prefix)) {
                    $ok = !$this->deleteItem($key) && $ok ? false : $ok;
                }
            }
        }

        return $ok;
    }
}
