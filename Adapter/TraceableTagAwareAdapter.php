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

use Symfony\Component\Cache\Adapter\TraceableTagAwareAdapter as BaseTraceableTagAwareAdapter;

/**
 * Traceable Tag Aware Cache Adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class TraceableTagAwareAdapter extends BaseTraceableTagAwareAdapter implements TagAwareAdapterInterface
{
    use TraceableTrait;

    /**
     * Constructor.
     *
     * @param TagAwareAdapterInterface $pool The pool adapter
     */
    public function __construct(TagAwareAdapterInterface $pool)
    {
        parent::__construct($pool);
    }
}
