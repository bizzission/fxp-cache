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
    /**
     * @var \ReflectionClass|null
     */
    private $ref;

    /**
     * {@inheritdoc}
     */
    public function clearByPrefix($prefix)
    {
        $this->clearDeferredByPrefix($prefix);

        return $this->doClearByPrefix($this->getNamespace(), $prefix);
    }

    /**
     * Clear the deferred by prefix.
     *
     * @param string $prefix The prefix
     */
    protected function clearDeferredByPrefix($prefix)
    {
        $deferred = $this->getPropertyValue('deferred');

        foreach ($deferred as $key => $value) {
            if ($prefix === '' || 0 === strpos($key, $prefix)) {
                unset($deferred[$key]);
            }
        }

        $this->setPropertyValue('deferred', $deferred);
    }

    /**
     * Get the namespace.
     *
     * @return string
     */
    protected function getNamespace()
    {
        return $this->getPropertyValue('namespace');
    }

    /**
     * Set the value of private property.
     *
     * @param string $property The property name
     * @param mixed  $value    The value
     *
     * @return self
     */
    protected function setPropertyValue($property, $value)
    {
        $prop = $this->getPrivateProperty($property);
        $prop->setAccessible(true);
        $prop->setValue($this, $value);
        $prop->setAccessible(false);

        return $this;
    }

    /**
     * Get the value of private property.
     *
     * @param string $property The property name
     *
     * @return mixed
     */
    protected function getPropertyValue($property)
    {
        $prop = $this->getPrivateProperty($property);
        $prop->setAccessible(true);
        $value = $prop->getValue($this);
        $prop->setAccessible(false);

        return $value;
    }

    /**
     * Get the private property.
     *
     * @param string                $property       The property name
     * @param \ReflectionClass|null reflectionClass The reflection class
     *
     * @return \ReflectionProperty
     */
    private function getPrivateProperty($property, $reflectionClass = null)
    {
        $reflectionClass = $reflectionClass ?: $this->getReflectionClass();

        if (!$reflectionClass->hasProperty($property) && $reflectionClass->getParentClass()) {
            return $this->getPrivateProperty($property, $reflectionClass->getParentClass());
        }

        return $reflectionClass->getProperty($property);
    }

    /**
     * Get the reflection class.
     *
     * @return \ReflectionClass
     */
    private function getReflectionClass()
    {
        if (null === $this->ref) {
            $this->ref = new \ReflectionClass($this);
        }

        return $this->ref;
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
