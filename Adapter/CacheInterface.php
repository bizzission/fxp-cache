<?php

/**
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Cache\Adapter;

use Sonatra\Component\Cache\CacheElement;
use Sonatra\Component\Cache\Counter;

/**
 * Cache Adapter Interface.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
interface CacheInterface
{
    /**
     * Sets the key.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     * @param int    $ttl   The time to live
     *
     * @return CacheElement
     */
    public function set($key, $value, $ttl = CacheElement::HOUR);

    /**
     * Gets the key.
     *
     * @param string $key The key
     *
     * @return CacheElement
     */
    public function get($key);

    /**
     * Check if the cache has the key.
     *
     * @param string $key The key
     *
     * @return bool
     */
    public function has($key);

    /**
     * Flushes data from cache identified by key.
     *
     * @param string $key The key
     *
     * @return bool
     */
    public function flush($key);

    /**
     * Flushes all data from cache or flushes all data identified by prefix key.
     *
     * @param null|string $prefix The prefix key
     *
     * @return bool
     */
    public function flushAll($prefix = null);

    /**
     * Sets the counter.
     *
     * @param Counter $counter The counter
     *
     * @return Counter
     */
    public function setCounter(Counter $counter);

    /**
     * Gets the counter.
     *
     * @param string $counter The counter
     *
     * @return Counter
     */
    public function getCounter($counter);

    /**
     * Increments the counter.
     *
     * @param Counter|string $counter The counter
     * @param int            $value   The value
     *
     * @return Counter
     */
    public function increment($counter, $value = 1);

    /**
     * Decrements the counter.
     *
     * @param Counter|string $counter The counter
     * @param int            $value   The value
     *
     * @return Counter
     */
    public function decrement($counter, $value = 1);
}
