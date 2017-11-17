<?php

/**
 * This file is part of the Cubiche/EventSourcing component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Tests\Units;

use Cubiche\Core\Bus\MessageId;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Event\PostWasCreated;
use Cubiche\Domain\Model\Tests\Fixtures\PostId;

/**
 * DomainEventTests class.
 *
 * Generated by TestGenerator on 2016-06-28 at 14:36:54.
 */
class DomainEventTests extends TestCase
{
    /**
     * Test id method.
     */
    public function testId()
    {
        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->when(
                $event = new PostWasCreated(
                    $postId,
                    $this->faker->sentence,
                    $this->faker->paragraph
                )
            )
            ->then()
                ->object($event->id())
                    ->isInstanceOf(MessageId::class)
        ;
    }

    /**
     * Test AggregateId method.
     */
    public function testAggregateId()
    {
        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->when(
                $event = new PostWasCreated(
                    $postId,
                    $this->faker->sentence,
                    $this->faker->paragraph
                )
            )
            ->then()
                ->object($event->aggregateId())
                    ->isEqualTo($postId)
        ;
    }

    /**
     * Test Version method.
     */
    public function testVersion()
    {
        $this
            ->given($postId = PostId::fromNative(md5(rand())))
            ->when(
                $event = new PostWasCreated(
                    $postId,
                    $this->faker->sentence,
                    $this->faker->paragraph
                )
            )
            ->then()
                ->integer($event->version())
                    ->isEqualTo(0)
                ->and()
                ->when($event->setVersion(165))
                ->then()
                    ->integer($event->version())
                        ->isEqualTo(165)
        ;
    }

    /**
     * Test serialize/deserialize method.
     */
    public function testSerializeDeserialize()
    {
        $this
            ->given(
                $postId = PostId::fromNative(md5(rand())),
                $title = $this->faker->sentence,
                $content = $this->faker->sentence
            )
            ->and()
            ->when($event = new PostWasCreated($postId, $title, $content))
            ->and($eventFromArray = PostWasCreated::deserialize($event->serialize()))
            ->then()
                ->object($eventFromArray)
                    ->isInstanceOf(PostWasCreated::class)
                ->object($event->id())
                    ->isEqualTo($eventFromArray->id())
                ->string($event->eventName())
                    ->isEqualTo(PostWasCreated::class)
                ->object($event->occurredOn())
                    ->isEqualTo($eventFromArray->occurredOn())
                ->string($event->eventName())
                    ->isEqualTo($eventFromArray->eventName())
                ->string($event->title())
                    ->isEqualTo($eventFromArray->title())
                ->string($event->content())
                    ->isEqualTo($eventFromArray->content())
                ->boolean($event->isPropagationStopped())
                    ->isEqualTo($eventFromArray->isPropagationStopped())
        ;
    }
}
