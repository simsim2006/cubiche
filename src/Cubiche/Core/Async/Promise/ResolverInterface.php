<?php

/*
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Async\Promise;

/**
 * Resolver Interface.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ResolverInterface
{
    /**
     * @param mixed $value
     */
    public function resolve($value = null);

    /**
     * @param mixed $reason
     */
    public function reject($reason = null);

    /**
     * @param mixed $state
     */
    public function notify($state = null);
}
