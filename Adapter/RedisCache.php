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

use Predis\Client;
use Sonatra\Component\Cache\CacheElement;
use Sonatra\Component\Cache\Counter;

/**
 * Redis Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class RedisCache extends AbstractCache
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * Constructor.
     *
     * @param string $prefix     A prefix to avoid clash between instances
     * @param array  $parameters The parameters of PRedis
     * @param array  $options    The options of PRedis
     */
    public function __construct($prefix, array $parameters = array(), array $options = array())
    {
        $options = array_replace(array('prefix' => $prefix), $options);

        $this->client =  new Client($parameters, $options);
        $this->prefix = $prefix;
    }

    /**
     * Gets the PRedis client.
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = CacheElement::HOUR)
    {
        $createAt = new \DateTime();
        $element = new CacheElement($key, $value, $ttl, $createAt);

        $cmdSet = $this->client->createCommand('set', array($key, serialize($element)));
        $cmdExpire = $this->client->createCommand('expire', array($key, $ttl));

        $this->client->executeCommand($cmdSet);
        $this->client->executeCommand($cmdExpire);

        return $element;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if ($this->has($key)) {
            $cmd = $this->client->createCommand('get', array($key));

            return unserialize($this->client->executeCommand($cmd));
        }

        return $this->createInvalidElement($key);
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        $cmd = $this->client->createCommand('exists', array($key));

        return $this->client->executeCommand($cmd);
    }

    /**
     * {@inheritdoc}
     */
    public function flush($key)
    {
        $cmd = $this->client->createCommand('del', array($key));

        return (bool) $this->client->executeCommand($cmd);
    }

    /**
     * {@inheritdoc}
     */
    public function flushAll($prefix = null)
    {
        if (null === $prefix) {
            $cmd = $this->client->createCommand('flushdb');

            return (bool) $this->client->executeCommand($cmd);
        }

        return $this->flushAllItems($prefix);
    }

    /**
     * {@inheritdoc}
     */
    public function setCounter(Counter $counter)
    {
        $cmd = $this->client->createCommand('set', array($counter->getName(), $counter->getValue()));

        $this->client->executeCommand($cmd);

        return $counter;
    }

    /**
     * {@inheritdoc}
     */
    public function getCounter($counter)
    {
        $cmd = $this->client->createCommand('get', array($counter));
        $value = (int) $this->client->executeCommand($cmd);

        return new Counter($counter, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function increment($counter, $value = 1)
    {
        return $this->doIncrement('incrby', $counter, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($counter, $value = 1)
    {
        return $this->doIncrement('decrby', $counter, $value);
    }

    /**
     * @param string         $cmd     The command
     * @param Counter|string $counter The counter
     * @param int            $value   The value
     *
     * @return Counter
     */
    protected function doIncrement($cmd, $counter, $value)
    {
        $counter = $this->transformCounter($counter);

        $cmd = $this->client->createCommand($cmd, array($counter->getName(), $value));
        $value = (int) $this->client->executeCommand($cmd);

        return new Counter($counter->getName(), $value);
    }

    /**
     * Flush all items cache.
     *
     * @param string $prefix
     *
     * @return bool
     */
    protected function flushAllItems($prefix)
    {
        $success = true;
        $cmd = $this->client->createCommand('keys', array($prefix.'*'));
        $list = $this->client->executeCommand($cmd);

        foreach ($list as $item) {
            $item = substr($item, strlen($this->prefix));
            $res = $this->flush($item);
            $success = !$res && $success ? false : $success;
        }

        return $success;
    }
}
