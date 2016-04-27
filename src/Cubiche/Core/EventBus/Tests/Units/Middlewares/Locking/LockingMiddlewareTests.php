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
use Cubiche\Core\EventBus\Tests\Fixtures\TriggerEventOnListener;
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
            ->and($callable = function (LoginUserEvent $event) {
                $event->setEmail(md5($event->email()));
            })
            ->and($listener = new TriggerEventOnListener($middleware, $callable))
            ->and($event = new LoginUserEvent('ivan@cubiche.com'))
            ->when($middleware->handle($event, array($listener, 'onLogin')))
            ->then()
                ->string($event->email())
                    ->isEqualTo(md5(sha1('ivan@cubiche.com')))
        ;
    }
}
