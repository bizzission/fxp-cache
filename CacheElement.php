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

/**
 * Cache Adapter Interface.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
final class CacheElement
{
    const SECOND = 1;
    const MINUTE = 60;
    const HOUR   = 3600;
    const DAY    = 86400;
    const WEEK   = 604800;
    const MONTH  = 2.63e+6;
    const YEAR   = 31.56e+6;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var integer
     */
    protected $ttl;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateInterval
     */
    protected $interval;

    /**
     * Constructor.
     *
     * @param string    $key       The key
     * @param mixed     $data      The Data
     * @param integer   $ttl       The time to live
     * @param \DateTime $createdAt The created date time
     */
    public function __construct($key, $data, $ttl = CacheElement::DAY, \DateTime $createdAt = null)
    {
        $this->key = $key;
        $this->data = $data;
        $this->ttl = abs($ttl);
        $this->createdAt = (null === $createdAt) ? new \DateTime() : $createdAt;
        $this->interval = new \DateInterval(sprintf('PT%sS', $this->ttl));
    }

    /**
     * Gets the key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Gets the data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Gets the time to live.
     *
     * @return integer
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * Checks if the cache element is expired.
     *
     * @return boolean
     */
    public function isExpired()
    {
        $date = clone $this->createdAt;

        return new \DateTime() > $date->add($this->interval);
    }

    /**
     * Gets the expiration date.
     *
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        if ($this->isExpired()) {
            return new \DateTime();
        }

        $date = clone $this->createdAt;
        $date = $date->add($this->interval);

        return $date;
    }
}
