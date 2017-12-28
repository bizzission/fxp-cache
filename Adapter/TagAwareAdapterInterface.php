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

use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface as BaseTagAwareAdapterInterface;

/**
 * Tag Aware Cache Adapter Interface.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
interface TagAwareAdapterInterface extends AdapterInterface, BaseTagAwareAdapterInterface
{
}
