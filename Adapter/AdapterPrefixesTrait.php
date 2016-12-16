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

/**
 * Adapter Trait for clear by prefixes.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 *
 * @method bool clearByPrefix(string $prefix)
 */
trait AdapterPrefixesTrait
{
    /**
     * {@inheritdoc}
     */
    public function clearByPrefixes(array $prefixes)
    {
        $ok = true;

        foreach ($prefixes as $prefix) {
            $ok = $this->clearByPrefix($prefix) && $ok;
        }

        return $ok;
    }
}
