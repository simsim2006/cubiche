<?php

/**
 * This file is part of the Cubiche/EventSourcing component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Tests\Units\Factory;

use Cubiche\Domain\EventSourcing\AggregateRepository;
use Cubiche\Domain\EventSourcing\EventStore\InMemoryEventStore;
use Cubiche\Domain\EventSourcing\Factory\AggregateRepositoryFactory;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\PostEventSourced;
use Cubiche\Domain\EventSourcing\Tests\Units\TestCase;

/**
 * AggregateRepositoryFactoryTests class.
 *
 * Generated by TestGenerator on 2016-06-28 at 14:36:54.
 */
class AggregateRepositoryFactoryTests extends TestCase
{
    /**
     * @return AggregateRepositoryFactory
     */
    protected function createFactory()
    {
        return new AggregateRepositoryFactory(new InMemoryEventStore());
    }

    /**
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($factory = $this->createFactory())
            ->and($repository = $factory->create(PostEventSourced::class))
            ->then()
                ->object($repository)
                    ->isInstanceOf(AggregateRepository::class)
                ->and()
                ->string($this->invoke($repository)->streamName())
                    ->isEqualTo('post_event_sourced')
        ;
    }
}
