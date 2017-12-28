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

/**
 * Adapter deferred Trait.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
trait AdapterDeferredTrait
{
    /**
     * Clear the deferred by prefixes.
     *
     * @param string[] $prefixes The prefixes
     */
    protected function clearDeferredByPrefixes(array $prefixes)
    {
        $deferred = AdapterUtil::getPropertyValue($this, 'deferred');

        foreach ($prefixes as $prefix) {
            foreach ($deferred as $key => $value) {
                if ('' === $prefix || 0 === strpos($key, $prefix)) {
                    unset($deferred[$key]);
                }
            }
        }

        AdapterUtil::setPropertyValue($this, 'deferred', $deferred);
    }
}
