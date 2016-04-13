<?php

/**
 * This file is part of the Cubiche/Command component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\CommandBus\Tests\Units\Middlewares\Handler\Resolver\HandlerClass;

use Cubiche\Domain\CommandBus\Exception\InvalidLocatorException;
use Cubiche\Domain\CommandBus\Exception\NotFoundException;
use Cubiche\Domain\CommandBus\Middlewares\Handler\Locator\InMemoryLocator;
use Cubiche\Domain\CommandBus\Middlewares\Handler\Resolver\ClassName\DefaultResolver as ClassNameDefaultResolver;
use Cubiche\Domain\CommandBus\Middlewares\Handler\Resolver\HandlerClass\DefaultResolver as HandlerClassDefaultResolver;
use Cubiche\Domain\CommandBus\Middlewares\Handler\Resolver\MethodName\DefaultResolver as MethodNameDefaultResolver;
use Cubiche\Domain\CommandBus\Tests\Fixtures\LoginUserCommand;
use Cubiche\Domain\CommandBus\Tests\Fixtures\LoginUserCommandHandler;
use Cubiche\Domain\CommandBus\Tests\Fixtures\LogoutUserCommand;
use Cubiche\Domain\CommandBus\Tests\Units\TestCase;
use Cubiche\Core\Delegate\Delegate;

/**
 * DefaultResolver class.
 *
 * Generated by TestGenerator on 2016-04-07 at 15:40:41.
 */
class DefaultResolverTests extends TestCase
{
    /**
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($locator = new \StdClass())
            ->then()
            ->exception(function () use ($locator) {
                new HandlerClassDefaultResolver(
                    new ClassNameDefaultResolver(),
                    new MethodNameDefaultResolver(),
                    [$locator]
                );
            })
            ->isInstanceOf(InvalidLocatorException::class)
        ;
    }

    /**
     * Test Resolve method.
     */
    public function testResolve()
    {
        $this
            ->given(
                $resolver = new HandlerClassDefaultResolver(
                    new ClassNameDefaultResolver(),
                    new MethodNameDefaultResolver(),
                    [new InMemoryLocator([LoginUserCommand::class => new LoginUserCommandHandler()])]
                )
            )
            ->when($result = $resolver->resolve(new LoginUserCommand('ivan@timeout.com', 'plainpassword')))
            ->then()
                ->object($result)
                    ->isInstanceOf(Delegate::class)
        ;

        $this
            ->given(
                $resolver = new HandlerClassDefaultResolver(
                    new ClassNameDefaultResolver(),
                    new MethodNameDefaultResolver(),
                    [new InMemoryLocator([LoginUserCommand::class => new LoginUserCommandHandler()])]
                )
            )
            ->then()
                ->exception(function () use ($resolver) {
                    $resolver->resolve(new LogoutUserCommand('ivan@timeout.com'));
                })
                ->isInstanceOf(NotFoundException::class)
        ;
    }
}
