<?php

/**
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Cache;

use Sonatra\Component\Cache\Exception\InvalidArgumentException;

/**
 * Cache Adapter Interface.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
final class Counter
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $value;

    /**
     * Constructor.
     *
     * @param string $name
     * @param int    $value
     *
     * @throws InvalidArgumentException When the name is not a string for the counter
     * @throws InvalidArgumentException When the value is not a numeric for the counter
     */
    public function __construct($name, $value = 0)
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException('The name is not string for the counter');
        }

        if (!is_int($value)) {
            throw new InvalidArgumentException('The value is not numeric for the counter');
        }

        $this->name  = $name;
        $this->value = $value;
    }

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the value.
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
}
