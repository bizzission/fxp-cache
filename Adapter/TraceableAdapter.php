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

use Symfony\Component\Cache\Adapter\TraceableAdapter as BaseTraceableAdapter;

/**
 * Traceable Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TraceableAdapter extends BaseTraceableAdapter implements AdapterInterface
{
    use TraceableTrait;
}
