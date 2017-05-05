<?php

/**
 * This file is part of the Cubiche/EventSourcing component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\EventSourcing\Tests\Units\Projector;

use Cubiche\Core\Specification\Criteria;
use Cubiche\Domain\EventPublisher\DomainEventPublisher;
use Cubiche\Domain\EventSourcing\AggregateRepository;
use Cubiche\Domain\EventSourcing\EventStore\InMemoryEventStore;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\PostEventSourced;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\Projector\PublishedPostProjector;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\ReadModel\PublishedPost;
use Cubiche\Domain\EventSourcing\Tests\Units\TestCase;
use Cubiche\Domain\Model\Tests\Fixtures\PostId;
use Cubiche\Domain\Repository\InMemory\InMemoryQueryRepository;
use Cubiche\Domain\Repository\QueryRepositoryInterface;

/**
 * ProjectorTests class.
 *
 * Generated by TestGenerator on 2016-06-28 at 14:36:54.
 */
class ProjectorTests extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createProjector(QueryRepositoryInterface $repository)
    {
        return new PublishedPostProjector($repository);
    }

    /**
     * Test create.
     */
    public function testCreate()
    {
        $readModelRepository = new InMemoryQueryRepository(PublishedPost::class);
        $writeModelRepository = new AggregateRepository(new InMemoryEventStore(), PostEventSourced::class);

        $this
            ->given($projector = $this->createProjector($readModelRepository))
            ->and(DomainEventPublisher::subscribe($projector))
            ->and(
                $postId = PostId::fromNative(md5(rand())),
                $writeModel = new PostEventSourced(
                    $postId,
                    'The post title',
                    'The post description'
                )
            )
            ->then()
                ->boolean($readModelRepository->isEmpty())
                    ->isTrue()
                ->and()
                ->when($writeModelRepository->persist($writeModel))
                ->then()
                    ->boolean($readModelRepository->isEmpty())
                        ->isTrue()
                ->and()
                ->when($writeModel->publish())
                ->and($writeModelRepository->persist($writeModel))
                ->then()
                    ->boolean($readModelRepository->isEmpty())
                        ->isFalse()
                    ->object($readModel = $readModelRepository->findOne(Criteria::property('id')->eq($postId)))
                        ->isInstanceOf(PublishedPost::class)
                    ->string($readModel->title())
                        ->isEqualTo('The post title')
                ->and()
                ->when($writeModel->changeTitle('foo'))
                ->and($writeModelRepository->persist($writeModel))
                ->then()
                    ->object($readModel = $readModelRepository->findOne(Criteria::property('id')->eq($postId)))
                        ->isInstanceOf(PublishedPost::class)
                    ->string($readModel->title())
                        ->isEqualTo('foo')
                ->and()
                ->when($writeModel->unpublish())
                ->and($writeModelRepository->persist($writeModel))
                ->then()
                    ->boolean($readModelRepository->isEmpty())
                        ->isTrue()
        ;
    }
}
