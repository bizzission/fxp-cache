<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Cache\Adapter;

use Symfony\Component\Cache\Adapter\PdoAdapter as BasePdoAdapter;

/**
 * Pdo Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class PdoAdapter extends BasePdoAdapter implements AdapterInterface
{
    use AdapterTrait;

    /**
     * {@inheritdoc}
     */
    protected function doClearByPrefix($namespace, $prefix)
    {
        return $this->doClear($namespace.$prefix);
    }
}
