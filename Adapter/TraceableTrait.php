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
 * @method bool start(string $name)
 */
trait TraceableTrait
{
    /**
     * {@inheritdoc}
     */
    public function clearByPrefix($prefix)
    {
        $event = $this->start(__FUNCTION__);
        $event->result['prefix'] = $prefix;

        try {
            return $event->result['result'] = $this->pool->clearByPrefix($prefix);
        } finally {
            $event->end = microtime(true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearByPrefixes(array $prefixes)
    {
        $event = $this->start(__FUNCTION__);
        $event->result['prefixes'] = $prefixes;

        try {
            return $event->result['result'] = $this->pool->clearByPrefixes($prefixes);
        } finally {
            $event->end = microtime(true);
        }
    }
}
