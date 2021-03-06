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

use Cubiche\Domain\EventSourcing\Projector\Action;
use Cubiche\Domain\EventSourcing\Projector\Projection;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\ReadModel\PublishedPost;
use Cubiche\Domain\EventSourcing\Tests\Units\TestCase;
use Cubiche\Domain\Model\Tests\Fixtures\PostId;

/**
 * ProjectionTests class.
 *
 * Generated by TestGenerator on 2017-05-05 at 11:37:18.
 */
class ProjectionTests extends TestCase
{
    /**
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given(
                $readModel = new PublishedPost(
                    PostId::fromNative(md5(rand())),
                    $this->faker->title,
                    $this->faker->text()
                )
            )
            ->and($action = Action::NONE())
            ->when($projection = new Projection($action, $readModel))
            ->then()
                ->object($projection->readModel())
                    ->isEqualTo($readModel)
                ->object($projection->action())
                    ->isEqualTo($action)
        ;
    }

    /**
     * Test SetReadModel method.
     */
    public function testSetReadModel()
    {
        $this
            ->given(
                $readModel = new PublishedPost(
                    PostId::fromNative(md5(rand())),
                    $this->faker->title,
                    $this->faker->text()
                )
            )
            ->and(
                $newReadModel = new PublishedPost(
                    PostId::fromNative(md5(rand())),
                    $this->faker->title,
                    $this->faker->text()
                )
            )
            ->and($action = Action::NONE())
            ->when($projection = new Projection($action, $readModel))
            ->then()
                ->object($projection->readModel())
                    ->isEqualTo($readModel)
                ->and()
                ->when($projection->setReadModel($newReadModel))
                ->then()
                    ->object($projection->readModel())
                        ->isEqualTo($newReadModel)
        ;
    }

    /**
     * Test SetAction method.
     */
    public function testSetAction()
    {
        $this
            ->given(
                $readModel = new PublishedPost(
                    PostId::fromNative(md5(rand())),
                    $this->faker->title,
                    $this->faker->text()
                )
            )
            ->and($action = Action::NONE())
            ->when($projection = new Projection($action, $readModel))
            ->then()
                ->object($projection->action())
                    ->isEqualTo($action)
                ->and()
                ->when($projection->setAction(Action::REMOVE()))
                ->then()
                    ->object($projection->action())
                        ->isEqualTo(Action::REMOVE())
        ;
    }
}
