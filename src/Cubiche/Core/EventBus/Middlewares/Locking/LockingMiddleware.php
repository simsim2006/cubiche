<?php

/**
 * This file is part of the Cubiche/EventBus package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\EventBus\Middlewares\Locking;

use Cubiche\Core\EventBus\EventInterface;
use Cubiche\Core\EventBus\MiddlewareInterface;
use Cubiche\Core\Delegate\Delegate;

/**
 * LockingMiddleware class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class LockingMiddleware implements MiddlewareInterface
{
    /**
     * @var bool
     */
    private $isRunning;

    /**
     * @var Delegate[]
     */
    private $queue = [];

    /**
     * Execute the given event... after other running events are complete.
     *
     * @param EventInterface $event
     * @param callable       $next
     *
     * @throws \Exception
     */
    public function handle(EventInterface $event, callable $next)
    {
        $this->queue[] = Delegate::fromClosure(function () use ($event, $next) {
            return $next($event);
        });

        if ($this->isRunning) {
            return;
        }

        $this->isRunning = true;
        try {
            $this->runQueuedJobs();
        } catch (\Exception $e) {
            $this->isRunning = false;
            $this->queue = [];
            throw $e;
        }

        $this->isRunning = false;
    }

    /**
     * Process any pending events in the queue. If multiple, jobs are in the
     * queue, only the first return value is given back.
     *
     * @return mixed
     */
    protected function runQueuedJobs()
    {
        $returnValues = [];
        while ($lastCommand = array_shift($this->queue)) {
            $returnValues[] = $lastCommand->__invoke();
        }

        return array_shift($returnValues);
    }
}
