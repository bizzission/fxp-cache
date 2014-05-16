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

use Sonatra\Component\Cache\CacheElement;
use Sonatra\Component\Cache\Counter;

/**
 * APC Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ApcCache extends AbstractCache
{
    /**
     * @var string
     */
    protected $prefix;

    /**
     * Constructor.
     *
     * @param string $prefix A prefix to avoid clash between instances
     */
    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = CacheElement::HOUR)
    {
        $key = $this->getCacheKey($key);
        $createAt = new \DateTime();
        $element = new CacheElement($key, $value, $ttl, $createAt);

        apc_store($key, $element, $element->getTtl());

        return $element;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        $keyPrefixed = $this->getCacheKey($key);

        if (apc_exists($keyPrefixed)) {
            /* @var CacheElement $element */
            $element = apc_fetch($keyPrefixed);

            if ($element instanceof CacheElement && !$element->isExpired()) {
                return $element;
            }
        }

        return $this->createInvalidElement($key);
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return apc_exists($this->getCacheKey($key));
    }

    /**
     * {@inheritdoc}
     */
    public function flush($key)
    {
        return apc_delete($this->getCacheKey($key));
    }

    /**
     * {@inheritdoc}
     */
    public function flushAll($prefix = null)
    {
        $success = true;
        $info = apc_cache_info();

        foreach ($info['cache_list'] as $item) {
            $key = $item['key'];
            $fPrefix = sprintf('%s%s', $this->prefix, $prefix);

            if (0 === strpos($key, $fPrefix)) {
                $res = apc_delete($key);
                $success = !$res && $success ? false : $success;
            }
        }

        return $success;
    }

    /**
     * {@inheritdoc}
     */
    public function setCounter(Counter $counter)
    {
        $key = $this->getCacheKey($counter->getName());

        apc_store($key, $counter->getValue());

        return $counter;
    }

    /**
     * {@inheritdoc}
     */
    public function getCounter($counter)
    {
        $key = $this->getCacheKey($counter);
        $value = 0;

        if (apc_exists($key)) {
            $value = apc_fetch($key);
        }

        return new Counter($counter, $value);
    }

    /**
     * Gets the cache key.
     *
     * @param string $key The cache key
     *
     * @return string
     */
    protected function getCacheKey($key)
    {
        return sprintf('%s%s', $this->prefix, $key);
    }
}
