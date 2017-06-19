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
 * @method bool clearByPrefixes(array $prefixes)
 */
trait AdapterPrefixesTrait
{
    /**
     * {@inheritdoc}
     */
    public function clearByPrefix($prefix)
    {
        return $this->clearByPrefixes(array($prefix));
    }
}
