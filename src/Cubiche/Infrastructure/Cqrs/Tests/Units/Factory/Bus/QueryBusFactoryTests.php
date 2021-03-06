<?php

/**
 * This file is part of the Cubiche/Cqrs component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Infrastructure\Cqrs\Tests\Units\Factory\Bus;

use Cubiche\Core\Bus\Middlewares\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Cqrs\Query\QueryBus;
use Cubiche\Infrastructure\Cqrs\Factory\Bus\QueryBusFactory;
use Cubiche\Infrastructure\Cqrs\Factory\QueryHandlerResolverFactory;
use Cubiche\Infrastructure\Cqrs\Factory\ValidatorHandlerResolverFactory;
use Cubiche\Infrastructure\Cqrs\Tests\Units\TestCase;

/**
 * QueryBusFactoryTests class.
 *
 * Generated by TestGenerator on 2017-05-03 at 11:41:18.
 */
class QueryBusFactoryTests extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createFactory()
    {
        return new QueryBusFactory();
    }

    /**
     * Test Create method.
     */
    public function testCreate()
    {
        $commandHandlerResolverFactory = new QueryHandlerResolverFactory(new InMemoryLocator([]));
        $validatorHandlerResolverFactory = new ValidatorHandlerResolverFactory(new InMemoryLocator([]));

        $this
            ->given($factory = $this->createFactory())
            ->and(
                $bus = $factory->create(
                    $commandHandlerResolverFactory->create(),
                    $validatorHandlerResolverFactory->create()
                )
            )
            ->then()
                ->object($bus)
                    ->isInstanceOf(QueryBus::class)
                ->and($reflectionClass = new \ReflectionClass(QueryBus::class))
                ->and($reflectionProperty = $reflectionClass->getProperty('middlewares'))
                ->and($reflectionProperty->setAccessible(true))
                ->array($reflectionProperty->getValue($bus)->toArray())
                    ->hasSize(2)
        ;
    }
}
