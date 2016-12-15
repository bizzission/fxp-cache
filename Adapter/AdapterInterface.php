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

/**
 * Cache Adapter Interface.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface AdapterInterface extends BaseAdapterInterface
{
    /**
     * Deletes all items identified by the prefix in the pool.
     *
     * @param string $prefix The prefix
     *
     * @return bool True if the pool was successfully cleared. False if there was an error
     */
    public function clearByPrefix($prefix);
}