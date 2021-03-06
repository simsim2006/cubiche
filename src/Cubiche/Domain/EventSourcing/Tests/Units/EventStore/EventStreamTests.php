<?php

/**
 * This file is part of the Cubiche/EventSourcing component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Tests\Units\EventStore;

use Cubiche\Domain\EventSourcing\EventStore\EventStream;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasCreated;
use Cubiche\Domain\EventSourcing\Tests\Units\TestCase;
use Cubiche\Domain\Model\Tests\Fixtures\PostId;

/**
 * EventStreamTests class.
 *
 * Generated by TestGenerator on 2016-06-28 at 14:36:54.
 */
class EventStreamTests extends TestCase
{
    /**
     * Test StreamName method.
     */
    public function testStreamName()
    {
        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->and($streamName = 'Posts-'.$postId)
            ->and($eventStream = new EventStream($streamName, $postId, []))
            ->then()
                ->string($eventStream->streamName())
                    ->isEqualTo($streamName)
        ;
    }

    /**
     * Test AggregateId method.
     */
    public function testAggregateId()
    {
        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->and($streamName = 'Posts-'.$postId)
            ->and($eventStream = new EventStream($streamName, $postId, []))
            ->then()
                ->object($eventStream->aggregateId())
                    ->isEqualTo($postId)
        ;
    }

    /**
     * Test Events method.
     */
    public function testEvents()
    {
        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->and($streamName = 'Posts-'.$postId)
            ->and($eventStream = new EventStream($streamName, $postId, []))
            ->then()
                ->array($eventStream->events())
                    ->isEmpty()
        ;

        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->and($streamName = 'Posts-'.$postId)
            ->and($eventStream = new EventStream($streamName, $postId, [new PostWasCreated($postId, 'foo', 'bar')]))
            ->then()
                ->array($eventStream->events())
                    ->hasSize(1)
        ;

        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->and($streamName = 'Posts-'.$postId)
            ->then()
                ->exception(function () use ($streamName, $postId) {
                    new EventStream($streamName, $postId, ['bar']);
                })->isInstanceOf(\InvalidArgumentException::class)
        ;
    }
}
