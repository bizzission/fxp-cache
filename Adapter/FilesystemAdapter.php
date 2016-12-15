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

use Symfony\Component\Cache\Adapter\FilesystemAdapter as BaseFilesystemAdapter;

/**
 * Filesystem Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class FilesystemAdapter extends BaseFilesystemAdapter implements AdapterInterface
{
    use AdapterTrait;

    /**
     * {@inheritdoc}
     */
    protected function doClearByPrefix($namespace, $prefix)
    {
        $ok = true;
        $directory = $this->getPropertyValue('directory');
        $keys = array();

        /* @var \SplFileInfo $file */
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory,
                \FilesystemIterator::SKIP_DOTS)) as $file) {
            if ($file->isFile()) {
                if (!$h = @fopen($file, 'rb')) {
                    continue;
                }

                rawurldecode(rtrim(fgets($h)));
                $value = stream_get_contents($h);
                $key = substr($value, 0, strpos($value, "\n"));
                fclose($h);

                if ($prefix === '' || 0 === strpos($value, $prefix)) {
                    $keys[] = $key;
                }
            }

            $ok = ($file->isDir() || $this->deleteItems($keys) || !file_exists($file)) && $ok;
        }

        return $ok;
    }
}
