<?php

/**
 * This file is part of the Cubiche/EventBus component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\EventBus\Tests\Units\Middlewares\Locking;

use Cubiche\Core\EventBus\Middlewares\Locking\LockingMiddleware;
use Cubiche\Core\EventBus\Tests\Fixtures\LoginUserEvent;
use Cubiche\Core\EventBus\Tests\Units\TestCase;

/**
 * LockingMiddleware class.
 *
 * Generated by TestGenerator on 2016-04-11 at 15:18:25.
 */
class LockingMiddlewareTests extends TestCase
{
    /**
     * Test handle method.
     */
    public function testHandle()
    {
        $this
            ->given($middleware = new LockingMiddleware())
            ->and($event = new LoginUserEvent('ivan@cubiche.com'))
            ->and($callable = function (LoginUserEvent $event) {
                $event->setEmail('info@cubiche.org');
            })
            ->when($middleware->handle($event, $callable))
            ->then()
                ->string($event->email())
                    ->isEqualTo('info@cubiche.org')
        ;

        $this
            ->given($middleware = new LockingMiddleware())
            ->and($event = new LoginUserEvent('ivan@cubiche.com'))
            ->and($callable = function (LoginUserEvent $event) {
                $event->setEmail('info@cubiche.org');

                throw new \InvalidArgumentException();
            })
            ->then()
                ->exception(function () use ($middleware, $event, $callable) {
                    $middleware->handle($event, $callable);
                })
                ->isInstanceOf(\InvalidArgumentException::class)
        ;

        $this
            ->given($middleware = new LockingMiddleware())
            ->and($event = new LoginUserEvent('ivan@cubiche.com'))
            ->and($counter = 0)
            ->and($callable = function (LoginUserEvent $event) use (&$counter) {
                ++$counter;
            })
            ->when($middleware->handle($event, $callable))
            ->then()
                ->integer($counter)
                    ->isEqualTo(1)
            ->when($middleware->handle($event, $callable))
            ->then()
                ->integer($counter)
                    ->isEqualTo(2)
        ;
    }
}
