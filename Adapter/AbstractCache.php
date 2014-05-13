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

use Sonatra\Component\Cache\Counter;

/**
 * Base Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class AbstractCache implements CacheInterface
{
    /**
     * {@inheritdoc}
     */
    public function increment($counter, $value = 1)
    {
        $counter = $this->transformCounter($counter);
        $counter = new Counter($counter->getName(), $counter->getValue() + $value);

        $this->setCounter($counter);

        return $counter;
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($counter, $value = 1)
    {
        $counter = $this->transformCounter($counter);
        $counter = new Counter($counter->getName(), $counter->getValue() + (-1 * $value));

        $this->setCounter($counter);

        return $counter;
    }

    /**
     * Transforms the counter.
     *
     * @param Counter|string $counter
     *
     * @return Counter
     */
    protected function transformCounter($counter)
    {
        if ($counter instanceof Counter) {
            return $counter;
        }

        return $this->getCounter($counter);
    }
}
