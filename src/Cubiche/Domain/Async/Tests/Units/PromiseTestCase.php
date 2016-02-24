<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Async\Tests\Units;

use Cubiche\Domain\Async\PromiseInterface;
use Cubiche\Domain\Tests\Units\TestCase;
use mageekguy\atoum\adapter;
use mageekguy\atoum\annotations\extractor;
use mageekguy\atoum\asserter\generator;
use mageekguy\atoum\mock;
use mageekguy\atoum\test\assertion\manager;

/**
 * PromiseTestCase class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
abstract class PromiseTestCase extends TestCase
{
    /**
     * {@inheritdoc}
     */
    public function __construct(
        adapter $adapter = null,
        extractor $annotationExtractor = null,
        generator $asserterGenerator = null,
        manager $assertionManager = null,
        \closure $reflectionClassFactory = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory
        );

        $this->getAssertionManager()
            ->setHandler(
                'promise',
                function () {
                    return $this->promise();
                }
            )
            ->setHandler(
                'resolve',
                function ($value = null) {
                    return $this->resolve($value);
                }
            )
            ->setHandler(
                'reject',
                function ($value = null) {
                    return $this->reject($value);
                }
            )
            ->setHandler(
                'notify',
                function ($value = null) {
                    return $this->notify($value);
                }
            )
            ->setHandler(
                'cancel',
                function ($value = null) {
                    return $this->cancel($value);
                }
            )
            ->setHandler(
                'delegateMock',
                function () {
                    return new \mock\Cubiche\Domain\Delegate\Delegate(function ($value = null) {
                        return $value;
                    });
                }
            )
            ->setHandler(
                'delegateMockWithReturn',
                function ($return) {
                    return new \mock\Cubiche\Domain\Delegate\Delegate(function ($value = null) use ($return) {
                        return $return;
                    });
                }
            )
            ->setHandler(
                'delegateCall',
                function (mock\aggregator $mock) {
                    return $this
                        ->getMock($mock)
                        ->call('__invoke')
                    ;
                }
            )
        ;
    }

    /**
     * @return PromiseInterface
     */
    abstract protected function promise();

    /**
     * @param mixed $value
     */
    abstract protected function resolve($value = null);

    /**
     * @param mixed $reason
     */
    abstract protected function reject($reason = null);

    /**
     * @param mixed $state
     */
    abstract protected function notify($state = null);

    /**
     * @return bool
     */
    abstract protected function cancel();

    /*
     * Test then.
     */
    public function testThen()
    {
        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock(),
                $rejected = $this->delegateMock(),
                $notify = $this->delegateMock()
            )
            ->if($promise->then($succeed, $rejected, $notify))
            ->when($this->resolve('foo'))
            ->then
                ->delegateCall($succeed)
                    ->withArguments('foo')
                    ->once()
                ->delegateCall($rejected)
                    ->never()
                ->delegateCall($notify)
                    ->never()
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock(),
                $rejected = $this->delegateMock(),
                $notify = $this->delegateMock()
            )
            ->if($promise->then($succeed, $rejected, $notify))
            ->when($this->reject($reason = new \Exception()))
            ->then
                ->delegateCall($succeed)
                    ->never()
                ->delegateCall($rejected)
                    ->withArguments($reason)
                    ->once()
                ->delegateCall($notify)
                    ->never()
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock(),
                $rejected = $this->delegateMock(),
                $notify = $this->delegateMock()
            )
            ->if($promise->then($succeed, $rejected, $notify))
            ->when(function () {
                for ($i = 0; $i < 10; ++$i) {
                    $this->notify(($i + 1) * 10);
                }
            })
            ->then
                ->delegateCall($succeed)
                    ->never()
                ->delegateCall($rejected)
                    ->never()
                ->delegateCall($notify)
                    ->exactly(10)
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMockWithReturn('bar'),
                $rejected = $this->delegateMock(),
                $notify = $this->delegateMock(),
                $succeedThen = $this->delegateMock()
            )
            ->if($promiseThen = $promise->then($succeed, $rejected, $notify))
            ->and($promiseThen->then($succeedThen))
            ->when($this->resolve('foo'))
            ->then
                ->object($promiseThen)
                    ->isInstanceOf(PromiseInterface::class)
                ->delegateCall($succeed)
                    ->withArguments('foo')
                    ->once()
                ->delegateCall($rejected)
                    ->never()
                ->delegateCall($notify)
                    ->never()
                ->delegateCall($succeedThen)
                    ->withArguments('bar')
                    ->once()
        ;
    }

    /*
     * Test otherwise.
     */
    public function testOtherwise()
    {
        $this
            ->given(
                $promise = $this->promise(),
                $otherwise = $this->delegateMock()
            )
            ->if($promiseOtherwise = $promise->otherwise($otherwise))
            ->when($this->reject($reason = new \Exception()))
            ->then
                ->object($promiseOtherwise)
                    ->isInstanceOf(PromiseInterface::class)
                ->delegateCall($otherwise)
                    ->withArguments($reason)
                    ->once()
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock(),
                $rejected = $this->delegateMock(),
                $notify = $this->delegateMock(),
                $otherwise = $this->delegateMock()
            )
            ->if(
                $promiseOtherwise = $promise
                    ->then($succeed, $rejected, $notify)
                    ->otherwise($otherwise)
            )
            ->when($this->reject($reason = new \Exception()))
            ->then
                ->object($promiseOtherwise)
                    ->isInstanceOf(PromiseInterface::class)
                ->delegateCall($rejected)
                    ->withArguments($reason)
                    ->once()
                ->delegateCall($otherwise)
                    ->withArguments($reason)
                    ->once()
        ;
    }

    /*
     * Test always.
     */
    public function testAlways()
    {
        $this
            ->given(
                $promise = $this->promise(),
                $notify = $this->delegateMock(),
                $finally = $this->delegateMock()
            )
            ->if($promiseAlways = $promise->always($finally, $notify))
            ->when($this->resolve('foo'))
            ->then
                ->object($promiseAlways)
                    ->isInstanceOf(PromiseInterface::class)
                ->delegateCall($finally)
                    ->withArguments('foo')
                    ->once()
                ->delegateCall($notify)
                    ->never()
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $notify = $this->delegateMock(),
                $finally = $this->delegateMock()
            )
            ->if($promiseAlways = $promise->always($finally, $notify))
            ->when($this->reject($reason = new \Exception()))
            ->then
                ->delegateCall($finally)
                    ->withArguments(null, $reason)
                    ->once()
                ->delegateCall($notify)
                    ->never()
        ;
    }

    /*
     * Test cancel.
     */
    public function testCancel()
    {
        $this
            ->given(
                $promise = $this->promise(),
                $otherwise = $this->delegateMock()
            )
            ->if($promise->otherwise($otherwise))
            ->when($this->cancel())
            ->then
                ->delegateCall($otherwise)
                    ->with()
                    ->arguments(0, function ($argument) {
                        $this->object($argument)->isInstanceOf(\RuntimeException::class);
                    })
                    ->once()
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock(),
                $rejected = $this->delegateMock(),
                $notify = $this->delegateMock()
            )
            ->if($promise->then($succeed, $rejected, $notify))
            ->when($this->resolve('foo'))
            ->and($this->cancel())
            ->then
                ->delegateCall($succeed)
                    ->withArguments('foo')
                    ->once()
                ->delegateCall($rejected)
                    ->never()
                ->delegateCall($notify)
                    ->never()
        ;
    }

    /*
     * Test resolved promise.
     */
    public function testResolvedPromise()
    {
        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock()
            )
            ->if($promise->then($succeed))
            ->when($this->resolve())
            ->exception(
                function () {
                    $this->resolve();
                }
            )->isInstanceOf(\LogicException::class)
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock()
            )
            ->if($promise->then($succeed))
            ->when($this->resolve())
            ->exception(
                function () {
                    $this->reject();
                }
            )->isInstanceOf(\LogicException::class)
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock()
            )
            ->if($promise->then($succeed))
            ->when($this->resolve())
            ->exception(
                function () {
                    $this->notify();
                }
            )->isInstanceOf(\LogicException::class)
        ;
    }

    /*
     * Test rejected promise.
     */
    public function testRejectedPromise()
    {
        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock()
            )
            ->if($promise->then($succeed))
            ->when($this->reject())
            ->exception(
                function () {
                    $this->resolve();
                }
            )->isInstanceOf(\LogicException::class)
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock()
            )
            ->if($promise->then($succeed))
            ->when($this->reject())
            ->exception(
                function () {
                    $this->reject();
                }
            )->isInstanceOf(\LogicException::class)
        ;

        $this
            ->given(
                $promise = $this->promise(),
                $succeed = $this->delegateMock()
            )
            ->if($promise->then($succeed))
            ->when($this->reject())
            ->exception(
                function () {
                    $this->notify();
                }
            )->isInstanceOf(\LogicException::class)
        ;
    }
}