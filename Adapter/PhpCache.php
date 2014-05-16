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
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * PHP Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PhpCache extends AbstractCache
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Constructor.
     *
     * @param string     $path       The cache path
     * @param string     $prefix     A prefix to avoid clash between instances
     * @param Filesystem $filesystem The file system instance
     */
    public function __construct($path, $prefix, Filesystem $filesystem = null)
    {
        $this->path = $path;
        $this->prefix = $prefix;
        $this->filesystem = null !== $filesystem ? $filesystem : new Filesystem();
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value, $ttl = CacheElement::HOUR)
    {
        $createAt = new \DateTime();
        $content = sprintf("<?php\n\nreturn array('createdAt' => %s, 'ttl' => %s, 'data' => '%s');\n", $createAt->format('U'), $ttl, serialize($value));
        $mode = 0666 & ~umask();
        $element = new CacheElement($key, $value, $ttl, $createAt);

        $this->filesystem->dumpFile($this->getCacheKey($key), $content, $mode);

        return $element;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        $file = $this->getCacheKey($key);

        if ($this->filesystem->exists($file)) {
            $data = include $file;
            $element = new CacheElement($key, unserialize($data['data']), $data['ttl'], new \DateTime('@' . $data['createdAt']));

            if (!$element->isExpired()) {
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
        return $this->filesystem->exists($this->getCacheKey($key));
    }

    /**
     * {@inheritdoc}
     */
    public function flush($key)
    {
        try {
            $this->filesystem->remove($this->getCacheKey($key));

        } catch (IOException $e) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function flushAll($prefix = null)
    {
        $success = true;
        $finder = new Finder();
        $finder->files()->in($this->getCachePath());

        /* @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $key = substr($file->getFilename(), 0, strlen($file->getFilename()) - 4);

            if (null === $prefix || 0 === strpos($key, $prefix)) {
                $res = $this->flush($key);
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
        $file = $this->getCacheKey($counter->getName());
        $this->filesystem->mkdir(dirname($file));

        file_put_contents($file, $counter->getValue());

        return $counter;
    }

    /**
     * {@inheritdoc}
     */
    public function getCounter($counter)
    {
        $file = $this->getCacheKey($counter);
        $value = 0;

        if (file_exists($file)) {
            $value = (integer) file_get_contents($file);
        }

        return new Counter($counter, $value);
    }

    /**
     * Gets the cache path.
     *
     * @return string
     */
    protected function getCachePath()
    {
        $prefix = null === $this->prefix ? '' : sprintf('/%s', $this->prefix);

        return sprintf('%s%s', $this->path, $prefix);
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
        return sprintf('%s/%s.php', $this->getCachePath(), $key);
    }
}
