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
 * Adapter Trait.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
trait AdapterTrait
{
    use AdapterPrefixesTrait;
    use AdapterDeferredTrait;

    /**
     * {@inheritdoc}
     */
    public function clearByPrefix($prefix)
    {
        $this->clearDeferredByPrefixes(array($prefix));
        $namespace = AdapterUtil::getPropertyValue($this, 'namespace');

        return $this->doClearByPrefix($namespace, $prefix);
    }

    /**
     * Action to delete all items identified by the prefix in the pool.
     *
     * @param string $namespace The namespace
     * @param string $prefix    The prefix
     *
     * @return bool
     */
    abstract protected function doClearByPrefix($namespace, $prefix);
}
