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

use Symfony\Component\Cache\Adapter\TraceableAdapter as BaseTraceableAdapter;

/**
 * Traceable Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class TraceableAdapter extends BaseTraceableAdapter implements AdapterInterface
{
    use TraceableTrait;
}
